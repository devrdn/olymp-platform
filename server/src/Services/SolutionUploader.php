<?php

namespace App\Services;

use App\Config\AllowedExtesions;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\UserSolution;
use App\Repository\UserSolutionRepository;
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
   public function __construct(
      private UserSolutionRepository $userSolutionRepository
   ) {
      parent::__construct();
   }

   /**
    * Upload User solution as File
    *
    * @param UploadedFile $uploadedFile
    * @param string $solutionId
    * @param string $targetDirectory
    *
    * @return string solution file name
    */
   public function uploadSolutionAsFile(UploadedFile $uploadedFile, string $solutionId,  string $targetDirectory): string
   {
      $this->setTargetDirectory($targetDirectory);

      // check if user file extension matches selected extension
      $fileExtension = $uploadedFile->getClientOriginalExtension();

      // create new file name task_{task_id}_{data}.{ext}
      $newFileName = self::createFileName("task", $solutionId, $fileExtension);

      // upload user solution to `data` folder
      $this->uploadFile($uploadedFile, $newFileName);

      return $newFileName;
   }

   /**
    * Upload User solution as Text
    *
    * @param string $solutionAsText
    * @param string $solutionId
    * @param string $targetDirectory
    * @param AllowedExtesions $solutionExtesion
    *
    * @return string solution file name
    */
   public function uploadSolutionAsText(string $solutionAsText, string $solutionId, string $targetDirectory, AllowedExtesions $solutionExtesion): string
   {
      $this->setTargetDirectory($targetDirectory);

      // create new file name task_{task_id}_{data}.{ext}
      $newFileName = self::createFileName("task", $solutionId, $solutionExtesion->value);

      // create file from text and upload solution
      $this->createAndUploadFile($solutionAsText, $newFileName);

      return $newFileName;
   }
}
