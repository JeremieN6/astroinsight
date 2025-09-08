<?php
namespace App\Command;

use App\Repository\AstroProfileRepository;
use App\Service\Astro\NatalChartUpdater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'astro:natal:recompute', description: 'Recompute natal charts for profiles missing data')]
class AstroRecomputeNatalCommand extends Command
{
    public function __construct(private AstroProfileRepository $repo, private NatalChartUpdater $updater)
    { parent::__construct(); }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $profiles = $this->repo->findAll();
        $updated = 0; $skipped = 0;
        foreach ($profiles as $p) {
            if ($p->getNatalChartJson() === null) {
                try { $this->updater->update($p); $updated++; }
                catch (\Throwable $e) { $io->warning('Skip profile '.$p->getId().': '.$e->getMessage()); $skipped++; }
            } else { $skipped++; }
        }
        $io->success("Updated $updated profiles; skipped $skipped.");
        return Command::SUCCESS;
    }
}
