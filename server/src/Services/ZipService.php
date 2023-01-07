<?php

namespace App\Services;

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
   private const ERR_OPEN = "Zip archive is not open";

   /**
    * @var string Invalid file name error
    */
   private const ERR_INVALID_NAME = "Files name don't match patterns";

   /**
    * @var string Missing File Pair Error
    */
   private const ERR_NO_COUPLE = "Some files doesn't have couple";


   public function __construct()
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
   public function openZip(string $archive): string|bool
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
    * Check if all files files and archive has correct name and
    * all files has couple (e.g. `1_input.txt` -> `1_output.txt`)
    *
    * @param string $input Input file name
    * @param string $output Output file name
    * @param string $fileIdintifierPattern Regular expression for file identifier (e.g. `/\[id\]/`);
    * @param bool $hasOutput If there are output files
    *
    * @return string|bool Error or `TRUE` if there are no errors
    */
   public function isAllFilesHasCouple(string $input, string $output, string $fileIdintifierPattern, bool $hasOutput = true): string|bool
   {
      if (!$this->isZipOpenned()) {
         return self::ERR_OPEN;
      }

      // check if input and output has identifier
      $fileHasIdintifier = boolval(preg_match($fileIdintifierPattern, $input) && preg_match($fileIdintifierPattern, $output));

      // create regex patterns from input and output
      $inputPattern = '/' . preg_replace($fileIdintifierPattern, "(.+)", $input) . '/';
      $outputPattern = '/' . preg_replace($fileIdintifierPattern, "(.+)", $output) . '/';


      // check if all files in archive is valid
      for ($i = 0; $i < $this->zip->numFiles; $i++) {
         // get file name by index
         $fileName = $this->zip->getNameIndex($i);
         $isValidInput = preg_match($inputPattern, $fileName, $matches);
         $isValidOutput = $isValidInput || preg_match($outputPattern, $fileName, $matches);

         // if file name is invalid
         if (!($isValidInput || $isValidOutput)) {
            return self::ERR_INVALID_NAME;
            break;
         }

         // if task doesn't have output or doesn't have idintifier then continue
         if (!($hasOutput && $fileHasIdintifier)) {
            continue;
         }

         // check if file has couple
         $hasCouple = $this->fileHasCouple($matches[1], $fileIdintifierPattern, $isValidInput ? $output : $input);
         if ($hasCouple === false) {
            return self::ERR_NO_COUPLE;
            break;
         }
      }

      return true;
   }


   /**
    * Check File has couple in archive
    *
    * @param string $fileIdintifierPattern Identifier of the file (e.g. 1, 2, "a")
    * @param string $coupleName Template of couple name (e.g. [id]_input.txt)
    *
    * @return int|false Index of the couple file in the archive or FALSE if doesn't exist
    */
   private function fileHasCouple(string $fileIdintifier, string $fileIdintifierPattern, string $coupleName)
   {
      $coupleFileName = preg_replace($fileIdintifierPattern, $fileIdintifier, $coupleName);
      return $this->zip->locateName($coupleFileName);
   }

   /**
    * Extract files to path
    *
    * @param string $path Path to extract
    * @param bool $close Should the archive be closed after extraction
    *
    * @return bool TRUE on success or FALSE on failure
    */
   public function extractTo(string $path, bool $close): bool
   {
      $isSuccess = $this->zip->extractTo($path);
      if ($close) {
         $this->zip->close();
      }

      return $isSuccess;
   }
}
