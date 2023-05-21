<?php

namespace App\Tests\Application;

use App\Constants\MessageConstants;
use App\Controller\TaskController;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UploadTest extends WebTestCase
{
    private const URI = "http://localhost";

    public function testIfRoutePrivate(): void
    {
        $client = static::createClient();
        $client->request('POST', '/task/upload/4');

        $this->assertResponseRedirects(self::URI . "/login");
    }

    public function testSubmitSolutionPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/view/4');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a.text-primary', "Log In");
    }

    public function dataUploadTestAsFile(): array
    {
        return [
            [__DIR__ . "/data/helloWorld.cpp"],
            [__DIR__ . "/data/helloWorld.py"]
        ];
    }

    /**
     * @dataProvider dataUploadTestAsFile
     */
    public function testUploadTestAsFile(string $file): void
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
        $form["upload_solution[file_solution]"]->upload($file);

        $client->submit($form);

        $this->assertStringContainsString(MessageConstants::SUCCESSFULLY_UPLOADED, $client->getResponse()->getContent());
    }

    public function dataUploadTestAsTest(): array
    {
        return [
            ["print(\"Hello World\")", "py"],
            ["<?php\necho \"Hello World\";", "php"],
        ];
    }

    /**
     * @dataProvider dataUploadTestAsTest
     */
    public function testUploadTestAsText(string $text, string $programmingLanguage): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy([
            'email' => 'john.doe@gmail.com',
        ]);

        $client->loginUser($user);

        $client->followRedirects();

        $client->request('GET', '/task/view/4');

        $client->submitForm("upload_solution[save]", [
                "upload_solution[text_solution]" => $text,
                'upload_solution[language]' => $programmingLanguage,
            ]
        );

        $this->assertStringContainsString(MessageConstants::SUCCESSFULLY_UPLOADED, $client->getResponse()->getContent());
    }

    public function testUploadSolutionValidation(): void
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
        $form["upload_solution[file_solution]"]->upload(__DIR__ ."/data/helloWorld.cpp");
        $form["upload_solution[text_solution]"]->setValue('test');
        $client->submit($form);

        $this->assertStringContainsString(MessageConstants::ONLY_ONE_FIELD_FILLED, $client->getResponse()->getContent());
    }
}