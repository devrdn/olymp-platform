<?php

namespace App\Config;

enum AllowedExtesions: string
{
   case Python = "py";
   case CPP = 'cpp';
   case Java = 'java';
   case PHP = 'php';
}
