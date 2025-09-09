<?php
namespace App\Command;

use App\Repository\UsersRepository;
use App\Repository\AstroProfileRepository;
use App\Repository\DailyHoroscopeRepository;
use App\Service\Astro\HoroscopeGeneratorInterface;
use App\Service\Astro\AspectInterpreter;
use App\Entity\DailyHoroscope;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:horoscope:precompute', description: 'Precompute daily horoscope entries for all users having an astro profile.')] 
class PrecomputeDailyHoroscopeCommand extends Command
{
    public function __construct(
        private UsersRepository $usersRepository,
        private AstroProfileRepository $profileRepository,
        private DailyHoroscopeRepository $dailyRepo,
        private HoroscopeGeneratorInterface $generator,
        private AspectInterpreter $interpreter,
        private EntityManagerInterface $em,
    ) { parent::__construct(); }

    protected function configure(): void
    {
        $this->addArgument('date', InputArgument::OPTIONAL, 'Target date (Y-m-d, default today UTC)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dateArg = $input->getArgument('date');
        $date = $dateArg ? new DateTimeImmutable($dateArg.' UTC') : new DateTimeImmutable('today UTC');
        $users = $this->usersRepository->findAll();

        $generated = 0; $skipped = 0; $noProfile = 0; $updated = 0;
        foreach ($users as $user) {
            $profile = $this->profileRepository->findOneByUser($user);
            if (!$profile) { $noProfile++; continue; }
            $existing = $this->dailyRepo->findOneForUserDate($user, $date);
            if ($existing && $existing->isFinal()) { $skipped++; continue; }

            try {
                $payload = $this->generator->generate($profile, $date);
            } catch (\Throwable $e) {
                $io->warning('Generation failed user '.$user->getId().': '.$e->getMessage());
                continue;
            }

            $insights = $this->interpreter->interpret($payload['aspects'] ?? []);
            if (!$existing) {
                $existing = new DailyHoroscope();
                $existing->setUser($user)->setDate($date);
                $this->em->persist($existing);
                $generated++;
            } else { $updated++; }

            $existing->setSummary($payload['summary'] ?? null)
                ->setScores($payload['scores'] ?? null)
                ->setRawData(['aspects' => $payload['aspects'] ?? []])
                ->setInsights($insights)
                ->setNextTransit($payload['nextTransit'] ?? null)
                ->setGeneratedAt(new DateTimeImmutable())
                ->setFinal(false); // keep false until manual promotion criteria implemented
        }
        $this->em->flush();

        $io->success("New: $generated | Updated: $updated | Skipped(final): $skipped | NoProfile: $noProfile");
        return Command::SUCCESS;
    }
}
