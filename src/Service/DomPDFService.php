<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use App\Entity\Quote;

class DomPDFService
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Génère un PDF pour un devis (Quote entity)
     * Retourne le binaire PDF
     */
    public function generateQuotePdf(Quote $quote): string
    {
        $template = 'quote/pdf.html.twig';

        // Préparer les données minimales pour le template
        $data = [
            'quote' => $quote,
            'client' => $quote->getClient(),
            'generatedAt' => new \DateTime(),
        ];

        $html = $this->twig->render($template, $data);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Génère un PDF d'estimation freelance
     */
    public function generateFreelancePDF(array $estimationData): string
    {
        // Détermine le template selon le type de freelance
        $freelanceType = $estimationData['formData']['pricing']['type'] ??
                        $estimationData['formData']['constraints']['freelanceType'] ?? 'forfait';
        
        if ($freelanceType === 'regie') {
            $template = 'pdf/freelance_regie.html.twig';
        } else {
            $template = 'pdf/freelance_forfait.html.twig';
        }

        // Prépare les données pour le template
        $templateData = $this->prepareFreelanceData($estimationData);

        // Génère le HTML
        $html = $this->twig->render($template, $templateData);

        // Configure DomPDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Génère un PDF d'estimation entreprise
     */
    public function generateEnterprisePDF(array $estimationData): string
    {
        $template = 'pdf/entreprise_devis.html.twig';

        // Prépare les données pour le template
        $templateData = $this->prepareEnterpriseData($estimationData);

        // Génère le HTML
        $html = $this->twig->render($template, $templateData);

        // Configure DomPDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Prépare les données pour le template freelance
     */
    private function prepareFreelanceData(array $estimationData): array
    {
        $formData = $estimationData['formData'] ?? [];
        $estimation = $estimationData['estimation'] ?? [];
        
        $freelanceType = $formData['pricing']['type'] ??
                        $formData['constraints']['freelanceType'] ?? 'forfait';
        
        // Traduction des phases si breakdown existe
        $translatedBreakdown = null;
        if (isset($estimation['breakdown'])) {
            $translatedBreakdown = $this->translatePhases($estimation['breakdown']);
        }

        return [
            'title' => 'Récapitulatif d\'Estimation',
            'freelanceType' => $freelanceType,
            'generatedAt' => new \DateTime(),
            'formData' => $formData, // Ajout de formData pour les templates
            'project' => [
                'type' => $this->getProjectTypeLabel($formData['basics'] ?? []),
                'description' => $formData['basics']['description'] ?? '',
                'technologies' => $this->formatTechnologies($formData['basics']['technologies'] ?? []),
            ],
            'client' => $this->getClientInfo($formData),
            'constraints' => $formData['constraints'] ?? [],
            'features' => $formData['features']['selectedFeatures'] ?? [],
            'objectives' => $this->translateObjectives($formData['objectives']['selectedObjectives'] ?? []),
            'estimation' => array_merge($estimation, [
                'breakdown' => $translatedBreakdown
            ]),
            'breakdown' => $translatedBreakdown,
            'recommendations' => $estimation['recommendations'] ?? [],
            'risks' => $estimation['risks'] ?? [],
            'metadata' => $estimationData['metadata'] ?? [],
            'charts' => $translatedBreakdown ? $this->prepareChartData($translatedBreakdown) : null,
            'indicators' => $this->prepareIndicators($estimation, $formData),
            'comparisons' => $this->prepareComparisonData($estimation),
            'chartImages' => $estimationData['chartImages'] ?? null,
        ];
    }

    /**
     * Prépare les données pour le template entreprise
     */
    private function prepareEnterpriseData(array $estimationData): array
    {
        $formData = $estimationData['formData'] ?? [];
        $estimation = $estimationData['estimation'] ?? [];
        $metadata = $estimationData['metadata'] ?? [];

        // Adaptation des données entreprise vers le format template
        $technologies = '';
        if (isset($formData['basics']['technologies'])) {
            $tech = $formData['basics']['technologies'];
            $techParts = [];
            if (!empty($tech['frontend'])) $techParts[] = $tech['frontend'];
            if (!empty($tech['backend'])) $techParts[] = $tech['backend'];
            if (!empty($tech['database'])) $techParts[] = $tech['database'];
            if (!empty($tech['infrastructure'])) $techParts[] = $tech['infrastructure'];
            $technologies = implode(', ', $techParts);
        }

        return [
            'title' => 'Récapitulatif d\'Estimation',
            'generatedAt' => $metadata['generatedAt'] ?? new \DateTime(),
            'client' => [
                'companyName' => $formData['pricing']['companyName'] ?? '',
                'contactName' => $formData['pricing']['contactName'] ?? '',
                'contactEmail' => $formData['pricing']['contactEmail'] ?? '',
                'contactPhone' => $formData['pricing']['contactPhone'] ?? '',
                'projectName' => $formData['basics']['projectName'] ?? '',
                'projectDescription' => $formData['basics']['projectDescription'] ?? '',
                'sector' => $formData['pricing']['sector'] ?? '',
            ],
            'project' => [
                'type' => $this->getProjectTypeLabel($formData['basics'] ?? []),
                'description' => $formData['basics']['projectDescription'] ?? '',
                'technologies' => $technologies,
            ],
            'estimation' => array_merge($estimation, [
                'breakdown' => isset($estimation['breakdown']) ? $this->translatePhases($estimation['breakdown']) : null
            ]),
            'charts' => isset($estimation['breakdown']) ? $this->prepareChartData($this->translatePhases($estimation['breakdown'])) : null,
            'indicators' => $this->prepareIndicators($estimation, $formData),
            'comparisons' => $this->prepareComparisonData($estimation),
            'chartImages' => $estimationData['chartImages'] ?? null,
            'features' => $formData['functionalities']['selectedFeatures'] ?? [],
            'recommendations' => $estimation['recommendations'] ?? [],
            'risks' => $estimation['risks'] ?? [],
            'objectives' => $this->translateObjectives($formData['objectives']['selectedObjectives'] ?? []),
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
        
        // Valeurs par défaut pour éviter les erreurs de template
        return [
            'projectName' => '',
            'clientName' => '',
            'companyName' => '',
            'contactEmail' => '',
            'description' => '',
            'type' => '',
            'budget' => '',
            'competition' => '',
            'validity' => 30,
            'paymentTerms' => '',
            'warranty' => 3,
        ];
    }

    /**
     * Obtient le libellé du type de projet avec gestion du type personnalisé
     */
    private function getProjectTypeLabel(array $basics): string
    {
        $projectType = $basics['projectType'] ?? '';
        $customProjectType = $basics['customProjectType'] ?? '';

        $projectTypeLabels = [
            'site-vitrine' => 'Site vitrine',
            'saas' => 'SaaS',
            'e-commerce' => 'E-commerce',
            'api' => 'API',
            'app-mobile' => 'App mobile',
            'dashboard' => 'Dashboard',
            'portail-b2b' => 'Portail B2B',
            'back-office' => 'Back-office',
            'intranet' => 'Intranet / Portail interne'
        ];

        if ($projectType === 'autre' && !empty($customProjectType)) {
            return "Autre ({$customProjectType})";
        } elseif ($projectType === 'autre') {
            return 'Autre';
        }

        return $projectTypeLabels[$projectType] ?? ($projectType ?: 'Non spécifié');
    }

    /**
     * Traduit les objectifs de leurs IDs vers les libellés français
     */
    private function translateObjectives(array $objectiveIds): array
    {
        $objectiveLabels = [
            'profitability' => 'Rentabilité maximale',
            'portfolio' => 'Projet portfolio',
            'learning' => 'Progression technique',
            'strategic-client' => 'Nouveau client stratégique',
            'fill-gap' => 'Combler un trou dans le planning',
            'positioning' => 'Nouveau positionnement',
            'income' => 'Complément de revenu',
            'other' => 'Autre raison'
        ];

        return array_map(function($id) use ($objectiveLabels) {
            return $objectiveLabels[$id] ?? $id;
        }, $objectiveIds);
    }

    /**
     * Traduit les phases du breakdown de l'anglais vers le français
     */
    private function translatePhases(array $breakdown): array
    {
        $phaseTranslations = [
            'analysis' => 'Analyse',
            'analyse' => 'Analyse',
            'conception' => 'Conception',
            'design' => 'Conception',
            'development' => 'Développement',
            'integration' => 'Intégration',
            'testing' => 'Tests',
            'deployment' => 'Déploiement',
            'formation' => 'Formation',
            'maintenance' => 'Maintenance'
        ];

        $translatedBreakdown = [];
        foreach ($breakdown as $phase => $details) {
            $translatedPhase = $phaseTranslations[strtolower($phase)] ?? ucfirst($phase);
            $translatedBreakdown[$translatedPhase] = $details;
        }

        return $translatedBreakdown;
    }

    /**
     * Prépare les données pour les graphiques (pie chart et bar chart)
     */
    private function prepareChartData(array $breakdown): array
    {
        if (empty($breakdown)) {
            return ['pie' => [], 'bars' => []];
        }

        $colors = [
            '#667eea', // Bleu principal QuickEsti
            '#764ba2', // Violet
            '#f093fb', // Rose
            '#f5576c', // Rouge
            '#4facfe', // Bleu clair
            '#43e97b'  // Vert
        ];

        $totalCost = array_sum(array_column($breakdown, 'cost'));
        $maxDays = max(array_column($breakdown, 'days'));

        $pieData = [];
        $barData = [];
        $colorIndex = 0;

        foreach ($breakdown as $phase => $details) {
            $percentage = $totalCost > 0 ? round(($details['cost'] / $totalCost) * 100, 1) : 0;
            $barWidth = $maxDays > 0 ? round(($details['days'] / $maxDays) * 100, 1) : 0;

            $color = $colors[$colorIndex % count($colors)];

            $pieData[] = [
                'label' => $phase,
                'value' => $details['cost'],
                'percentage' => $percentage,
                'color' => $color,
                'progressBar' => $this->generateProgressBar($percentage)
            ];

            $barData[] = [
                'label' => $phase,
                'days' => $details['days'],
                'width' => $barWidth,
                'color' => $color,
                'progressBar' => $this->generateProgressBar($barWidth)
            ];

            $colorIndex++;
        }

        return [
            'pie' => $pieData,
            'bars' => $barData,
            'gantt' => $this->prepareGanttData($breakdown),
            'totalCost' => $totalCost,
            'maxDays' => $maxDays
        ];
    }

    /**
     * Prépare les données pour le diagramme de Gantt
     */
    private function prepareGanttData(array $breakdown): array
    {
        if (empty($breakdown)) {
            return ['phases' => [], 'totalWeeks' => 0, 'weeks' => []];
        }

        $colors = [
            '#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#43e97b'
        ];

        $totalDays = array_sum(array_column($breakdown, 'days'));
        $totalWeeks = max(1, ceil($totalDays / 5));

        $phases = [];
        $currentWeek = 0;
        $colorIndex = 0;

        foreach ($breakdown as $phase => $details) {
            $phaseDays = $details['days'];
            $phaseWeeks = ceil($phaseDays / 5);
            $startWeek = $currentWeek;
            $endWeek = $currentWeek + $phaseWeeks;

            // Calcul de la position et largeur en pourcentage
            $startPercent = ($startWeek / $totalWeeks) * 100;
            $widthPercent = ($phaseWeeks / $totalWeeks) * 100;

            $phases[] = [
                'name' => $phase,
                'days' => $phaseDays,
                'weeks' => $phaseWeeks,
                'startWeek' => $startWeek + 1,
                'endWeek' => $endWeek,
                'startPercent' => $startPercent,
                'widthPercent' => $widthPercent,
                'color' => $colors[$colorIndex % count($colors)],
                'description' => $details['description'] ?? ''
            ];

            $currentWeek += $phaseWeeks;
            $colorIndex++;
        }

        // Génération des semaines pour l'en-tête
        $weeks = [];
        for ($i = 1; $i <= $totalWeeks; $i++) {
            $weeks[] = "S{$i}";
        }

        return [
            'phases' => $phases,
            'totalWeeks' => $totalWeeks,
            'weeks' => $weeks,
            'totalDays' => $totalDays
        ];
    }

    /**
     * Prépare les indicateurs visuels (confiance, badges, progression)
     */
    private function prepareIndicators(array $estimation, array $formData): array
    {
        $confidence = $estimation['confidence'] ?? 'medium';

        // Configuration de la jauge de confiance
        $confidenceConfig = [
            'high' => [
                'label' => 'Confiance Élevée',
                'class' => 'confidence-high',
                'icon' => '✓',
                'description' => 'Estimation fiable basée sur des données précises'
            ],
            'medium' => [
                'label' => 'Confiance Modérée',
                'class' => 'confidence-medium',
                'icon' => '~',
                'description' => 'Estimation probable avec quelques incertitudes'
            ],
            'low' => [
                'label' => 'Confiance Faible',
                'class' => 'confidence-low',
                'icon' => '!',
                'description' => 'Estimation approximative nécessitant plus d\'analyse'
            ]
        ];

        // Badges pour recommandations
        $recommendationBadges = [];
        foreach ($estimation['recommendations'] ?? [] as $recommendation) {
            $recommendationBadges[] = [
                'text' => $recommendation,
                'type' => $this->getRecommendationBadgeType($recommendation),
                'class' => $this->getRecommendationBadgeClass($recommendation)
            ];
        }

        // Badges pour risques
        $riskBadges = [];
        foreach ($estimation['risks'] ?? [] as $risk) {
            $riskBadges[] = [
                'text' => $risk,
                'type' => $this->getRiskBadgeType($risk),
                'class' => $this->getRiskBadgeClass($risk)
            ];
        }

        return [
            'confidence' => $confidenceConfig[$confidence] ?? $confidenceConfig['medium'],
            'recommendationBadges' => $recommendationBadges,
            'riskBadges' => $riskBadges
        ];
    }

    private function getRecommendationBadgeType(string $recommendation): string
    {
        $recommendation = strtolower($recommendation);
        if (strpos($recommendation, 'sécurité') !== false || strpos($recommendation, 'backup') !== false) {
            return 'Sécurité';
        }
        if (strpos($recommendation, 'performance') !== false || strpos($recommendation, 'optimisation') !== false) {
            return 'Performance';
        }
        if (strpos($recommendation, 'architecture') !== false || strpos($recommendation, 'microservice') !== false) {
            return 'Architecture';
        }
        return 'Conseil';
    }

    private function getRecommendationBadgeClass(string $recommendation): string
    {
        $type = $this->getRecommendationBadgeType($recommendation);
        return match($type) {
            'Sécurité' => 'badge-danger',
            'Performance' => 'badge-warning',
            'Architecture' => 'badge-info',
            default => 'badge-success'
        };
    }

    private function getRiskBadgeType(string $risk): string
    {
        $risk = strtolower($risk);
        if (strpos($risk, 'critique') !== false || strpos($risk, 'bloquant') !== false) {
            return 'Critique';
        }
        if (strpos($risk, 'délai') !== false || strpos($risk, 'planning') !== false) {
            return 'Planning';
        }
        if (strpos($risk, 'technique') !== false || strpos($risk, 'complexité') !== false) {
            return 'Technique';
        }
        return 'Attention';
    }

    private function getRiskBadgeClass(string $risk): string
    {
        $type = $this->getRiskBadgeType($risk);
        return match($type) {
            'Critique' => 'badge-danger',
            'Planning' => 'badge-warning',
            'Technique' => 'badge-info',
            default => 'badge-warning'
        };
    }

    /**
     * Prépare les données de comparaison (donut HT/TVA, efficacité, métriques)
     */
    private function prepareComparisonData(array $estimation): array
    {
        $totalCost = $estimation['totalCost'] ?? 0;
        $totalDays = $estimation['totalDays'] ?? 1;
        $breakdown = $estimation['breakdown'] ?? [];

        // Donut chart HT/TVA/TTC
        $htAmount = $totalCost;
        $tvaAmount = $totalCost * 0.2;
        $ttcAmount = $totalCost * 1.2;

        $htPercent = 83.33; // 100/1.2
        $tvaPercent = 16.67; // 20/1.2

        $donutData = [
            'ht' => [
                'amount' => $htAmount,
                'percent' => $htPercent,
                'color' => '#667eea',
                'progressBar' => $this->generateProgressBar($htPercent)
            ],
            'tva' => [
                'amount' => $tvaAmount,
                'percent' => $tvaPercent,
                'color' => '#f59e0b',
                'progressBar' => $this->generateProgressBar($tvaPercent)
            ],
            'ttc' => [
                'amount' => $ttcAmount
            ]
        ];

        // Efficacité par phase (coût par jour)
        $efficiencyData = [];
        $maxEfficiency = 0;

        if (!empty($breakdown)) {
            foreach ($breakdown as $phase => $details) {
                $days = $details['days'] ?? 1;
                $cost = $details['cost'] ?? 0;
                $efficiency = $days > 0 ? $cost / $days : 0;

                $efficiencyData[] = [
                    'phase' => $phase,
                    'efficiency' => $efficiency,
                    'days' => $days,
                    'cost' => $cost
                ];

                $maxEfficiency = max($maxEfficiency, $efficiency);
            }

            // Calcul des pourcentages pour les barres
            foreach ($efficiencyData as &$item) {
                $item['percent'] = $maxEfficiency > 0 ? ($item['efficiency'] / $maxEfficiency) * 100 : 0;
                $item['progressBar'] = $this->generateProgressBar($item['percent']);
            }
        }

        // Métriques de performance
        $avgCostPerDay = $totalDays > 0 ? $totalCost / $totalDays : 0;
        $projectWeeks = ceil($totalDays / 5);
        $avgCostPerWeek = $projectWeeks > 0 ? $totalCost / $projectWeeks : 0;

        $metrics = [
            [
                'value' => number_format($avgCostPerDay, 0, ',', ' ') . '€',
                'label' => 'Coût moyen / jour',
                'trend' => 'Optimisé'
            ],
            [
                'value' => $projectWeeks . ' sem',
                'label' => 'Durée totale',
                'trend' => 'Planifié'
            ],
            [
                'value' => number_format($avgCostPerWeek, 0, ',', ' ') . '€',
                'label' => 'Coût moyen / semaine',
                'trend' => 'Contrôlé'
            ]
        ];

        return [
            'donut' => $donutData,
            'efficiency' => $efficiencyData,
            'metrics' => $metrics,
            'maxEfficiency' => $maxEfficiency
        ];
    }

    /**
     * Génère une barre de progression avec des caractères Unicode
     */
    private function generateProgressBar(float $percentage, int $maxLength = 20): string
    {
        $filledLength = (int) round(($percentage / 100) * $maxLength);
        $emptyLength = $maxLength - $filledLength;

        return str_repeat('█', $filledLength) . str_repeat('░', $emptyLength);
    }

    /**
     * Formate les technologies en string pour l'affichage
     */
    private function formatTechnologies($technologies): string
    {
        if (is_string($technologies)) {
            return $technologies;
        }

        if (is_array($technologies)) {
            $formatted = [];
            foreach ($technologies as $key => $value) {
                if (is_string($value) && !empty($value)) {
                    $formatted[] = ucfirst($key) . ': ' . $value;
                }
            }
            return implode(', ', $formatted);
        }

        return '';
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
    public function generateFilename(array $formData): string
    {
        $projectType = $formData['basics']['projectType'] ?? 'projet';
        $date = (new \DateTime())->format('Y-m-d');

        // Nettoie le nom du type de projet
        $cleanProjectType = preg_replace('/[^a-zA-Z0-9-_]/', '-', $projectType);

        // Format: estimation-[type]-quickesti-[date]
        return sprintf('estimation-%s-quickesti-%s.pdf', $cleanProjectType, $date);
    }
}
