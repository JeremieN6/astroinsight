<?php
namespace App\Command;

use App\Repository\ApiCacheEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:api-cache:purge', description: 'Purge expired API cache entries')]
class ApiCachePurgeCommand extends \Symfony\Component\Console\Command\Command
{
    public function __construct(private ApiCacheEntryRepository $repo, private EntityManagerInterface $em)
    { parent::__construct(); }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $count = $this->repo->purgeExpired();
        $io->success($count.' expired entries purged');
        return self::SUCCESS;
    }
}
