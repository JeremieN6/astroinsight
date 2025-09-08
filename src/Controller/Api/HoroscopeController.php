<?php
namespace App\Controller\Api;

use App\Repository\HoroscopeCacheRepository;
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
        private HoroscopeCacheRepository $cacheRepo,
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
        $cache = $this->cacheRepo->findForUserAndDate($user, $date, 'daily');
        if (!$cache) {
            $profile = $this->profileRepo->findOneByUser($user);
            if (!$profile) {
                return $this->json(['error' => 'astro_profile_missing'], 400);
            }
            $data = $this->generator->generate($profile, $date);
            $cache = new \App\Entity\HoroscopeCache();
            $cache->setUser($user)->setDate($date)->setScope('daily')
                ->setScores($data['scores'])
                ->setSummary($data['summary'])
                ->setAspects($data['aspects'])
                ->setGeneratedAt(new DateTimeImmutable())
                ->setIsFinal(false);
            $this->em->persist($cache);
            $this->em->flush();
        }
        return $this->json([
            'date' => $cache->getDate()->format('Y-m-d'),
            'scope' => 'daily',
            'scores' => $cache->getScores(),
            'summary' => $cache->getSummary(),
            'aspects' => $cache->getAspects(),
            'is_final' => $cache->isFinal(),
        ]);
    }
}
