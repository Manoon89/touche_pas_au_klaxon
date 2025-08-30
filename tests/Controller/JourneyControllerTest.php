<?php

namespace App\Tests\Controller;

use App\Entity\Journey;
use App\Entity\User;
use App\Entity\Agency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class JourneyControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $journeyRepository;
    private string $path = '/journey/';
    private EntityRepository $userRepository;
    private EntityRepository $agencyRepository;

    protected function setUp(): void
    {
        // Permet de faire des requêtes comme si on est un navigateur
        $this->client = static::createClient();
        // Permet d'interagir directement avec la base de données
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        // Permet d'utiliser les méthodes find(), findAll(), count() sur les agences et les utilisateurs
        $this->agencyRepository = $this->manager->getRepository(Agency::class);
        $this->userRepository = $this->manager->getRepository(User::class);  
        $this->journeyRepository = $this->manager->getRepository(Journey::class);     

        // Crée un utilisateur pour se logger
        $user = $this->userRepository->findOneBy(['email' => 'user@example.com']);
        if (!$user) {
            $user = new User();
            $user->setLastName('Nom');
            $user->setFirstName('Prénom');
            $user->setPhone('0123456789');
            $user->setEmail('user@example.com');
            $user->setPassword('password');
            $user->setRoles(['ROLE_USER']);
            $this->manager->persist($user);
            $this->manager->flush();
        }
        $this->client->loginUser($user);

        // Vide la table test journey avant chaque test
        $journeys = $this->manager->getRepository(Journey::class)->findAll();
        foreach ($journeys as $journey) {
            $this->manager->remove($journey);
        }

        // Vide la table test agency avant chaque test
        $agencies = $this->manager->getRepository(Agency::class)->findAll();
        foreach ($agencies as $agency) {
            $this->manager->remove($agency);
        }

        $this->manager->flush();
    }

    public function testNew(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création des entités nécessaires
        $departureAgency = new Agency();
        $departureAgency->setCity('Paris');
        $this->manager->persist($departureAgency);

        $arrivalAgency = new Agency();
        $arrivalAgency->setCity('Lyon');
        $this->manager->persist($arrivalAgency);

        $this->manager->flush();

        // On récupère le formulaire de création
        $this->client->request('GET', sprintf('%snew', $this->path));

        // On vérifie que la requête a réussi & que la page s'affiche
        self::assertResponseStatusCodeSame(200);

        // On soumet le formulaire avec un nouveau trajet
        $this->client->submitForm('save', [
            'journey[departureDate]' => (new \DateTime('+1 day'))->format('Y-m-d H:i'),
            'journey[arrivalDate]' => (new \DateTime('+1 day +2 hours'))->format('Y-m-d H:i'),
            'journey[totalSeats]' => 4,
            'journey[availableSeats]' => 3,
            'journey[departureAgency]' => $departureAgency->getId(),
            'journey[arrivalAgency]' => $arrivalAgency->getId(),
        ]);

        self::assertResponseStatusCodeSame(200);

        // Vérifie que le trajet a bien été rajouté en base
        $journeys = $this->journeyRepository->findAll();
        self::assertCount(1, $journeys);
        self::assertSame(4, $journeys[0]->getTotalSeats());

        // Vérifie le message flash s'affiche bien
        $this->assertSelectorTextContains('.flash', 'Le trajet a bien été créé');
    }

    public function testShow(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création des entités nécessaires
        $departureAgency = new Agency();
        $departureAgency->setCity('Paris');
        $this->manager->persist($departureAgency);

        $arrivalAgency = new Agency();
        $arrivalAgency->setCity('Lyon');
        $this->manager->persist($arrivalAgency);
    
        // Récupère l'utilisateur loggé
        $user = $this->userRepository->findOneBy(['email' => 'user@example.com']);

        // Création d'un trajet pour le test 
        $fixture = new Journey();
        $fixture->setDepartureDate(new \DateTime('+1 day'));
        $fixture->setArrivalDate(new \DateTime('+1 day +2 hours'));
        $fixture->setTotalSeats(5);
        $fixture->setAvailableSeats(4);
        $fixture->setDepartureAgency($departureAgency);
        $fixture->setArrivalAgency($arrivalAgency);
        $fixture->setUser($user);

        $this->manager->persist($fixture);
        $this->manager->flush();
    
        // On récupère la page show pour ce trajet
        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
    
        // Vérifie que certaines informations du trajet sont présentes dans la page
        self::assertSelectorTextContains('.journey-author', $user->getFirstName() . ' ' . $user->getLastName());
        self::assertSelectorTextContains('.journey-phone', $user->getPhone());
        self::assertSelectorTextContains('.journey-mail', $user->getEmail());
        self::assertSelectorTextContains('.journey-seats', $fixture->getTotalSeats());
    }

    public function testEdit(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création des entités nécessaires
        $departureAgency = new Agency();
        $departureAgency->setCity('Paris');
        $this->manager->persist($departureAgency);

        $arrivalAgency = new Agency();
        $arrivalAgency->setCity('Lyon');
        $this->manager->persist($arrivalAgency);

        // Récupère l'utilisateur loggé
        $user = $this->userRepository->findOneBy(['email' => 'user@example.com']);

        // Création d'un trajet pour le test 
        $fixture = new Journey();
        $fixture->setDepartureDate(new \DateTime('+1 day'));
        $fixture->setArrivalDate(new \DateTime('+1 day +2 hours'));
        $fixture->setTotalSeats(5);
        $fixture->setAvailableSeats(4);
        $fixture->setDepartureAgency($departureAgency);
        $fixture->setArrivalAgency($arrivalAgency);
        $fixture->setUser($user);

        $this->manager->persist($fixture);
        $this->manager->flush();

        // On récupère le formulaire de modification
        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        // On soumet le formulaire avec les nouvelles informations
        $this->client->submitForm('save', [
            'journey[departureDate]' => (new \DateTime('+1 day'))->format('Y-m-d H:i'),
            'journey[arrivalDate]' => (new \DateTime('+1 day +2 hours'))->format('Y-m-d H:i'),
            'journey[totalSeats]' => 8,
            'journey[availableSeats]' => 7,
            'journey[departureAgency]' => $arrivalAgency->getId(),
            'journey[arrivalAgency]' => $departureAgency->getId(),
        ]);

        self::assertResponseStatusCodeSame(200); 

        // Vérifie que l'agence a bien été modifiéée en base
        $fixture = $this->journeyRepository->findAll();

        self::assertSame(
            (new \DateTime('+1 day'))->format('Y-m-d H:i'),
            $fixture[0]->getDepartureDate()->format('Y-m-d H:i')
        );
        self::assertSame(
            (new \DateTime('+1 day +2 hours'))->format('Y-m-d H:i'),
            $fixture[0]->getArrivalDate()->format('Y-m-d H:i')
        );
        self::assertSame(8, $fixture[0]->getTotalSeats());
        self::assertSame(7, $fixture[0]->getAvailableSeats());
        self::assertSame($arrivalAgency->getId(), $fixture[0]->getDepartureAgency()->getId());
        self::assertSame($departureAgency->getId(), $fixture[0]->getArrivalAgency()->getId());

        // Vérifie le message flash s'affiche bien        
        self::assertSelectorTextContains('.flash', 'Le trajet a été modifié');

    }

    public function testRemove(): void
    {
        // Suit automatiquement les redirections
        $this->client->followRedirects();

        // Création des entités nécessaires
        $departureAgency = new Agency();
        $departureAgency->setCity('Paris');
        $this->manager->persist($departureAgency);

        $arrivalAgency = new Agency();
        $arrivalAgency->setCity('Lyon');
        $this->manager->persist($arrivalAgency);

        // Récupère l'utilisateur loggé
        $user = $this->userRepository->findOneBy(['email' => 'user@example.com']);

        // Création d'un trajet pour le test 
        $fixture = new Journey();
        $fixture->setDepartureDate(new \DateTime('+1 day'));
        $fixture->setArrivalDate(new \DateTime('+1 day +2 hours'));
        $fixture->setTotalSeats(5);
        $fixture->setAvailableSeats(4);
        $fixture->setDepartureAgency($departureAgency);
        $fixture->setArrivalAgency($arrivalAgency);
        $fixture->setUser($user);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $crawler = $this->client->request('GET', '/');

        self::assertResponseStatusCodeSame(200);

        // On soumet le formulaire de suppression avec le token
        $deleteForm = $crawler->filter('form')->reduce(function ($node) use ($fixture) {
            return strpos($node->attr('action'), (string)$fixture->getId()) !== false;
        })->first()->form();

        $this->client->submit($deleteForm);

        // On vérifie que le trajet a bien été supprimé
        self::assertSame(0, $this->journeyRepository->count([]));

        // Vérifie le message flash s'affiche bien
        self::assertSelectorTextContains('.flash', 'Le trajet a été supprimé');
    }
}
