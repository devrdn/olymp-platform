<?php

namespace App\Services;

use App\Config\AllowedExtesions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Service responsible for uploading user solutions
 * 
 * User can upload solution in two formats:
 *    1. As File
 *    2. As Text
 */
class SolutionUploader extends FileUploader 
{
   /**
    * Upload User solution as File
    *
    * @param UploadedFile $uploadedFile
    * @param string $solutionId
    * @param string $targetDirectory
    *
    * @return void
    */
   public function uploadSolutionAsFile(UploadedFile $uploadedFile, string $solutionId,  string $targetDirectory): void {
      $this->setTargetDirectory($targetDirectory);

      // check if user file extension matches selected extension
      $fileExtension = $uploadedFile->getClientOriginalExtension();

      // create new file name task_{task_id}_{data}.{ext}
      $newFileName = self::createFileUniqueNameByDate("task", $solutionId, $fileExtension);

      // upload user solution to `data` folder
      $this->uploadFile($uploadedFile, $newFileName);

      return;
   }

   /**
    * Upload User solution as Text
    *
    * @param string $solutionAsText
    * @param string $solutionId
    * @param string $targetDirectory
    * @param AllowedExtesions $solutionExtesion
    *
    * @return void
    */
   public function uploadSolutionAsText(string $solutionAsText, string $solutionId, string $targetDirectory, AllowedExtesions $solutionExtesion)
   {
      $this->setTargetDirectory($targetDirectory);

      // create new file name task_{task_id}_{data}.{ext}
      $newFileName = self::createFileUniqueNameByDate("task", $solutionId, $solutionExtesion->value);
      
      // create file from text and upload solution
      $this->createAndUploadFile($solutionAsText, $newFileName);

      return;
   }
}