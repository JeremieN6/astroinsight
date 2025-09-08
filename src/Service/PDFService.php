<?php

namespace App\Service;

use Knp\Snappy\Pdf;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

class PDFService
{
    private Pdf $pdf;
    private Environment $twig;

    public function __construct(Pdf $pdf, Environment $twig)
    {
        $this->pdf = $pdf;
        $this->twig = $twig;
    }

    /**
     * Génère un PDF d'estimation freelance
     */
    public function generateFreelancePDF(array $estimationData): string
    {
        // Détermine le template selon le type de freelance
        $freelanceType = $estimationData['formData']['constraints']['freelanceType'] ?? 'forfait';
        
        if ($freelanceType === 'regie') {
            $template = 'pdf/freelance_regie.html.twig';
        } else {
            $template = 'pdf/freelance_forfait.html.twig';
        }

        // Prépare les données pour le template
        $templateData = $this->prepareFreelanceData($estimationData);

        // Génère le HTML
        $html = $this->twig->render($template, $templateData);

        // Configure les options PDF
        $options = [
            'page-size' => 'A4',
            'margin-top' => '15mm',
            'margin-right' => '15mm',
            'margin-bottom' => '15mm',
            'margin-left' => '15mm',
            'encoding' => 'UTF-8',
            'enable-local-file-access' => true,
            'no-outline' => true,
        ];

        // Génère le PDF
        return $this->pdf->getOutputFromHtml($html, $options);
    }

    /**
     * Génère un PDF d'estimation entreprise
     */
    public function generateEnterprisePDF(array $estimationData): string
    {
        $template = 'pdf/enterprise.html.twig';
        
        // Prépare les données pour le template
        $templateData = $this->prepareEnterpriseData($estimationData);

        // Génère le HTML
        $html = $this->twig->render($template, $templateData);

        // Configure les options PDF
        $options = [
            'page-size' => 'A4',
            'margin-top' => '15mm',
            'margin-right' => '15mm',
            'margin-bottom' => '15mm',
            'margin-left' => '15mm',
            'encoding' => 'UTF-8',
            'enable-local-file-access' => true,
            'no-outline' => true,
        ];

        // Génère le PDF
        return $this->pdf->getOutputFromHtml($html, $options);
    }

    /**
     * Prépare les données pour le template freelance
     */
    private function prepareFreelanceData(array $estimationData): array
    {
        $formData = $estimationData['formData'] ?? [];
        $estimation = $estimationData['estimation'] ?? [];
        
        $freelanceType = $formData['constraints']['freelanceType'] ?? 'forfait';
        
        return [
            'title' => $freelanceType === 'regie' ? 'Devis Commercial' : 'Estimation de Coûts',
            'freelanceType' => $freelanceType,
            'generatedAt' => new \DateTime(),
            'project' => [
                'type' => $formData['basics']['projectType'] ?? 'Non spécifié',
                'description' => $formData['basics']['description'] ?? '',
                'technologies' => $formData['basics']['technologies'] ?? '',
            ],
            'client' => $this->getClientInfo($formData),
            'constraints' => $formData['constraints'] ?? [],
            'features' => $formData['features']['selectedFeatures'] ?? [],
            'objectives' => $formData['objectives']['selectedObjectives'] ?? [],
            'estimation' => $estimation,
            'breakdown' => $estimation['breakdown'] ?? [],
            'recommendations' => $estimation['recommendations'] ?? [],
            'risks' => $estimation['risks'] ?? [],
            'metadata' => $estimationData['metadata'] ?? [],
        ];
    }

    /**
     * Prépare les données pour le template entreprise
     */
    private function prepareEnterpriseData(array $estimationData): array
    {
        $formData = $estimationData['formData'] ?? [];
        $estimation = $estimationData['estimation'] ?? [];
        
        return [
            'title' => 'Estimation Budgétaire Projet',
            'generatedAt' => new \DateTime(),
            'project' => [
                'type' => $formData['basics']['projectType'] ?? 'Non spécifié',
                'description' => $formData['basics']['description'] ?? '',
                'technologies' => $formData['basics']['technologies'] ?? '',
            ],
            'structure' => $formData['structure'] ?? [],
            'functionalities' => $formData['functionalities'] ?? [],
            'deliverables' => $formData['deliverables'] ?? [],
            'objectives' => $formData['objectives'] ?? [],
            'estimation' => $estimation,
            'breakdown' => $estimation['breakdown'] ?? [],
            'recommendations' => $estimation['recommendations'] ?? [],
            'risks' => $estimation['risks'] ?? [],
            'metadata' => $estimationData['metadata'] ?? [],
        ];
    }

