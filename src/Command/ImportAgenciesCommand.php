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

/**
 * Commande console pour importer des agences depuis un fichier CSV ou texte
 */
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

    /**
     * Configure les arguments et options de la commande
     */
    protected function configure(): void
    {
        $this
            // Argument optionnel : chemin du fichier à importer
            ->addArgument('file', InputArgument::OPTIONAL, 'Chemin du fichier', __DIR__ . '/../../data/agences.txt')
            // Option : délimiteur CSV
            ->addOption('delimiter', null, InputOption::VALUE_REQUIRED, 'Délimiteur CSV', ',');
    }

    /**
     * Exécute l'import des agences
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * 
     * @return int Retourne le statut de la commande (succès ou échec)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath  = $input->getArgument('file');
        $delimiter = $input->getOption('delimiter');

        if (!is_string($delimiter)) {
            $output->writeln('<error>Le délimiteur doit être une chaîne de caractères.</error>');
            return Command::FAILURE;
        }

        // Vérifie que le fichier existe bien
        if (!is_string($filePath)) {
            $output->writeln("<error>Chemin de fichier invalide</error>");
            return Command::FAILURE;
        }

        // Vérifie que le fichier peut s'ouvrir en lecture
        if (($handle = fopen($filePath, 'r')) === false) {
            $output->writeln("<error>Impossible d’ouvrir le fichier.</error>");
            return Command::FAILURE;
        }

        $repo = $this->em->getRepository(Agency::class);
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            // conversion CSV en tableau
            $data = str_getcsv(trim($line), $delimiter);

            [$city] = array_map(fn(?string $s) => $s !== null ? trim($s) : '', array_slice($data, 0, 1));

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