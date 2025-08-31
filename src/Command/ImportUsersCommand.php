<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Commande console pour importer des utilisateurs depuis un fichier CSV ou texte
 */
#[AsCommand(
    name: 'import:users',
    description: 'Add users',
)]

class ImportUsersCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    /**
     * Configure les arguments et options de la commande
     */
    protected function configure(): void
    {
        $this
            // Argument optionnel : chemin du fichier à importer
            ->addArgument('file', InputArgument::OPTIONAL, 'Chemin du fichier', __DIR__ . '/../../data/users.txt')
            // Option : délimiteur CSV
            ->addOption('delimiter', null, InputOption::VALUE_REQUIRED, 'Délimiteur CSV', ',');
    }

    /**
     * Exécute l'import des utilisateurs
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
            $output->writeln('<error>Délimiteur invalide.</error>');
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

        $repo = $this->em->getRepository(User::class);
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            // conversion CSV en tableau
            $data = str_getcsv(trim($line), $delimiter);

            // Attendu : firstName,lastName,email,phone,[role]
            if (count($data) < 4) { continue; }

            [$lastName, $firstName, $phone, $email] = array_map(
                fn(?string $s) => $s !== null ? trim($s) : '',
                array_slice($data, 0, 4)
            );
            $roleFromFile = strtoupper(trim($data[4] ?? 'ROLE_USER'));

            // Skip si déjà existant (email unique)
            if ($repo->findOneBy(['email' => $email])) { continue; }

            $user = new User();
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setPhone($phone);
            $user->setEmail($email);

            // Utilise le hasher Symfony (config security.yaml). Comme l'admin ne doit pas avoir de CRUD pour les utilisateurs
            // je suis partie sur un MDP identique pour tous. En prod, il faudrait que l'admin puisse a  minima réinitialiser
            // le mot de passe d'un utilisateur et/ou que cet utilisateur puisse faire une demande en cas de mot de passe perdu.
            $hashed = $this->passwordHasher->hashPassword($user, 'defaultPassword');
            $user->setPassword($hashed);

            // Définit le rôle (array JSON)
            // Accepte "ROLE_ADMIN"/"ROLE_USER" en 5e colonne, sinon ROLE_USER
            $roles = in_array($roleFromFile, ['ROLE_ADMIN', 'ROLE_USER'], true)
                ? [$roleFromFile]
                : ['ROLE_USER'];
            $user->setRoles($roles);

            $this->em->persist($user);
            $count++;
        }

        fclose($handle);
        $this->em->flush();

        $output->writeln("<info>Import terminé : $count utilisateur(s) créés.</info>");
        return Command::SUCCESS;
    }
}