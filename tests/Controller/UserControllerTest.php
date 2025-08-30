<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $userRepository;
    private string $path = '/user/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->manager->getRepository(User::class);

        // Supprime d'abord toutes les journeys pour éviter les contraintes FK
        $journeys = $this->manager->getRepository(\App\Entity\Journey::class)->findAll();
        foreach ($journeys as $journey) {
            $this->manager->remove($journey);
        }

        // Puis on supprime les utilisateurs
        foreach ($this->userRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {

        $this->client->followRedirects();
        
        // Création d'un utilisateur pour se connecter
        $user = new User();
        $user->setLastName('Nom');
        $user->setFirstName('Prénom');
        $user->setPhone('0123456789');
        $user->setEmail('admin+' . uniqid() . '@example.com');
        $user->setPassword('password');
        $user->setRoles(['ROLE_ADMIN']);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->client->loginUser($user);

        // Création d'autres utilisateurs pour tester l'affichage
        $fixture1 = new User();
        $fixture1->setLastName('Zephyr');
        $fixture1->setFirstName('Alice');
        $fixture1->setPhone('0122334455');
        $fixture1->setEmail('alice@email.fr');
        $fixture1->setPassword('password');
        $this->manager->persist($fixture1);

        $fixture2 = new User();
        $fixture2->setFirstName('Bob');
        $fixture2->setLastName('Anderson');
        $fixture2->setPhone('0222334455');
        $fixture2->setEmail('bob@example.com');
        $fixture2->setPassword('password');
        $this->manager->persist($fixture2);

        $this->manager->flush();

        // Requête vers la page des utilisateurs
        $crawler = $this->client->request('GET', '/user/index');

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Liste des utilisateurs');

        // Vérifie que deux utilisateurs sont affichés + l'admin
        $userRows = $crawler->filter('tr.user');
        self::assertSame(3, $userRows->count(), 'La page doit afficher les 3 utilisateurs.');

        // Vérifie l'ordre alphabétique par nom de famille
        $firstUserLastName = trim($userRows->eq(0)->filter('td')->eq(0)->text());
        $secondUserLastName = trim($userRows->eq(1)->filter('td')->eq(0)->text());
        $thirdUserLastName = trim($userRows->eq(2)->filter('td')->eq(0)->text());
        self::assertSame('Anderson', $firstUserLastName);
        self::assertSame('Nom', $secondUserLastName);
        self::assertSame('Zephyr', $thirdUserLastName);
    }
}