<?php

namespace App\Config;

enum AllowedExtensions: string
{
   case Python = "py";
   case CPP = 'cpp';
   case Java = 'java';
   case PHP = 'php';
}
