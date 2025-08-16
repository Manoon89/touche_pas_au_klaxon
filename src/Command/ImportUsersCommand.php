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

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::OPTIONAL, 'Chemin du fichier', __DIR__ . '/../../data/users.txt')
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

        $repo = $this->em->getRepository(User::class);
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $data = str_getcsv(trim($line), $delimiter);

            // Attendu : firstName,lastName,email,phone[,role]
            if (count($data) < 4) { continue; }

            [$firstName, $lastName, $email, $phone] = array_map('trim', array_slice($data, 0, 4));
            $roleFromFile = strtoupper(trim($data[4] ?? 'ROLE_USER'));

            // Skip si déjà existant (email unique)
            if ($repo->findOneBy(['email' => $email])) { continue; }

            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPhone($phone);

            // Utilise le hasher Symfony (config security.yaml). En prod il faudra générer un mot de passe aléatoire et l'envoyer par mail
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