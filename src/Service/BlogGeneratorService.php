<?php

namespace App\Service;

use App\Entity\Blog;
use App\Service\OpenAIService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class BlogGeneratorService
{
    public function __construct(
        private OpenAIService $openAIService,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {}

    /**
     * Génère un article de blog avec OpenAI en conservant la structure exacte
     */
    public function generateBlogArticle(?string $topic = null): Blog
    {
        // Sujets prédéfinis si aucun sujet n'est fourni
        $defaultTopics = [
            "Aller au‑delà de l'horoscope : analyser ses dynamiques personnelles",
            "Fenêtres d’opportunités : comment les repérer et les utiliser au quotidien",
            "Méthode Stellara : scores, leviers et focus de la semaine",
            "Aspects clés du jour : agir quand l’énergie est favorable",
            "Mercure rétrograde sans panique : check‑list pragmatique",
            "Mars et productivité : canaliser l’énergie sans s’épuiser",
            "Vénus et relations pro : négocier et collaborer au bon moment",
            "La Lune et le rythme : organiser sa semaine avec les bons tempos",
            "Planifier ses objectifs trimestriels avec ses cycles personnels",
            "Lire sa météo intérieure : prendre de meilleures décisions au bon moment",
            "Compatibilité d’équipe : synchroniser réunions et sprints avec l’énergie du moment",
            "Rituel hebdo en 10 minutes : faire le point avec Stellara",
            "Limites de l’astrologie appliquée : ce qu’on mesure et ce qu’on ne promet pas",
            "Cas pratiques avant/après : le ROI personnel d’un meilleur timing",
            "Devenir antifragile : utiliser les périodes de tension pour progresser",
            "Top erreurs d’interprétation astrologique à éviter (et comment les corriger)"
        ];

        $selectedTopic = $topic ?: $defaultTopics[array_rand($defaultTopics)];

        // Prompt optimisé pour conserver la structure exacte de ChatGPT
        $prompt = $this->buildStructuredPrompt($selectedTopic);

        try {
            // Appel OpenAI avec des paramètres optimisés pour la génération de contenu
            $result = $this->openAIService->callOpenAI($prompt, [
                'model' => 'gpt-4o-mini',
                'temperature' => 0.3, // Plus strict pour la structure
                'max_tokens' => 3000, // Plus de tokens pour un article complet
                'response_format' => ['type' => 'json_object']
            ]);

            // Post-traitement : rejet si <div> ou <span> présents, ou remplacement automatique
            if (isset($result['contenu']) && preg_match('/<(div|span)[^>]*>/i', $result['contenu'])) {
                // Option stricte : rejet
                throw new \Exception('La réponse OpenAI contient des <div> ou <span>, prompt non respecté.');
                // Option tolérante :
                // $result['contenu'] = preg_replace('/<div[^>]*>/', '<h2>', $result['contenu']);
                // $result['contenu'] = str_replace('</div>', '</h2>', $result['contenu']);
            }

            // Validation de la réponse
            $this->validateResponse($result);

            // Création de l'entité Blog
            $article = $this->createBlogEntity($result);

            // Sauvegarde en base
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->logger->info('Article de blog généré avec succès', [
                'titre' => $article->getTitre(),
                'slug' => $article->getSlug()
            ]);

            return $article;
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la génération d\'article', [
                'topic' => $selectedTopic,
                'error' => $e->getMessage()
            ]);

            throw new \Exception('Impossible de générer l\'article : ' . $e->getMessage());
        }
    }

    /**
     * Construit un prompt structuré pour l'audience tech
     */
    private function buildStructuredPrompt(string $topic): string
    {
        return "🚨 INTERDICTION ABSOLUE d’utiliser <div> ou <span> ou toute balise autre que <h2>, <p>, <ul>, <ol> ! Si tu utilises une autre balise, la réponse sera rejetée. 🚨\n\n" .
            "UTILISE UNIQUEMENT CES BALISES HTML :\n" .
            "- <h2>Titre principal</h2> (5-6 sections dans l'article)\n" .
            "- <p>Paragraphe de texte avec <strong>mots-clés</strong></p>\n" .
            "- <ul><li>Point de liste</li></ul>\n" .
            "- <ol><li>Étape numérotée</li></ol>\n\n" .
            "❌ INTERDIT : <div>, <span>, ou tout autre balise\n" .
            "✅ OBLIGATOIRE : <h2>, <p>, <ul>, <ol> uniquement\n\n" .
            "EXEMPLE EXACT À REPRODUIRE :\n" .
            "<h2>Titre de section</h2>\n" .
            "<p>Paragraphe d'introduction avec <strong>mots-clés</strong>.</p>\n" .
            "<ul><li>Point important</li><li>Autre point</li></ul>\n" .
            "<p>Paragraphe de transition.</p>\n\n" .
            "---\n\n" .
            "Tu es un rédacteur spécialisé en astrologie appliquée et pédagogie. Rédige un article sur : \"$topic\".\n\n" .
            "AUDIENCE : Curieux exigeants, indépendants, entrepreneurs, managers et équipes produit — des personnes orientées décision qui veulent des repères concrets.\n" .
            "CONTEXTE : Stellara est une application d’astrologie analytique qui transforme l’activité astrologique pertinente et le thème natal en tableau de bord personnel (scores, tendances, fenêtres d’opportunités).\n\n" .
            "STRUCTURE : 5-6 sections avec <h2>\n" .
            "STYLE : Clair, pragmatique, non fataliste. Exemples concrets du quotidien pro/perso, conseils actionnables. Ajoute quelques emojis avec parcimonie. Invente des exemples avant/après (avec chiffres, contexte) pour illustrer les points clés quand c’est utile.\n\n" .
            "RÈGLE ABSOLUE : Utilise UNIQUEMENT <h2>, <p>, <ul>, <ol>. JAMAIS de <div> ni <span>.\n\n" .
            "Écris un article de 800-1400 mots avec 5-6 sections <h2>. Intègre naturellement les notions : astrologie appliquée, analyse comportementale, aspects, cycles personnels, opportunités, méthode, limites.\nÀ la fin, ajoute un call-to-action pour découvrir Stellara ou un conseil actionnable pour lire sa ‘météo intérieure’.\n\n" .
            "RÉPONSE JSON EXACTE :\n" .
            "{\n    \"titre\": \"Titre SEO optimisé (50-60 caractères)\",\n    \"slug\": \"slug-seo-automatique\",\n    \"contenu\": \"HTML complet avec structure préservée\",\n    \"auteur\": \"Jérémie N.\",\n    \"metaDescription\": \"Description SEO 150-160 caractères\",\n    \"motsCles\": [\"astrologie appliquée\", \"analyse comportementale\", \"opportunités personnelles\"]\n}\n\n" .
            "IMPÉRATIF : L'article doit traiter des décisions concrètes (timing, énergie/temps, qualité d’exécution, ROI personnel) avec des exemples crédibles. Préserve exactement le formatage HTML généré.";
    }

    /**
     * Valide la réponse OpenAI
     */
    private function validateResponse(array $result): void
    {
        $requiredFields = ['titre', 'slug', 'contenu', 'auteur'];

        foreach ($requiredFields as $field) {
            if (!isset($result[$field]) || empty($result[$field])) {
                throw new \Exception("Champ manquant ou vide : $field");
            }
        }

        // Validation du slug (format URL)
        if (!preg_match('/^[a-z0-9-]+$/', $result['slug'])) {
            throw new \Exception("Format de slug invalide");
        }

        // Validation du contenu HTML
        if (strlen($result['contenu']) < 500) {
            throw new \Exception("Contenu trop court");
        }

        // Validation stricte : aucune balise <div> ou <span> ne doit être présente
        if (preg_match('/<(div|span)[^>]*>/i', $result['contenu'])) {
            throw new \Exception('Le contenu généré contient des <div> ou <span>, prompt non respecté.');
        }
    }

    /**
     * Crée l'entité Blog à partir de la réponse OpenAI
     */
    private function createBlogEntity(array $result): Blog
    {
        $article = new Blog();

        $article->setTitre($result['titre']);
        $article->setSlug($this->ensureUniqueSlug($result['slug']));
        $article->setContenu($result['contenu']);
        $article->setAuteur($result['auteur']);
        $article->setPublished(true); // Publié par défaut
        $article->setCreatedAt(new \DateTimeImmutable('now'));
        $article->setUpdatedAt(null); // Null à la création, sera défini lors des modifications

        return $article;
    }

    /**
     * Assure l'unicité du slug
     */
    private function ensureUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Vérifie si un slug existe déjà
     */
    private function slugExists(string $slug): bool
    {
        return $this->entityManager->getRepository(Blog::class)
            ->findOneBy(['slug' => $slug]) !== null;
    }
}
