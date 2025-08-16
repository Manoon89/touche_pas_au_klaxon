<?php

namespace App\Tests\Controller;

use App\Entity\Journey;
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

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->journeyRepository = $this->manager->getRepository(Journey::class);

        foreach ($this->journeyRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Journey index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'journey[departureDate]' => 'Testing',
            'journey[arrivalDate]' => 'Testing',
            'journey[totalSeats]' => 'Testing',
            'journey[availableSeats]' => 'Testing',
            'journey[user]' => 'Testing',
            'journey[departureAgency]' => 'Testing',
            'journey[arrivalAgency]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->journeyRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Journey();
        $fixture->setDepartureDate('My Title');
        $fixture->setArrivalDate('My Title');
        $fixture->setTotalSeats('My Title');
        $fixture->setAvailableSeats('My Title');
        $fixture->setUser('My Title');
        $fixture->setDepartureAgency('My Title');
        $fixture->setArrivalAgency('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Journey');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Journey();
        $fixture->setDepartureDate('Value');
        $fixture->setArrivalDate('Value');
        $fixture->setTotalSeats('Value');
        $fixture->setAvailableSeats('Value');
        $fixture->setUser('Value');
        $fixture->setDepartureAgency('Value');
        $fixture->setArrivalAgency('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'journey[departureDate]' => 'Something New',
            'journey[arrivalDate]' => 'Something New',
            'journey[totalSeats]' => 'Something New',
            'journey[availableSeats]' => 'Something New',
            'journey[user]' => 'Something New',
            'journey[departureAgency]' => 'Something New',
            'journey[arrivalAgency]' => 'Something New',
        ]);

        self::assertResponseRedirects('/journey/');

        $fixture = $this->journeyRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDepartureDate());
        self::assertSame('Something New', $fixture[0]->getArrivalDate());
        self::assertSame('Something New', $fixture[0]->getTotalSeats());
        self::assertSame('Something New', $fixture[0]->getAvailableSeats());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getDepartureAgency());
        self::assertSame('Something New', $fixture[0]->getArrivalAgency());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Journey();
        $fixture->setDepartureDate('Value');
        $fixture->setArrivalDate('Value');
        $fixture->setTotalSeats('Value');
        $fixture->setAvailableSeats('Value');
        $fixture->setUser('Value');
        $fixture->setDepartureAgency('Value');
        $fixture->setArrivalAgency('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/journey/');
        self::assertSame(0, $this->journeyRepository->count([]));
    }
}
