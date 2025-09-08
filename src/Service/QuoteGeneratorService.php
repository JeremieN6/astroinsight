<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\Quote;
use App\Entity\QuoteItem;
use App\Entity\Users;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class QuoteGeneratorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuoteRepository $quoteRepository,
        private LoggerInterface $logger
    ) {}

    /**
     * Génère un devis à partir des données d'estimation
     */
    public function generateQuoteFromEstimation(
        Users $user,
        Client $client,
        array $estimationData,
        string $title,
        ?string $description = null
    ): Quote {
        try {
            // Création du devis
            $quote = new Quote();
            $quote->setUser($user);
            $quote->setClient($client);
            $quote->setQuoteNumber($this->quoteRepository->generateNextQuoteNumber());
            $quote->setTitle($title);
            $quote->setDescription($description);
            $quote->setEstimationData($estimationData);

            // Génération des postes à partir de l'estimation
            $this->generateQuoteItemsFromEstimation($quote, $estimationData);

            // Calcul des totaux
            $this->calculateTotals($quote);

            // Définition des conditions de paiement par défaut
            $this->setDefaultPaymentTerms($quote);

            // Sauvegarde
            $this->entityManager->persist($quote);
            $this->entityManager->flush();

            $this->logger->info('Devis généré avec succès', [
                'quote_id' => $quote->getId(),
                'quote_number' => $quote->getQuoteNumber(),
                'user_id' => $user->getId(),
                'client_id' => $client->getId()
            ]);

            return $quote;

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la génération du devis', [
                'user_id' => $user->getId(),
                'client_id' => $client->getId(),
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Impossible de générer le devis : ' . $e->getMessage());
        }
    }

    /**
     * Génère les postes du devis à partir des données d'estimation
     */
    private function generateQuoteItemsFromEstimation(Quote $quote, array $estimationData): void
    {
        $userType = $estimationData['userType'] ?? 'freelance';
        $estimation = $estimationData['estimation'] ?? [];
        $breakdown = $estimation['breakdown'] ?? [];

        $sortOrder = 1;

        // Génération des postes selon le type d'utilisateur
        if ($userType === 'freelance') {
            $this->generateFreelanceItems($quote, $breakdown, $sortOrder);
        } else {
            $this->generateEnterpriseItems($quote, $breakdown, $sortOrder);
        }
    }

    /**
     * Génère les postes pour un freelance
     */
    private function generateFreelanceItems(Quote $quote, array $breakdown, int &$sortOrder): void
    {
        // Analyse et développement
        if (isset($breakdown['analysis'])) {
            $item = new QuoteItem();
            $item->setQuote($quote);
            $item->setDescription('Analyse et conception du projet');
            $item->setQuantity($breakdown['analysis']['days'] ?? '1');
            $item->setUnit('jour');
            $item->setUnitPrice($breakdown['analysis']['dailyRate'] ?? '400');
            $item->setSortOrder($sortOrder++);
            $quote->addItem($item);
        }

        // Développement
        if (isset($breakdown['development'])) {
            $item = new QuoteItem();
            $item->setQuote($quote);
            $item->setDescription('Développement et intégration');
            $item->setQuantity($breakdown['development']['days'] ?? '5');
            $item->setUnit('jour');
            $item->setUnitPrice($breakdown['development']['dailyRate'] ?? '400');
            $item->setSortOrder($sortOrder++);
            $quote->addItem($item);
        }

        // Tests et déploiement
        if (isset($breakdown['testing'])) {
            $item = new QuoteItem();
            $item->setQuote($quote);
            $item->setDescription('Tests et mise en production');
            $item->setQuantity($breakdown['testing']['days'] ?? '1');
            $item->setUnit('jour');
            $item->setUnitPrice($breakdown['testing']['dailyRate'] ?? '400');
            $item->setSortOrder($sortOrder++);
            $quote->addItem($item);
        }
    }

    /**
     * Génère les postes pour une entreprise
     */
    private function generateEnterpriseItems(Quote $quote, array $breakdown, int &$sortOrder): void
    {
        // Gestion de projet
        if (isset($breakdown['management'])) {
            $item = new QuoteItem();
            $item->setQuote($quote);
            $item->setDescription('Gestion de projet et coordination');
            $item->setQuantity($breakdown['management']['days'] ?? '2');
            $item->setUnit('jour');
            $item->setUnitPrice($breakdown['management']['dailyRate'] ?? '600');
            $item->setSortOrder($sortOrder++);
            $quote->addItem($item);
        }

        // Équipe de développement
        if (isset($breakdown['team'])) {
            foreach ($breakdown['team'] as $role => $data) {
                $item = new QuoteItem();
                $item->setQuote($quote);
                $item->setDescription($this->getRoleDescription($role));
                $item->setQuantity($data['days'] ?? '5');
                $item->setUnit('jour');
                $item->setUnitPrice($data['dailyRate'] ?? '500');
                $item->setSortOrder($sortOrder++);
                $quote->addItem($item);
            }
        }
    }

    /**
     * Calcule les totaux du devis
     */
    private function calculateTotals(Quote $quote): void
    {
        $totalHt = '0.00';
        
        foreach ($quote->getItems() as $item) {
            $totalHt = bcadd($totalHt, $item->getTotalPrice(), 2);
        }

        $tvaAmount = bcmul($totalHt, bcdiv($quote->getTvaRate(), '100', 4), 2);
        $totalTtc = bcadd($totalHt, $tvaAmount, 2);

        $quote->setTotalHt($totalHt);
        $quote->setTotalTtc($totalTtc);
    }

    /**
     * Définit les conditions de paiement par défaut
     */
    private function setDefaultPaymentTerms(Quote $quote): void
    {
        $terms = "Conditions de paiement :\n";
        $terms .= "- Acompte de 30% à la signature du devis\n";
        $terms .= "- Solde à la livraison du projet\n";
        $terms .= "- Paiement par virement bancaire\n";
        $terms .= "- Délai de paiement : 30 jours";

        $quote->setPaymentTerms($terms);
    }

    /**
     * Retourne la description d'un rôle
     */
    private function getRoleDescription(string $role): string
    {
        return match($role) {
            'frontend' => 'Développement Frontend',
            'backend' => 'Développement Backend',
            'fullstack' => 'Développement Full-Stack',
            'designer' => 'Design UX/UI',
            'devops' => 'DevOps et Infrastructure',
            default => ucfirst($role)
        };
    }

    /**
     * Met à jour le statut d'un devis
     */
    public function updateQuoteStatus(Quote $quote, string $status): void
    {
        $oldStatus = $quote->getStatus();
        $quote->setStatus($status);

        // Actions spécifiques selon le statut
        switch ($status) {
            case Quote::STATUS_SENT:
                if (!$quote->getSentAt()) {
                    $quote->setSentAt(new \DateTimeImmutable());
                }
                break;
            case Quote::STATUS_ACCEPTED:
                $quote->setAcceptedAt(new \DateTimeImmutable());
                break;
        }

        $this->entityManager->flush();

        $this->logger->info('Statut du devis mis à jour', [
            'quote_id' => $quote->getId(),
            'old_status' => $oldStatus,
            'new_status' => $status
        ]);
    }
}
