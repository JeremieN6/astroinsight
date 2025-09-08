<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Quote;
use App\Repository\ClientRepository;
use App\Repository\QuoteRepository;
use App\Service\QuoteGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/quotes')]
#[IsGranted('ROLE_USER')]
class QuoteController extends AbstractController
{
    public function __construct(
        private QuoteRepository $quoteRepository,
        private ClientRepository $clientRepository,
        private QuoteGeneratorService $quoteGeneratorService,
        private EntityManagerInterface $entityManager,
        private \App\Service\DomPDFService $domPdfService,
        private LoggerInterface $logger
    ) {}

    #[Route('/', name: 'quote_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        $quotes = $this->quoteRepository->findByUser($user);
        $stats = $this->quoteRepository->getStatsByUser($user);

        return $this->render('quote/index.html.twig', [
            'quotes' => $quotes,
            'stats' => $stats,
        ]);
    }

    #[Route('/new', name: 'quote_new')]
    public function new(): Response
    {
        $user = $this->getUser();
        $clients = $this->clientRepository->findByUser($user);

        return $this->render('quote/new.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/{id}', name: 'quote_show', requirements: ['id' => '\d+'])]
    public function show(Quote $quote): Response
    {
        // Vérification que le devis appartient à l'utilisateur connecté
        if ($quote->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
        ]);
    }

    #[Route('/api/generate', name: 'api_quote_generate', methods: ['POST'])]
    public function generateFromEstimation(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['clientId'], $data['estimationData'], $data['title'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Données manquantes (clientId, estimationData, title)'
                ], 400);
            }

            $user = $this->getUser();
            
            // Récupération du client
            $client = $this->clientRepository->find($data['clientId']);
            if (!$client || $client->getUser() !== $user) {
                return $this->json([
                    'success' => false,
                    'error' => 'Client non trouvé ou non autorisé'
                ], 404);
            }

            // Génération du devis
            $quote = $this->quoteGeneratorService->generateQuoteFromEstimation(
                $user,
                $client,
                $data['estimationData'],
                $data['title'],
                $data['description'] ?? null
            );

            return $this->json([
                'success' => true,
                'quote' => [
                    'id' => $quote->getId(),
                    'quoteNumber' => $quote->getQuoteNumber(),
                    'totalHt' => $quote->getTotalHt(),
                    'totalTtc' => $quote->getTotalTtc(),
                ],
                'redirectUrl' => $this->generateUrl('quote_show', ['id' => $quote->getId()])
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur lors de la génération : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/clients/search', name: 'api_clients_search', methods: ['GET'])]
    public function searchClients(Request $request): JsonResponse
    {
        $search = $request->query->get('q', '');
        $user = $this->getUser();

        if (strlen($search) < 2) {
            $clients = $this->clientRepository->findByUser($user);
        } else {
            $clients = $this->clientRepository->searchByNameOrCompany($user, $search);
        }

        $results = array_map(function(Client $client) {
            return [
                'id' => $client->getId(),
                'name' => $client->getName(),
                'company' => $client->getCompany(),
                'email' => $client->getEmail(),
                'display' => $client->getCompany()
                    ? "{$client->getName()} ({$client->getCompany()})"
                    : $client->getName()
            ];
        }, $clients);

        return $this->json($results);
    }

    #[Route('/api/clients', name: 'api_clients_create', methods: ['POST'])]
    public function createClient(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!$data || !isset($data['name'], $data['email'], $data['city'], $data['postalCode'], $data['address'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Données manquantes (name, email, city, postalCode, address)'
                ], 400);
            }

            $user = $this->getUser();

            // Basic server-side validation and sanitization
            $postalCode = trim((string) $data['postalCode']);
            // Allow letters, numbers, spaces and hyphens, max length 10
            if (!preg_match('/^[A-Za-z0-9 \-]{1,10}$/', $postalCode)) {
                return $this->json([
                    'success' => false,
                    'error' => 'Code postal invalide. Autorisé : lettres, chiffres, espaces et tirets (max 10 caractères).'
                ], 422);
            }

            // Création du client
            $client = new Client();
            $client->setUser($user);
            $client->setName($data['name']);
            $client->setEmail($data['email']);
            $client->setCity($data['city']);
            $client->setPostalCode($postalCode);
            $client->setAddress($data['address']);

            if (isset($data['company'])) {
                $client->setCompany($data['company']);
            }
            if (isset($data['phone'])) {
                $client->setPhone($data['phone']);
            }
            if (isset($data['siret'])) {
                $client->setSiret($data['siret']);
            }
            if (isset($data['tvaNumber'])) {
                $client->setTvaNumber($data['tvaNumber']);
            }

            $this->entityManager->persist($client);
            try {
                $this->entityManager->flush();
            } catch (\Throwable $e) {
                // Log and return structured error instead of letting a PHP warning bubble up
                $this->logger->error('Erreur lors de la persistance du client: ' . $e->getMessage());
                return $this->json([
                    'success' => false,
                    'error' => 'Impossible de créer le client pour le moment.'
                ], 500);
            }

            return $this->json([
                'success' => true,
                'client' => [
                    'id' => $client->getId(),
                    'name' => $client->getName(),
                    'company' => $client->getCompany(),
                    'email' => $client->getEmail(),
                    'display' => $client->getCompany()
                        ? "{$client->getName()} ({$client->getCompany()})"
                        : $client->getName()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur lors de la création : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}/status', name: 'quote_update_status', methods: ['PATCH'])]
    public function updateStatus(Quote $quote, Request $request): JsonResponse
    {
        // Vérification que le devis appartient à l'utilisateur connecté
        if ($quote->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);
        $newStatus = $data['status'] ?? null;

        $validStatuses = [
            Quote::STATUS_DRAFT,
            Quote::STATUS_SENT,
            Quote::STATUS_ACCEPTED,
            Quote::STATUS_REFUSED,
            Quote::STATUS_EXPIRED
        ];

        if (!in_array($newStatus, $validStatuses)) {
            return $this->json([
                'success' => false,
                'error' => 'Statut invalide'
            ], 400);
        }

        try {
            $this->quoteGeneratorService->updateQuoteStatus($quote, $newStatus);

            return $this->json([
                'success' => true,
                'status' => $quote->getStatus(),
                'statusLabel' => $quote->getStatusLabel()
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}/pdf', name: 'quote_pdf')]
    public function downloadPdf(Quote $quote): Response
    {
        // Vérification que le devis appartient à l'utilisateur connecté
        if ($quote->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        // Génération du HTML via un template dédié puis conversion en PDF
        try {
            // Le service DomPDFService renvoie le binaire PDF
            $pdfContent = $this->domPdfService->generateQuotePdf($quote);

            $filename = sprintf('devis-%s.pdf', $quote->getQuoteNumber() ?? $quote->getId());

            $response = new Response($pdfContent);
            $disposition = $response->headers->makeDisposition(
                'attachment',
                $filename
            );
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', $disposition);

            // Mettre à jour le compteur de téléchargements
            try {
                $quote->incrementDownloadCount();
                $quote->setLastDownloadedAt(new \DateTimeImmutable());
                $this->entityManager->persist($quote);
                $this->entityManager->flush();
            } catch (\Throwable $e) {
                // Ne pas empêcher la réponse si la persistance échoue
                $this->logger->warning('Impossible de mettre à jour le compteur de téléchargement: ' . $e->getMessage());
            }

            return $response;

        } catch (\Exception $e) {
            // En cas d'erreur PDF, logguer et rediriger vers la vue
            $this->logger->error('Erreur génération PDF devis : ' . $e->getMessage());
            return $this->redirectToRoute('quote_show', ['id' => $quote->getId()]);
        }
    }
}
