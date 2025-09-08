<?php
namespace App\Command;

use App\Repository\UsersRepository;
use App\Repository\AstroProfileRepository;
use App\Repository\HoroscopeCacheRepository;
use App\Service\Astro\HoroscopeGeneratorInterface;
use App\Entity\HoroscopeCache;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'astro:generate:daily', description: 'Generate daily horoscopes (stub) for users with an astro profile')]
class GenerateDailyHoroscopeCommand extends Command
{
    public function __construct(
        private UsersRepository $usersRepository,
        private AstroProfileRepository $astroProfileRepository,
        private HoroscopeCacheRepository $horoscopeCacheRepository,
        private HoroscopeGeneratorInterface $horoscopeGenerator,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $today = new DateTimeImmutable('today UTC');
        $users = $this->usersRepository->findAll();
        $countGenerated = 0; $countSkipped = 0; $countNoProfile = 0;

        foreach ($users as $user) {
            $profile = $this->astroProfileRepository->findOneByUser($user);
            if (!$profile) { $countNoProfile++; continue; }

            $existing = $this->horoscopeCacheRepository->findForUserAndDate($user, $today, 'daily');
            if ($existing && $existing->isFinal()) { $countSkipped++; continue; }

            $data = $this->horoscopeGenerator->generate($profile, $today);
            if (!$existing) {
                $existing = new HoroscopeCache();
                $existing->setUser($user)->setDate($today)->setScope('daily');
                $this->em->persist($existing);
            }
            $existing->setScores($data['scores'])
                ->setSummary($data['summary'])
                ->setAspects($data['aspects'])
                ->setGeneratedAt(new DateTimeImmutable())
                ->setIsFinal(false); // still stub
            $countGenerated++;
        }
        $this->em->flush();

        $io->success("Generated: $countGenerated | Skipped: $countSkipped | NoProfile: $countNoProfile");
        return Command::SUCCESS;
    }
}
