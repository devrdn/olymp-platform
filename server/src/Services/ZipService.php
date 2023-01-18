<?php

namespace App\Services;

use App\Entity\Task;
use App\Entity\TaskTest;
use App\Exception\ZipException;
use App\Exception\ZipServiceException;
use App\Repository\TaskTestRepository;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use ZipArchive;

/**
 * Service responsable for check zip files and extract to certain directory
 */
class ZipService
{

   /**
    * @var ZipArchive Zip Archive
    */
   private ZipArchive $zip;

   /**
    * @var string Openning file error
    */
   private const ERR_OPEN = "Zip archive is not opened";

   /**
    * @var string Invalid file name error
    */
   private const ERR_EXTRACT = "Unsuccessful file extraction";

   /**
    * @var string Missing File Pair Error
    */
   private const ERR_NO_COUPLE = "Some files doesn't have couple";

   /**
    * @var string File Identifier Pattern
    */
   private const FILE_IDENTIFIER_PATTERN = "[id]";


   public function __construct(private TaskTestRepository $taskTestRepository, private Filesystem $filesystem)
   {
      $this->zip = new ZipArchive;
   }

   /**
    * Open Zip file
    *
    * @param string Path to Archive
    *
    * @return bool Did Archive Openned
    */
   public function openZip(string $archive): bool
   {
      // open Zip archive
      if ($this->zip->open($archive) !== true) {
         return false;
      }

      return true;
   }

   /**
    * @param ZipArchive $zip Zip Archive
    *
    * @return bool Check if archive is openned
    */
   private function isZipOpenned(): bool
   {
      return !empty($this->zip->filename);
   }

   /**
    * Extract files to path if they are correct
    *
    * @param string $path Path to extract
    * @param bool $close Should the archive be closed after extraction
    *
    * @return bool TRUE on success or FALSE on failure
    */
   public function extractFilesIfHasPair(Task $task, string $inputPattern, string $outputPattern, string $path, bool $close = true, bool $hasCouple = true)
   {
      // Check if archive is openned
      if (!$this->isZipOpenned()) {
         return new ZipServiceException(self::ERR_OPEN);
      }

      // get file identifier pattern
      $fileIdentifierPattern = $this->getFileIdentifierPattern();

      // create regex patterns from input and output
      $inputPattern = '/' . str_replace($fileIdentifierPattern, "(.+)", $inputPattern) . '/';

      $pathToFiles = $this->createPathToFile($task->getId(), $path);

      // check if all files in archive is valid
      for ($i = 0; $i < $this->zip->numFiles; $i++) {
         // get filename by index
         $fileName = $this->zip->getNameIndex($i);

         $fileIdentifier = $this->getFileIdentifier($inputPattern, $fileName);

         if (!$fileIdentifier) {
            continue;
         }

         if ($hasCouple) {
            // check if file has couple
            $filePair = $this->getFilePair($fileIdentifier, $outputPattern);

            if (!$filePair) {
               throw new ZipServiceException(self::ERR_NO_COUPLE);
            }
         }

         $filesToExtract = [$fileName, $filePair];
         $isSuccess = $this->extractFilesTo($filesToExtract, $pathToFiles);

         if (!$isSuccess) {
            throw new ZipServiceException(self::ERR_EXTRACT);
         }

         $this->saveFiles($task, $pathToFiles, $fileName, $filePair);
      }

      if ($close) {
         $this->zip->close();
      }
   }


   /**
    * Save files to DataBase
    *
    * @param Task $task
    * @param string $inputFile Input file name
    * @param string $path Path with tests
    * @param string $outputFile Output file name
    */
   private function saveFiles(Task $task, string $path, string $inputFile, string $outputFile): void
   {

      // check if task exists in Database
      $ifExists = $this->taskTestRepository->findOneBy([
         'task' => $task->getId(),
         'input_data' => $path . $inputFile
      ]);

      if ($ifExists) {
         return;
      }

      $taskTest = new TaskTest();
      $taskTest->setTask($task);
      $taskTest->setOutputData($path . $outputFile);
      $taskTest->setInputData($path . $inputFile);

      // save files to DB
      $this->taskTestRepository->save($taskTest, true);
   }


