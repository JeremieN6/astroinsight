<?php

namespace App\Controller;

use App\Form\UserProfileFormType;
use App\Repository\PlanRepository;
use App\Repository\QuoteRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PlanRepository $planRepository): Response
    {
        $plans = $planRepository->findAll();
        return $this->render('home/index.html.twig', [
            'page_title' => 'QuickEsti - Estimations de projets web avec IA',
            'meta_description' => 'Créez des devis professionnels en quelques clics avec notre IA. Adapté aux freelances et entreprises.',
            'plans' => $plans,
        ]);
    }

    #[Route('/estimation', name: 'app_estimation')]
    public function estimation(): Response
    {
        // La sécurité est gérée par security.yaml (ROLE_USER requis)
        // Pas besoin de vérification manuelle ici
        return $this->render('estimation/index.html.twig', [
            'page_title' => 'Estimation de projet - QuickEsti',
            'meta_description' => 'Utilisez notre outil d\'estimation intelligent pour créer votre devis personnalisé.',
        ]);
    }

    #[Route('/estimation-v2', name: 'app_estimation_v2')]
    public function estimationV2(): Response
    {
        return $this->render('estimation/v2.html.twig', [
            'page_title' => 'Estimation de projet V2 - QuickEsti',
            'meta_description' => 'Nouvelle version de notre outil d\'estimation avec interface guidée par étapes.',
        ]);
    }

    #[Route('/mon-compte', name: 'app_account')]
    public function main(Request $request, EntityManagerInterface $entityManager, QuoteRepository $quoteRepository, ClientRepository $clientRepository): Response
    {
        // Vérifier que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        // Vérification supplémentaire au cas où
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Créer le formulaire de profil
        $profileForm = $this->createForm(UserProfileFormType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');

            return $this->redirectToRoute('app_account');
        }

        // Compteurs dynamiques
    $quotesCount = $quoteRepository->countByUser($user);
    $estimationsCount = $quoteRepository->countEstimationsByUser($user);
    $acceptedQuotes = $quoteRepository->countAcceptedByUser($user);
    $clientsCount = $clientRepository->countByUser($user);
    $downloadsCount = $quoteRepository->sumDownloadsByUser($user);

        return $this->render('main/dashboard.html.twig', [
            'page_title' => 'Tableau de bord - QuickEsti',
            'user' => $user,
            'profileForm' => $profileForm->createView(),
            'counters' => [
                'quotes' => $quotesCount,
                'estimations' => $estimationsCount,
                'accepted' => $acceptedQuotes,
                'clients' => $clientsCount,
                'downloads' => $downloadsCount,
            ],
        ]);
    }
}
