<?php

namespace App\Tests\Application;

use App\Controller\TaskController;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UploadTest extends WebTestCase
{
    private const URI = "http://localhost";

    //private const SUCCESS_MESSAGE = "Solution is successfully uploaded";

    public function testIfRoutePrivate(): void
    {
        $client = static::createClient();
        $client->request('POST', '/task/upload/4');

        $this->assertResponseRedirects(self::URI . "/login");
    }

    public function testSubmitSolutionPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/view/2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a.text-primary', "Log In");
    }

    public function testUploadTestAsFile(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy([
            'email' => 'john.doe@gmail.com',
        ]);

        $client->loginUser($user);

        $client->followRedirects();

        $crawler = $client->request('GET', '/task/view/4');

        $form = $crawler->selectButton('upload_solution_save')->form();
        $form["upload_solution[file_solution]"]->upload(__DIR__ . '/data/test.cpp');

        $client->submit($form);

        $this->assertStringContainsString(TaskController::SUCCESS_UPLOAD_MESSAGE, $client->getResponse()->getContent());
    }
}
