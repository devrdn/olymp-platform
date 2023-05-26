<?php

namespace App\Tests\Integration\Services;

use App\Exception\FileUploaderException;
use App\Services\FileUploader;
use App\Tests\Config\Constants;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends KernelTestCase
{
    private const TEST_DIR = __DIR__ . "/data";

    private FileUploader $fileUploader;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        // boot kernel and get service
        self::bootKernel();
        $container = static::getContainer();
        $this->fileUploader = $container->get(FileUploader::class);
    }

    public function dataCreateAndUploadFile(): array
    {
        return [
            ["../data/user/1", "task.txt", "Test Content"],
            ["../data/user/3", "task_2.txt", "Контент"],
            ["../data/user/1", "task_1_1.txt", "Hey, Hey, Hey"],
        ];
    }

    /**
     * @dataProvider dataCreateAndUploadFile
     * @throws FileUploaderException
     * @throws Exception
     */
    public function testCreateAndUploadFile(string $fileDirectory, string $fileName, string $fileContent, string $exception = null)
    {
        $this->fileUploader->setTargetDirectory($fileDirectory);
        $filePath = $this->fileUploader->createAndUploadFile($fileContent, $fileName);

        // if file exist and contain right content
        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, $fileContent);
    }

    public function dataCreateAndUploadFileException(): array
    {
        return [
            ["../.*de=a-=asd", "task.txt", "Test Content"],
            ["...", "task.txt", "Test Content"],
            ["data", "", "Test Content"],
        ];
    }

    /**
     * @dataProvider dataCreateAndUploadFileException
     */
    public function testCreateAndUploadFileException(string $fileDirectory, string $fileName, string $fileContent)
    {
        $this->expectException(FileUploaderException::class);

        $this->fileUploader->setTargetDirectory($fileDirectory);
        $this->fileUploader->createAndUploadFile($fileContent, $fileName);
    }

    public function dataUploadFile(): array
    {
            return [
                [new UploadedFile(self::TEST_DIR . '/test.txt', 'test.txt', null, null, true), "", __DIR__ . '/data/out'],
                [new UploadedFile(self::TEST_DIR . '/helloWorld.cpp', 'helloWorld.cpp', null, null, true), "helloWorld.cpp", __DIR__ . '/data/out'],
            ];
    }

    /**
     * @dataProvider dataUploadFile
     */
    public function testUploadFile(UploadedFile $file, string $newFileName, string $targetDirectory)
    {
        $this->fileUploader->setTargetDirectory($targetDirectory);
        $filePath = $this->fileUploader->uploadFile($file, $newFileName);
        $this->assertFileExists($filePath);
    }


    public function dataUploadFileException(): array
    {
        return [
            [new UploadedFile(self::TEST_DIR . '/test.txt', 'test.txt', null, null, true), "", __DIR__ . '.../data/'],
            [new UploadedFile(self::TEST_DIR . '/helloWorld.cpp', 'test.png', null, null, true), "new_test.png", __DIR__ . '...']
        ];
    }

    /**
     * @dataProvider dataUploadFileException
     */
    public function testUploadFileException(UploadedFile $file, string $newFileName, string $targetDirectory)
    {
        $this->expectException(FileUploaderException::class);

        $this->fileUploader->setTargetDirectory($targetDirectory);
        $this->fileUploader->uploadFile($file, $newFileName);
    }
}
