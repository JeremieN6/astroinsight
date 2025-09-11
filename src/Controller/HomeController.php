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
            'page_title' => 'AstroInsight – Astrologie de précision',
            'meta_description' => 'Horoscope quotidien personnalisé, aspects clés et scores d’énergie pour mieux décider.',
            'plans' => $plans,
        ]);
    }

    #[Route('/estimation', name: 'app_estimation')]
    public function estimation(): Response
    {
        // La sécurité est gérée par security.yaml (ROLE_USER requis)
        // Pas besoin de vérification manuelle ici
        return $this->render('estimation/index.html.twig', [
            'page_title' => 'AstroInsight - Générateur d\'horoscope quotidien et d\'analyse approfondies',
            'meta_description' => 'Application qui se distingue par ses fonctionnalités d\'analyse comportementale et de prédiction des opportunités personnelles.',
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
    $estimationsCount = $quoteRepository->countEstimationsByUser($user);
    $downloadsCount = $quoteRepository->sumDownloadsByUser($user);

        return $this->render('main/dashboard.html.twig', [
            'page_title' => 'Tableau de bord - AstroInsight',
            'meta_description' => 'Paramétrez votre profil astrologique et accédez à votre tableau de bord.',
            'user' => $user,
            'profileForm' => $profileForm->createView(),
            'counters' => [
                'estimations' => $estimationsCount,
                'downloads' => $downloadsCount,
            ],
        ]);
    }

    #[Route('/politique-de-confidentialité', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('components/privacy-policy.html.twig', [
            'page_title' => 'AstroInsight - Générateur d\'horoscope quotidien et d\'analyse approfondies',
            'meta_description' => 'Application qui se distingue par ses fonctionnalités d\'analyse comportementale et de prédiction des opportunités personnelles.',
        ]);
    }
}
