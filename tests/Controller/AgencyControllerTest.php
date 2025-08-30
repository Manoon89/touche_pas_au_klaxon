<?php

namespace App\Tests\Controller;

use App\Entity\Agency;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AgencyControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $agencyRepository;
    private EntityRepository $userRepository;
    private string $path = '/agency/';

    protected function setUp(): void
    {
        // Permet de faire des requêtes comme si on est un navigateur
        $this->client = static::createClient();
        // Permet d'interagir directement avec la base de données
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        // Permet d'utiliser les méthodes find(), findAll(), count() sur les agences et les utilisateurs
        $this->agencyRepository = $this->manager->getRepository(Agency::class);
        $this->userRepository = $this->manager->getRepository(User::class);

        // Crée un utilisateur pour se logger
        $user = $this->userRepository->findOneBy(['email' => 'admin@example.com']);
        if (!$user) {
            $user = new User();
            $user->setLastName('Nom');
            $user->setFirstName('Prénom');
            $user->setPhone('0123456789');
            $user->setEmail('admin@example.com');
            $user->setPassword('password');
            $user->setRoles(['ROLE_ADMIN']);
            $this->manager->persist($user);
            $this->manager->flush();
        }
        $this->client->loginUser($user);

        // Vide la table test agency avant chaque test
        foreach ($this->agencyRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création d'agences pour ce test
        $fixture1 = new Agency();
        $fixture1->setCity('Lyon');

        $fixture2 = new Agency();
        $fixture2->setCity('Nimes');

        $fixture3 = new Agency();
        $fixture3->setCity('Versailles');

        $this->manager->persist($fixture1);
        $this->manager->persist($fixture2);
        $this->manager->persist($fixture3);
        $this->manager->flush();

        $crawler = $this->client->request('GET', $this->path);

        // On vérifie que la requête est réussie et qu'on arrive bien sur la page Liste des agences
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Liste des agences');

        // On récupère le contenu du body de la page HTML
        $pageText = $crawler->filter('body')->text();

        // On vérifie que les villes sont bien présentes dans le body
        self::assertStringContainsString('Lyon', $pageText);
        self::assertStringContainsString('Nimes', $pageText);
        self::assertStringContainsString('Versailles', $pageText);

        // Récupère la position de chaque ville
        $positionLyon = strpos($pageText, 'Lyon');
        $positionNimes = strpos($pageText, 'Nimes');
        $positionVersailles = strpos($pageText, 'Versailles');

        // Vérifie que la position de chaque ville est bien dans l'ordre attendu
        self::assertTrue($positionLyon < $positionNimes && $positionNimes < $positionVersailles, 'les agences ne sont pas triées par ordre alphabétique');
    }

    public function testNew(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // On récupère le formulaire de création
        $this->client->request('GET', sprintf('%snew', $this->path));

        // On vérifie que la requête a réussi & que la page s'affiche
        self::assertResponseStatusCodeSame(200);

        // On soumet le formulaire avec une nouvelle ville
        $this->client->submitForm('save', [
            'agency[city]' => 'VilleTest',
        ]);

        self::assertResponseStatusCodeSame(200);

        // Vérifie que l'agence a bien été rajoutée en base
        $agencies = $this->agencyRepository->findAll();
        self::assertCount(1, $agencies);
        self::assertSame('VilleTest', $agencies[0]->getCity());

        // Vérifie le message flash s'affiche bien
        $this->assertSelectorTextContains('.flash', 'L\'agence a bien été créée');
    }

    public function testEdit(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création d'une agence pour le test 
        $fixture = new Agency();
        $fixture->setCity('VilleAvantModif');

        $this->manager->persist($fixture);
        $this->manager->flush();

        // On récupère le formulaire de modification
        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        // On soumet le formulaire avec la nouvelle ville
        $this->client->submitForm('save', [
            'agency[city]' => 'VilleApresModif',
        ]);

        self::assertResponseStatusCodeSame(200); 

        // Vérifie que l'agence a bien été modifiéée en base
        $fixture = $this->agencyRepository->findAll();
        self::assertSame('VilleApresModif', $fixture[0]->getCity());

        // Vérifie le message flash s'affiche bien        
        self::assertSelectorTextContains('.flash', 'L\'agence a bien été modifiée');
    }

    public function testRemove(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création d'une agence pour le test 
        $fixture = new Agency();
        $fixture->setCity('VilleASupprimer');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);

        // On soumet le formulaire de suppression avec le token
        $deleteForm = $crawler->filter('form')->reduce(function ($node) use ($fixture) {
            return strpos($node->attr('action'), (string)$fixture->getId()) !== false;
        })->first()->form();

        $this->client->submit($deleteForm);

        // On vérifie que l'agence a bien été supprimée
        self::assertSame(0, $this->agencyRepository->count([]));

        // Vérifie le message flash s'affiche bien
        self::assertSelectorTextContains('.flash', 'L\'agence a bien été supprimée');
    }
}
