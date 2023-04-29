<?php

namespace App\Services;

use App\Exception\FileUploaderException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Service responsible for uploading files
 */
class FileUploader
{
    /**
     * Directory To Upload File
     *
     * @var string
     */
    private string $targetDirectory;


    public function __construct(private readonly Filesystem $fileSystem)
    {
    }

    /**
     * Upload File To The Directory
     *
     * @param UploadedFile $file File to upload
     * @param string $fileName New filename
     *
     * @return string File path
     *
     * @throws FileUploaderException If file cannot be moved to directory
     */
    public function uploadFile(UploadedFile $file, string $fileName = ''): string
    {
        // create directory if not exist
        $this->createTargetDirectoryIfNotExist();

        // upload file to created directory
        $fileNameToUpload = $fileName === '' ? $file->getClientOriginalName() : $fileName;
        try {
            $file->move($this->targetDirectory, $fileNameToUpload);
        } catch (FileException) {
            throw $this->createFileUploaderException("Cannot move file to directory");
        }

        // return file path
        return $this->getFullFilePath($fileName);
    }

    /**
     * Create and Upload File
     *
     * @param string $fileContent
     * @param string $fileName
     *
     * @return string File Path
     * @throws FileUploaderException
     */
    public function createAndUploadFile(string $fileContent, string $fileName): string
    {
        $filePath = $this->getFullFilePath($fileName);

        $this->createTargetDirectoryIfNotExist();
        $this->createFile($filePath, $fileContent);

        return $this->getFullFilePath($filePath);
    }

    /**
     * Set Target Directory
     *
     * @param string $targetDirectory
     *
     * @return void
     */
    public function setTargetDirectory(string $targetDirectory): void
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Create Directory
     *
     * @return void
     *
     * @throws FileUploaderException If directory cannot be created
     */
    private function createTargetDirectoryIfNotExist(): void
    {
        if (!$this->fileSystem->exists($this->targetDirectory)) {
            try {
                $this->fileSystem->mkdir($this->targetDirectory);
            } catch (IOException  $exception) {
                throw $this->createFileUploaderException("It's impossible upload solution right now, try later");
            }
        }
    }

    /**
     * Create File
     *
     * @param string $filePath Path to file
     * @param string $content File content
     *
     * @return void
     *
     * @throws IOException|FileUploaderException If file cannot be created
     */
    private function createFile(string $filePath, string $content): void
    {
        try {
            $this->fileSystem->dumpFile($filePath, $content);
        } catch (IOException $exception) {
            throw $this->createFileUploaderException("It's impossible upload solution right now, try later");
        }
    }

    /**
     * Create A File Unique Name
     *
     * @param string $filePrefix String in start of file (e.g. task, archive, etc..)
     * @param string $fileExtension
     * @param string $uniqueIdentifier Unique identifier for file
     *
     * @return string File Unique Name
     */
    public static function createFileName(string $filePrefix, string $uniqueIdentifier = '', string $fileExtension = ''): string
    {
        return $filePrefix . '_' . $uniqueIdentifier . '_' . date('Y-m-d_H-i-s') . '.' . $fileExtension;
    }

    /**
     * @param string $fileName
     *
     * @return string Full File Path
     */
    private function getFullFilePath(string $fileName): string
    {
        return $this->targetDirectory . (preg_match('/.*\/$/', $this->targetDirectory) ? '' : '/') . $fileName;
    }

    /**
     * Create FileUploaderException
     *
     * @param string $message Exception message
     *
     * @return FileUploaderException Exception with message
     */
    private function createFileUploaderException(string $message): FileUploaderException
    {
        return new FileUploaderException($message);
    }
}
