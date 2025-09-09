<?php
namespace App\Controller\Api;

use App\Repository\DailyHoroscopeRepository;
use App\Repository\AstroProfileRepository;
use App\Service\Astro\HoroscopeGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/horoscope')] 
class HoroscopeController extends AbstractController
{
    public function __construct(
    private DailyHoroscopeRepository $dailyRepo,
        private AstroProfileRepository $profileRepo,
        private HoroscopeGeneratorInterface $generator,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/today', name: 'api_horoscope_today', methods: ['GET'])]
    public function today(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();
        $date = new DateTimeImmutable('today UTC');
        $entry = $this->dailyRepo->findOneForUserDate($user, $date);
        if (!$entry) {
            $profile = $this->profileRepo->findOneByUser($user);
            if (!$profile) {
                return $this->json(['error' => 'astro_profile_missing'], 400);
            }
            $data = $this->generator->generate($profile, $date);
            $entry = new \App\Entity\DailyHoroscope();
            $entry->setUser($user)
                ->setDate($date)
                ->setScores($data['scores'] ?? null)
                ->setSummary($data['summary'] ?? null)
                ->setRawData(['aspects' => $data['aspects'] ?? []])
                ->setInsights($data['insights'] ?? null)
                ->setGeneratedAt(new DateTimeImmutable())
                ->setFinal(false);
            $this->em->persist($entry);
            $this->em->flush();
        }
        return $this->json([
            'date' => $entry->getDate()->format('Y-m-d'),
            'scores' => $entry->getScores(),
            'summary' => $entry->getSummary(),
            'insights' => $entry->getInsights(),
            'aspects' => ($entry->getRawData()['aspects'] ?? []),
            'nextTransit' => $entry->getNextTransit(),
            'is_final' => $entry->isFinal(),
        ]);
    }
}
