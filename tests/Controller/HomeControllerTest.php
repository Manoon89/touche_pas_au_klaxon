<?php

namespace App\Tests\Controller;
use App\Entity\Agency;
use App\Entity\User;
use App\Entity\Journey;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomeControllerTest extends WebTestCase
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
      
        $this->manager->flush();
    }

    public function testIndexWithoutJourney(): void
    {        
        $crawler = $this->client->request('GET', '/');

        // Vérifie que la page renvoie un 200
        self::assertResponseIsSuccessful();

        // Vérifie que la page contient le titre Touche pas au klaxon
        self::assertSelectorTextContains('title', 'Touche pas au klaxon');

        // Vérifie qu'il y a au moins un trajet, ou le message "Aucun trajet disponible"
        $noJourneyExists = $crawler->filter('td.no-journey')->count() > 0;
        self::assertTrue($noJourneyExists, 'la page doit afficher le message "Aucun trajet disponible"');
    }

    public function testIndexWithJourney(): void
    {
        $container = $this->client->getContainer();
        $manager = $container->get('doctrine')->getManager();

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

        // Création des entités nécessaires
        $agencyDeparture = new Agency();
        $agencyDeparture->setCity('Paris');
        $this->manager->persist($agencyDeparture);

        $agencyArrival = new Agency();
        $agencyArrival->setCity('Lyon');
        $this->manager->persist($agencyArrival);

        $journey = new Journey();
        $journey->setDepartureAgency($agencyDeparture);
        $journey->setArrivalAgency($agencyArrival);
        $journey->setDepartureDate(new \DateTime('+1 day'));
        $journey->setArrivalDate(new \DateTime('+1 day +2 hours'));
        $journey->setAvailableSeats(3);
        $journey->setTotalSeats(4);
        $journey->setUser($user);
        $this->manager->persist($journey);

        $this->manager->flush();

        // On connecte l’utilisateur au client
        $this->client->loginUser($user);        

        $crawler = $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('title', 'Touche pas au klaxon');

        // Vérifie qu'au moins un trajet est affiché
        $journeyExists = $crawler->filter('.journey')->count() > 0;
        self::assertTrue($journeyExists, 'La page doit afficher au moins un trajet.');
    }

}
