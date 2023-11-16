<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'SensioTV+', 'The title failed');
    }

    public function testUserRegistration(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $registerLink = $client->getCrawler()->filter('[href="/register"]')->link();
        $client->click($registerLink);

        $this->assertSelectorTextContains('h1', 'Create your account');
        // Test validation errors
        $client->submitForm('Create your SensioTV account', [
            'user[firstName]' => 'Joseph'
        ]);
        $this->assertCount(2, $client->getCrawler()->filter('.invalid-feedback'));

        // When form is valid
        $client->submitForm('Create your SensioTV account', [
            'user[firstName]' => 'Joseph',
            'user[email]' => 'joseph@test.fr',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
        ]);
        $this->assertCount(0, $client->getCrawler()->filter('.invalid-feedback'));

        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'joseph@test.fr']);
        dump($user);
        $this->assertNotNull($user);
        $this->assertEquals('Joseph', $user->getFirstName());

        file_put_contents(__DIR__.'/../../public/test.html', var_export($client->getResponse()->getContent(), true));die;
    }
}
