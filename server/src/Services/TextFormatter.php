<?php

namespace App\Services;

/**
 * Text Responsa
 */
class TextFormatter
{

   /**
    * Entries to be changed in the source string
    */
   private const CHANGE_ENTRIES = [
      "<br>" => "\n",
      "&nbsp; &nbsp;" => "\t",
      "&nbsp;" => " ",
      "</p>" => "\n",
      "<p>" => "",
      "&lt;" => "<",
      "&gt;" => ">"
   ];

   /**
    * Format string from HTML to Raw Text
    * 
    * @param string $content
    *
    * @return string Formatted String
    */
   public function fromHtmlToText(string $content): string
   {
      // changle all occures
      foreach (self::CHANGE_ENTRIES as $key => $value) {
         $content = str_replace($key, $value, $content);
      }

      return $content;
   }
}