   /**
    * Extract files to path
    *
    * @param string $path Path to extract
    *
    * @return bool TRUE on success or FALSE on failure
    */
   private function extractFilesTo(string|array $file, string $path): bool
   {
      $isSuccess = $this->zip->extractTo($path, $file);
      return $isSuccess;
   }
   /**
    * Check File has couple in archive
    *
    * @param string $fileIdentifier Identifier of the file (e.g. 1, 2, "a")
    * @param string $pairFilePattern Pattern of couple name (e.g. [id]_output.txt)
    *
    * @return string|false Name of pair file in the archive or FALSE if doesn't exist
    */
   private function getFilePair(string $fileIdentifier, string $pairFilePattern): string|false
   {
      $coupleFileName = str_replace($this->getFileIdentifierPattern(), $fileIdentifier, $pairFilePattern);
      return $this->zip->locateName($coupleFileName) === false ? false : $coupleFileName;
   }

   /**
    * Create path to file
    */
   private function createPathToFile(int $taskId, string $path): string
   {
      $fullPath = $path . '/' . $taskId . '/tests/';

      // throw new Exception($fullPath);
      if (!$this->filesystem->exists($fullPath)) {
         try {
            $this->filesystem->mkdir($fullPath);
         } catch (Exception) {
            throw new ZipServiceException("Cannot save test to path " . $fullPath);
         }
      }

      return $fullPath;
   }

   /**
    * Get file identifier by pattern
    * 
    * @param string $pattern Pattern
    * @param string $fileName Full file name
    *
    * @return string|false File identifier or false if no matches is found
    */
   private function getFileIdentifier(string $pattern, string $fileName): string|false
   {
      $matches = [];
      preg_match($pattern, $fileName, $matches);
      return empty($matches) ? false : $matches[1];
   }


   /**
    * @return string File Identifier Pattern
    */
   private function getFileIdentifierPattern(): string
   {
      return self::FILE_IDENTIFIER_PATTERN;
   }


   # todo: delete method

   //public function isAllFilesHasCouple(string $input, string $output, bool $hasOutput = true): string|bool
   //{
   //   if (!$this->isZipOpenned()) {
   //      return self::ERR_OPEN;
   //   }

   //   // check if input and output has identifier
   //   $fileHasIdentifier = boolval(preg_match(self::FILE_IDENTIFIER_PATTERN, $input) && preg_match(self::FILE_IDENTIFIER_PATTERN, $output));

   //   // create regex patterns from input and output
   //   $inputPattern = '/' . preg_replace(self::FILE_IDENTIFIER_PATTERN, "(.+)", $input) . '/';
   //   $outputPattern = '/' . preg_replace(self::FILE_IDENTIFIER_PATTERN, "(.+)", $output) . '/';


   //   // check if all files in archive is valid
   //   for ($i = 0; $i < $this->zip->numFiles; $i++) {
   //      // get file name by index
   //      $fileName = $this->zip->getNameIndex($i);
   //      $isValidInput = preg_match($inputPattern, $fileName, $matches);
   //      $isValidOutput = $isValidInput || preg_match($outputPattern, $fileName, $matches);

   //      // if file name is invalid
   //      if (!($isValidInput || $isValidOutput)) {
   //         return self::ERR_INVALID_NAME;
   //         break;
   //      }

   //      // if task doesn't have output or doesn't have Identifier then continue
   //      if (!($hasOutput && $fileHasIdentifier)) {
   //         continue;
   //      }

   //      // check if file has couple
   //      // $hasCouple = $this->fileHasCouple($matches[1], self::FILE_IDENTIFIER_PATTERN, $isValidInput ? $output : $input);
   //      //if ($hasCouple === false) {
   //      //   return self::ERR_NO_COUPLE;
   //      //   break;
   //      //}
   //   }

   //   return true;
   //}
}
