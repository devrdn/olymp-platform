<?php

namespace App\Services;

use App\Entity\Task;
use App\Entity\TaskTest;
use App\Exception\TestUploaderException;
use App\Repository\TaskTestRepository;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use ZipArchive;

/**
 * Service responsable for check zip files and extract to certain directory
 */
class TestUploader
{

   /**
    * @var ZipArchive Zip Archive
    */
   private ZipArchive $zip;

   /**
    * @var string Invalid file name error
    */
   private const ERR_EXTRACT = "Unsuccessful file extraction";

   /**
    * @var string 
    */
   private const ERR_SAVE_TO_DB = "Cannot Save some files to DataBase";

   /**
    * @var string File Identifier Pattern
    */
   private const FILE_IDENTIFIER_PATTERN = "[id]";


   public function __construct(private TaskTestRepository $taskTestRepository, private readonly Filesystem $filesystem)
   {
      $this->zip = new ZipArchive;
   }

   /**
    * Opens a Zip archive.
    *
    * @param string $archive The path to the Zip archive to open
    *
    * @return bool True if the archive was opened successfully, false otherwise
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
    * Extract Tests to path if they are correct
    *
    * @param Task    $task          The task to which the tests belong
    * @param string  $inputPattern  The pattern used to identify input files
    * @param string  $outputPattern The pattern used to identify output files
    * @param string  $path          The `data` folder
    * @param bool    $close         Whether the zip archive should be closed after extracting the tests (defaults to true)
    * @param bool    $testHasPairs  Whether the input tests have pairs (defaults to true)
    *
    * @return int Number of uploaded tests
    *
    * @throws TestUploaderException
    */
   public function extractTestsIfHasPair(Task $task, string $inputPattern, string $outputPattern, string $path, bool $close = true, bool $testHasPairs = true): int
   {
      // get file identifier pattern
      $fileIdentifierPattern = $this->getFileIdentifierPattern();

      // create regex patterns from input and output
      $inputPattern = '/' . str_replace($fileIdentifierPattern, "(.+)", $inputPattern) . '/';

      // create path to test folder
      $pathToTestFolder = $this->createTestFolder($task->getId(), $path);

      $filesToExtract = [
         'input_files' => [],
         'output_files' => []
      ];

      // check if all files in archive is valid
      for ($i = 0; $i < $this->zip->numFiles; $i++) {
         // get filename by index
         $fileName = $this->zip->getNameIndex($i);

         $fileIdentifier = $this->getFileIdentifier($inputPattern, $fileName);

         if (!$fileIdentifier) {
            continue;
         }

         $filePair = null;

         if ($testHasPairs) {
            // check if file has couple
            $filePair = $this->getFilePair($fileIdentifier, $outputPattern);

            if (!$filePair) {
               continue;
            }
         }

         $filesToExtract['input_files'][] = $fileName;
         $filesToExtract['output_files'][] = $filePair;
      }

      if (count($filesToExtract['input_files']) === 0 && count($filesToExtract['output_files']) === 0) {
         return 0;
      }

      $this->extractAndSaveTests($task, $pathToTestFolder, $filesToExtract['input_files'], $filesToExtract['output_files']);

      if ($close) {
         $this->zip->close();
      }

      return count($filesToExtract['input_files']) + count($filesToExtract['output_files']);
   }

    /**
     * Extracts and saves tests
     *
     * @param Task $task
     * @param string $pathToTestFolder The path to the folder where the tests should be saved.
     * @param array $inputFiles An array of input tests
     * @param array $outputFiles An array of output tests
     * @throws TestUploaderException
     */
   private function extractAndSaveTests(Task $task, string $pathToTestFolder, array $inputFiles, array $outputFiles): void
   {
      for ($i = 0; $i < count($inputFiles); $i++) {
         $this->saveTests($task, $pathToTestFolder, $inputFiles[$i], $inputFiles[$i]);
         $this->extractFilesTo([$inputFiles[$i], $outputFiles[$i]], $pathToTestFolder);
      }
   }

    /**
     *  Save tests to the database.
     *
     * @param Task $task The task object
     * @param string $path Tests folders
     * @param string $inputFile The name of the input file
     * @param string $outputFile The name of the output file
     * @throws TestUploaderException
     */
   private function saveTests(Task $task, string $path, string $inputFile, string $outputFile): void
   {

      // check if task with this input DataExists exists in Database
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

      // Save file to Database
      try {
         $this->taskTestRepository->save($taskTest, true);
      } catch (Exception) {
         throw new TestUploaderException(self::ERR_SAVE_TO_DB);
      }
   }

    /**
     * Extract files to a specified path.
     *
     * @param string|array $file The file or array of files to extract.
     * @param string $path The path to extract the files to.
     *
     * @return bool True if the extraction was successful, false otherwise.
     * @throws TestUploaderException
     */
   private function extractFilesTo(string|array $file, string $path): bool
   {
      try {
         return $this->zip->extractTo($path, $file);
      } catch (Exception $exception) {
         throw new TestUploaderException(self::ERR_EXTRACT);
      }
   }

   /**
    * Get a file pair from the zip archive
    *
    * @param string $fileIdentifier    The identifier of the file to get the pair for (e.g. 1, 2, "a")
    * @param string $pairFilePattern   The pattern of the file pair to look for (e.g. [id]_output.txt)
    *
    * @return string|false  The name of the file pair, or false if not found.
    */
   private function getFilePair(string $fileIdentifier, string $pairFilePattern): string|false
   {
      $pairFileName = str_replace($this->getFileIdentifierPattern(), $fileIdentifier, $pairFilePattern);
      return $this->zip->locateName($pairFileName) === false ? false : $pairFileName;
   }

   /**
    * Create a folder for tests for a given task
    *
    * @param   int      $taskId The ID of the task
    * @param   string   $path The path to the `data` folder
    * @return  string The path of the created test folder.
    *
    * @throws TestUploaderException If the directory cannot be created
    */
   private function createTestFolder(int $taskId, string $path): string
   {
      $testFolderPath = $path . '/' . $taskId . '/tests/';

      // throw new Exception($fullPath);
      if (!$this->filesystem->exists($testFolderPath)) {
         try {
            $this->filesystem->mkdir($testFolderPath);
         } catch (Exception) {
            throw new TestUploaderException("Cannot create directory: " . $testFolderPath);
         }
      }

      return $testFolderPath;
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
}
