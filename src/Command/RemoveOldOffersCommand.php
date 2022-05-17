<?php

namespace App\Command;

use App\Entity\JobOffer;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remove-old-offers',
    description: 'This command removes old offers',
)]
class RemoveOldOffersCommand extends Command
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->em = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('max-days', InputArgument::OPTIONAL, 'Days that take to consider an offer as old', 90)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Apply changes or not')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $maxDays = $input->getArgument('max-days');


        $io->note(sprintf('Offers older than %s are considered old', $maxDays));

        $dryRun = false;
        if ($input->getOption('dry-run')) {
            $dryRun = true;
            $io->note("No changes will happen");
        }

        $oldOffers = $this->em->getRepository(JobOffer::class)->createQueryBuilder('jo')
            ->where('jo.created_at < :limitDate')
            ->setParameter('limitDate', new DateTimeImmutable('today -'. $maxDays.' days'))
            ->getQuery()->getResult();

        $io->writeln('There are '.count($oldOffers).' old offers');

        $io->success('All old offers have been removed');

        return Command::SUCCESS;
    }
}
