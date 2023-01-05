<?php

namespace App\Services;

use Exception;
use ZipArchive;

/**
 * Service responsable for check zip files and extract to certain directory
 */
class ZipManager
{
   /**
    * @var string Path to zip archive
    */
   private string $archive;

   /**
    * @var ZipArchive $zip
    */
   private ZipArchive $zip;



   /**
    * @var string Openning file error
    */
   private const ERR_OPEN = "Could not open zip archive";

   /**
    * @var string Invalid file name error
    */
   private const ERR_INVALID_NAME = "Files name don't match patterns";

   /**
    * @var string Missing File Pair Error
    */
   private const ERR_NO_COUPLE = "Some files doesn't have couple";



   public function __construct(string $archive)
   {
      $this->archive = $archive;
      $this->zip = new ZipArchive();
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
   public function checkZip(string $input, string $output, string $fileIdintifierPattern, bool $hasOutput = true): string|bool
   {
      // Open Zip Archive
      if ($this->zip->open($this->archive) !== true) {
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

      return false;
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
   private function fileHasCouple(string $fileIdintifier, string $fileIdintifierPattern, string $coupleName)
   {
      $coupleFileName = preg_replace($fileIdintifierPattern, $fileIdintifier, $coupleName);
      return $this->zip->locateName($coupleFileName);
   }

   /**
    * Extract files to directory
    *
    * @param string $path Directory path
    */
   public function extractTo(string $path)
   {
      $this->zip->extractTo($path);
   }
}
