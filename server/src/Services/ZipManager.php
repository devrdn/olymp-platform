<?php

namespace App\Services;

use Exception;
use ZipArchive;

/**
 * Service responsable for check zip files and extract to certain directory
 */
class ZipManager
{

   private ZipArchive $zip;

   /**
    * @var string Openning file error
    */
   private const ERR_OPEN = "Zip Archive is Not openned";

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
    * @param ZipArchive $zip Zip Archive
    *
    * @return bool if Zip archive openned
    */
   private function isZipOpenned(ZipArchive $zip): bool
   {
      return !empty($zip->filename);
   }

   /**
    * Check if files in the zip archive match the correct names
    *
    * @param string $input Input file name
    * @param string $output Output file name
    * @param string $fileIdintifierPattern Regular expression for file identifier (e.g. `/\[id\]/`);
    *
    * @return string|bool Error or `TRUE` if there are no errors
    */
   public function isAllFilesCorrect(ZipArchive $zip, string $input, string $output, string $fileIdintifierPattern, bool $hasOutput = true): string|bool
   {

      if (!$this->isZipOpenned($zip)) {
         return self::ERR_OPEN;
      }

      // check if input and output has identifier
      $fileHasIdintifier = boolval(preg_match($fileIdintifierPattern, $input) && preg_match($fileIdintifierPattern, $output));

      // create regex patterns from input and output
      $inputPattern = '/' . preg_replace($fileIdintifierPattern, "(.+)", $input) . '/';
      $outputPattern = '/' . preg_replace($fileIdintifierPattern, "(.+)", $output) . '/';


      // check if all files in archive is valid
      for ($i = 0; $i < $zip->numFiles; $i++) {
         // get file name by index
         $fileName = $zip->getNameIndex($i);
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
         $hasCouple = $this->fileHasCouple($zip, $matches[1], $fileIdintifierPattern, $isValidInput ? $output : $input);
         if ($hasCouple === false) {
            return self::ERR_NO_COUPLE;
            break;
         }
      }

      return true;
   }

   /**
    * Check if File has couple in zip archive
    *
    * @param string $fileIdintifierPattern Identifier of the file (e.g. 1, 2, "a")
    * @param string $coupleName Template of couple name (e.g. [id]_input.txt)
    * @param ZipArchive $zip ZipArchive
    *
    * @return int|false Index of the couple file in the archive or FALSE if doesn't exist
    */
   private function fileHasCouple(ZipArchive $zip, string $fileIdintifier, string $fileIdintifierPattern, string $coupleName)
   {
      $coupleFileName = preg_replace($fileIdintifierPattern, $fileIdintifier, $coupleName);
      return $zip->locateName($coupleFileName);
   }
}
