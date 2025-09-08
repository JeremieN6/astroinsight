<?php
namespace App\Controller;

use App\Repository\AstroProfileRepository;
use App\Repository\HoroscopeCacheRepository;
use App\Service\Astro\HoroscopeGeneratorInterface;
use App\Service\Astro\TransitKeyService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/mon-espace/horoscope')]
class HoroscopePageController extends AbstractController
{
    #[Route('', name: 'user_horoscope_today')]
    public function today(AstroProfileRepository $profiles, HoroscopeCacheRepository $cacheRepo, HoroscopeGeneratorInterface $gen, TransitKeyService $transitKey, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();
        $date = new DateTimeImmutable('today UTC');
        $cache = null; $fromCache = false;
        try { $cache = $cacheRepo->findForUserAndDate($user, $date, 'daily'); } catch (\Throwable $e) {}
        $profile = $profiles->findOneByUser($user);
        $data = null;
        if ($cache) { $data = [ 'scores' => $cache->getScores(), 'summary' => $cache->getSummary(), 'aspects' => $cache->getAspects(), 'is_final' => $cache->isFinal()]; $fromCache = true; }
        elseif ($profile) {
            try { $genData = $gen->generate($profile, $date); $data = $genData; } catch (\Throwable $e) { $data = null; }
        }
        $nextTransit = $profile ? $transitKey->nextKeyTransit($profile) : null;
        return $this->render('astro/horoscope_today.html.twig', [
            'data' => $data,
            'from_cache' => $fromCache,
            'profile' => $profile,
            'date' => $date,
            'nextTransit' => $nextTransit,
        ]);
    }
}
