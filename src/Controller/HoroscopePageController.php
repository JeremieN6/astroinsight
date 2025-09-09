<?php
namespace App\Controller;

use App\Repository\AstroProfileRepository;
use App\Repository\DailyHoroscopeRepository;
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
    public function today(AstroProfileRepository $profiles, DailyHoroscopeRepository $dailyRepo, HoroscopeGeneratorInterface $gen, TransitKeyService $transitKey, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();
        $date = new DateTimeImmutable('today UTC');
    $entry = null; $fromCache = false;
    try { $entry = $dailyRepo->findOneForUserDate($user, $date); } catch (\Throwable $e) {}
        $profile = $profiles->findOneByUser($user);
        $data = null;
    if ($entry) { $data = [
        'scores' => $entry->getScores(),
        'summary' => $entry->getSummary(),
        'insights' => $entry->getInsights(),
        'aspects' => ($entry->getRawData()['aspects'] ?? []),
        'nextTransit' => $entry->getNextTransit(),
        'is_final' => $entry->isFinal()
        ]; $fromCache = true; }
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