    /**
     * Extrait les informations client selon le mode
     */
    private function getClientInfo(array $formData): array
    {
        $freelanceType = $formData['constraints']['freelanceType'] ?? 'forfait';
        
        if ($freelanceType === 'regie' && isset($formData['clientInfo'])) {
            return [
                'projectName' => $formData['clientInfo']['projectName'] ?? '',
                'clientName' => $formData['clientInfo']['clientName'] ?? '',
                'companyName' => $formData['clientInfo']['companyName'] ?? '',
                'contactEmail' => $formData['clientInfo']['contactEmail'] ?? '',
                'description' => $formData['clientInfo']['projectDescription'] ?? '',
                'type' => $this->getClientTypeLabel($formData['clientInfo']['clientType'] ?? ''),
                'budget' => $this->getBudgetRangeLabel($formData['clientInfo']['clientBudgetRange'] ?? ''),
                'competition' => $this->getCompetitiveContextLabel($formData['clientInfo']['competitiveContext'] ?? ''),
                'validity' => $formData['clientInfo']['validityDays'] ?? 30,
                'paymentTerms' => $this->getPaymentTermsLabel($formData['clientInfo']['paymentTerms'] ?? ''),
                'warranty' => $formData['clientInfo']['warranty'] ?? 3,
            ];
        }
        
        return [];
    }

    /**
     * Labels pour les types de client
     */
    private function getClientTypeLabel(string $value): string
    {
        $labels = [
            'startup' => 'Startup / Jeune entreprise',
            'pme' => 'PME (10-250 salariés)',
            'grande-entreprise' => 'Grande entreprise (250+ salariés)',
            'association' => 'Association / ONG',
            'particulier' => 'Particulier'
        ];
        return $labels[$value] ?? $value;
    }

    /**
     * Labels pour les gammes de budget
     */
    private function getBudgetRangeLabel(string $value): string
    {
        $labels = [
            'low' => '< 5 000€',
            'medium' => '5 000€ - 15 000€',
            'high' => '15 000€ - 50 000€',
            'enterprise' => '50 000€+'
        ];
        return $labels[$value] ?? 'Budget non communiqué';
    }

    /**
     * Labels pour le contexte concurrentiel
     */
    private function getCompetitiveContextLabel(string $value): string
    {
        $labels = [
            'low' => 'Peu de concurrence',
            'medium' => 'Concurrence modérée',
            'high' => 'Forte concurrence'
        ];
        return $labels[$value] ?? $value;
    }

    /**
     * Labels pour les conditions de paiement
     */
    private function getPaymentTermsLabel(string $value): string
    {
        $labels = [
            '30-70' => '30% acompte, 70% livraison',
            '50-50' => '50% acompte, 50% livraison',
            '33-33-34' => '3 fois (33% - 33% - 34%)',
            'monthly' => 'Paiement mensuel',
            'custom' => 'Personnalisé'
        ];
        return $labels[$value] ?? $value;
    }

    /**
     * Génère un nom de fichier pour le PDF
     */
    public function generateFilename(string $userType, array $formData): string
    {
        $projectType = $formData['basics']['projectType'] ?? 'projet';
        $date = (new \DateTime())->format('Y-m-d');
        
        if ($userType === 'freelance') {
            $freelanceType = $formData['constraints']['freelanceType'] ?? 'forfait';
            $prefix = $freelanceType === 'regie' ? 'devis' : 'estimation';
        } else {
            $prefix = 'estimation-entreprise';
        }
        
        // Nettoie le nom de fichier
        $cleanProjectType = preg_replace('/[^a-zA-Z0-9-_]/', '-', $projectType);
        
        return sprintf('%s-%s-%s.pdf', $prefix, $cleanProjectType, $date);
    }
}
