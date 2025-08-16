<?php

namespace App\Command;

use App\Entity\Agency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'import:agencies',
    description: 'Add agencies',
)]
class ImportAgenciesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::OPTIONAL, 'Chemin du fichier', __DIR__ . '/../../data/agences.txt')
            ->addOption('delimiter', null, InputOption::VALUE_REQUIRED, 'Délimiteur CSV', ',');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath  = $input->getArgument('file');
        $delimiter = $input->getOption('delimiter');

        if (!is_file($filePath)) {
            $output->writeln("<error>Fichier introuvable: $filePath</error>");
            return Command::FAILURE;
        }

        if (($handle = fopen($filePath, 'r')) === false) {
            $output->writeln("<error>Impossible d’ouvrir le fichier.</error>");
            return Command::FAILURE;
        }

        $repo = $this->em->getRepository(Agency::class);
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv(trim($line), $delimiter);

            // Attendu : city
            if (count($data) < 1) { continue; }

            [$city] = array_map('trim', array_slice($data, 0, 1));

            // Skip si déjà existant (ville unique)
            if ($repo->findOneBy(['city' => $city])) { continue; }

            $agency = new Agency();
            $agency->setCity($city);

            $this->em->persist($agency);
            $count++;
        }

        fclose($handle);
        $this->em->flush();

        $output->writeln("<info>Import terminé : $count agence(s) créées.</info>");
        return Command::SUCCESS;
    }
}