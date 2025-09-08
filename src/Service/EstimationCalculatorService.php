<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class EstimationCalculatorService
{
    public function __construct(
        private OpenAIService $openAIService,
        private LoggerInterface $logger
    ) {}

    /**
     * Point d'entrée principal pour générer une estimation
     */
    public function generateEstimation(array $formData, string $userType): array
    {
        try {
            $this->logger->info('Génération estimation', [
                'userType' => $userType,
                'dataKeys' => array_keys($formData)
            ]);

            // Validation des données
            $this->validateFormData($formData, $userType);

            // Génération avec OpenAI
            $aiEstimation = $this->openAIService->generateEstimation($formData, $userType);

            // Post-traitement et validation
            $processedEstimation = $this->processEstimation($aiEstimation, $formData, $userType);

            // Ajout de métadonnées
            $finalEstimation = $this->addMetadata($processedEstimation, $formData, $userType);

            $this->logger->info('Estimation générée avec succès', [
                'totalDays' => $finalEstimation['estimation']['totalDays'],
                'totalCost' => $finalEstimation['estimation']['totalCost']
            ]);

            return $finalEstimation;

        } catch (\Exception $e) {
            $this->logger->error('Erreur génération estimation', [
                'error' => $e->getMessage(),
                'userType' => $userType
            ]);

            // Fallback avec estimation basique
            return $this->generateFallbackEstimation($formData, $userType);
        }
    }

    /**
     * Validation des données du formulaire
     */
    private function validateFormData(array $data, string $userType): void
    {
        if (!in_array($userType, ['freelance', 'entreprise'])) {
            throw new \InvalidArgumentException('Type utilisateur invalide');
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('Données du formulaire manquantes');
        }

        // Validation spécifique selon le type
        if ($userType === 'freelance') {
            $this->validateFreelanceData($data);
        } else {
            $this->validateEnterpriseData($data);
        }
    }

    /**
     * Validation des données freelance
     */
    private function validateFreelanceData(array $data): void
    {
        if (!isset($data['basics']) || empty($data['basics']['projectType'])) {
            throw new \InvalidArgumentException('Type de projet manquant');
        }
    }

    /**
     * Validation des données entreprise
     */
    private function validateEnterpriseData(array $data): void
    {
        if (!isset($data['basics']) || empty($data['basics']['projectType'])) {
            throw new \InvalidArgumentException('Type de projet manquant');
        }
    }

    /**
     * Post-traitement de l'estimation OpenAI
     */
    private function processEstimation(array $aiEstimation, array $formData, string $userType): array
    {
        // Validation de la structure de réponse
        if (!isset($aiEstimation['estimation'])) {
            throw new \Exception('Structure d\'estimation invalide');
        }

        $estimation = $aiEstimation['estimation'];

        // Validation des valeurs numériques
        $estimation['totalDays'] = max(1, (int)($estimation['totalDays'] ?? 1));
        $estimation['totalCost'] = max(100, (float)($estimation['totalCost'] ?? 100));

        // Assurer la cohérence entre jours et coût
        if ($userType === 'freelance' && isset($formData['constraints']['tjmTarget'])) {
            $tjm = $formData['constraints']['tjmTarget'];
            $expectedCost = $estimation['totalDays'] * $tjm;
            
            // Si l'écart est trop important, ajuster
            if (abs($estimation['totalCost'] - $expectedCost) / $expectedCost > 0.3) {
                $estimation['totalCost'] = $expectedCost;
            }
        }

        // Appliquer la marge de sécurité si définie
        $margin = $this->getSecurityMargin($formData, $userType);
        if ($margin > 0) {
            $marginDays = round($estimation['totalDays'] * $margin / 100);
            $marginCost = round($estimation['totalCost'] * $margin / 100);
            
            $estimation['totalDays'] += $marginDays;
            $estimation['totalCost'] += $marginCost;
            
            // Mettre à jour le breakdown
            if (isset($estimation['breakdown']['margin'])) {
                $estimation['breakdown']['margin']['days'] = $marginDays;
                $estimation['breakdown']['margin']['cost'] = $marginCost;
                $estimation['breakdown']['margin']['description'] = "Marge de sécurité ({$margin}%)";
            }
        }

        return ['estimation' => $estimation];
    }

    /**
     * Récupère la marge de sécurité selon le type d'utilisateur
     */
    private function getSecurityMargin(array $formData, string $userType): int
    {
        if ($userType === 'freelance') {
            return (int)($formData['constraints']['securityMargin'] ?? 0);
        } else {
            return (int)($formData['pricing']['securityMargin'] ?? 0);
        }
    }

    /**
     * Ajoute des métadonnées à l'estimation
     */
    private function addMetadata(array $estimation, array $formData, string $userType): array
    {
        $estimation['metadata'] = [
            'userType' => $userType,
            'generatedAt' => date('c'),
            'version' => '1.0',
            'disclaimer' => 'Cette estimation est indicative et basée sur les informations fournies.'
        ];

        // Ajouter des conseils spécifiques
        $estimation['advice'] = $this->generateAdvice($estimation['estimation'], $formData, $userType);

        return $estimation;
    }

    /**
     * Génère des conseils personnalisés
     */
    private function generateAdvice(array $estimation, array $formData, string $userType): array
    {
        $advice = [];

        // Conseils basés sur la durée
        if ($estimation['totalDays'] > 100) {
            $advice[] = "Projet de grande envergure : considérez une approche par phases pour réduire les risques.";
        } elseif ($estimation['totalDays'] < 10) {
            $advice[] = "Projet court : assurez-vous que toutes les fonctionnalités sont bien définies.";
        }

        // Conseils spécifiques au type
        if ($userType === 'freelance') {
            if (isset($formData['objectives']['selectedObjectives']) && 
                in_array('portfolio', $formData['objectives']['selectedObjectives'])) {
                $advice[] = "Projet portfolio : documentez bien votre travail pour valoriser vos compétences.";
            }
        } else {
            if (isset($formData['functionalities']['scalability']) && 
                $formData['functionalities']['scalability'] === 'yes') {
                $advice[] = "Architecture scalable : prévoyez du temps supplémentaire pour les choix techniques.";
            }
        }

        return $advice;
    }

    /**
     * Estimation de fallback en cas d'erreur OpenAI
     */
    private function generateFallbackEstimation(array $formData, string $userType): array
    {
        $this->logger->warning('Utilisation estimation fallback');

        // Estimation basique selon le type de projet
        $baseDays = $this->getBaseDays($formData, $userType);
        $dailyRate = $this->getDailyRate($formData, $userType);
        
        $totalDays = $baseDays;
        $totalCost = $totalDays * $dailyRate;

        // Appliquer la marge
        $margin = $this->getSecurityMargin($formData, $userType);
        if ($margin > 0) {
            $totalDays = round($totalDays * (1 + $margin / 100));
            $totalCost = round($totalCost * (1 + $margin / 100));
        }

        return [
            'estimation' => [
                'totalDays' => $totalDays,
                'totalCost' => $totalCost,
                'confidence' => 'low',
                'breakdown' => [
                    'development' => [
                        'days' => round($totalDays * 0.7),
                        'cost' => round($totalCost * 0.7),
                        'description' => 'Développement principal'
                    ],
                    'testing' => [
                        'days' => round($totalDays * 0.2),
                        'cost' => round($totalCost * 0.2),
                        'description' => 'Tests et validation'
                    ],
                    'margin' => [
                        'days' => round($totalDays * 0.1),
                        'cost' => round($totalCost * 0.1),
                        'description' => 'Marge et imprévus'
                    ]
                ],
                'recommendations' => [
                    'Estimation générée automatiquement en mode dégradé',
                    'Contactez-nous pour une estimation plus précise'
                ],
                'risks' => [
                    'Estimation basique - précision limitée'
                ]
            ],
            'metadata' => [
                'userType' => $userType,
                'generatedAt' => date('c'),
                'version' => '1.0-fallback',
                'disclaimer' => 'Estimation de fallback - précision limitée.'
            ]
        ];
    }

    /**
     * Calcule les jours de base selon le projet
     */
    private function getBaseDays(array $formData, string $userType): int
    {
        // Logique basique selon le type de projet
        $projectType = $formData['basics']['projectType'] ?? '';
        
        $baseDays = match($projectType) {
            'site-vitrine' => 15,
            'e-commerce' => 45,
            'saas' => 90,
            'app-mobile' => 60,
            'api' => 30,
            default => 30
        };

        // Ajustements selon les fonctionnalités
        if ($userType === 'freelance' && isset($formData['features']['selectedFeatures'])) {
            $baseDays += count($formData['features']['selectedFeatures']) * 3;
        } elseif ($userType === 'entreprise' && isset($formData['functionalities']['selectedFeatures'])) {
            $baseDays += count($formData['functionalities']['selectedFeatures']) * 5;
        }

        return max(5, $baseDays);
    }

    /**
     * Récupère le taux journalier
     */
    private function getDailyRate(array $formData, string $userType): float
    {
        if ($userType === 'freelance' && 
            isset($formData['constraints']['hasTjmTarget']) && 
            $formData['constraints']['hasTjmTarget'] && 
            isset($formData['constraints']['tjmTarget'])) {
            return (float)$formData['constraints']['tjmTarget'];
        }

        // Taux par défaut
        return $userType === 'freelance' ? 500.0 : 600.0;
    }
}
