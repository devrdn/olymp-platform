<?php

namespace App\Tests\Integration\Services;

use App\Exception\FileUploaderException;
use App\Services\FileUploader;
use PHPUnit\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileUploaderTest extends KernelTestCase {

    /**
     * @dataProvider dataCreateAndUploadFile
     */
    public function testCreateAndUploadFile(string $fileDirectory, string $fileName, string $fileContent) {
        // boot Symfony Kernel
        self::bootKernel();

        // access service container
        $container = static::getContainer();

        /**
         * @var FileUploader $fileUploader
         */
        $fileUploader = $container->get(FileUploader::class);
        $fileUploader->setTargetDirectory($fileDirectory);
        $filePath = $fileUploader->createAndUploadFile($fileContent, $fileName);

        // if file exist and contain right content
        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, $fileContent);
    }

    /**
     * @dataProvider dataCreateAndUploadFileException
     */
    public function testCreateAndUploadException(string $fileDirectory, string $fileName, string $fileContent)
    {
        self::bootKernel();

        // access service container
        $container = static::getContainer();

        $this->expectException(FileUploaderException::class);

        /**
         * @var FileUploader $fileUploader
         */
        $fileUploader = $container->get(FileUploader::class);
        $fileUploader->setTargetDirectory($fileDirectory);
        $fileUploader->createAndUploadFile($fileContent, $fileName);
    }

    public function dataCreateAndUploadFile(): array {
        return [
            ["../data/user/1", "task.txt", "Test Content"],
            ["../data/user/3", "task_2.txt", "Контент"],
            ["../data/user/1", "task_1_1.txt", "Hey, Hey, Hey"],
        ];
    }

    public function dataCreateAndUploadFileException(): array
    {
        return [
            ["../.*de=a-=asd", "task.txt", "Test Content"],
            ["...", "task.txt", "Test Content"],
            ["data", "", "Test Content"],
        ];
    }
}
