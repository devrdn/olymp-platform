<?php

namespace App\EventSubscriber;

use App\Constants\MessageConstants;
use Doctrine\Common\Collections\ArrayCollection;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OnlyOneFieldFilledSubscriber implements EventSubscriberInterface
{

   /**
    * Fields that must be checked
    *
    * @var ArrayCollection
    */
   private ArrayCollection $fieldsToCheck;

   public function __construct(array $fields)
   {
      $this->fieldsToCheck = new ArrayCollection();
      foreach ($fields as $field) {
         $this->fieldsToCheck->add($field);
      }
   }

   public static function getSubscribedEvents(): array
   {
      // return the subscribed events, their methods and priorities
      return [
         FormEvents::SUBMIT => 'handleOnSubmit',
      ];
   }

   /**
    * Handle Form Submution on `SUBMIT` Event
    *
    * If count of filled fields isn't equal 1, then add a form error
    *
    * @param FormEvent $event
    * @return void
    */
   public function handleOnSubmit(FormEvent $event): void
   {
      $formData = $event->getData();

      $fillFields = [];
      foreach ($this->fieldsToCheck as $fieldToCheck) {
         if (isset($formData[$fieldToCheck])) {
            $fillFields[] = $fieldToCheck;
         }
      }

      $this->addFormError($event, count($fillFields));
   }

   /**
    * Adds errors to the form depending on the filled fields
    *
    * @param FormEvent $event
    * @param int $countOfFillFields Count of form fields that are filled 
    * @return void
    */
   public function addFormError(FormEvent $event, int $countOfFillFields): void
   {
      if ($countOfFillFields == 0 || $countOfFillFields > 1) {
         $event->getForm()->addError(
            new FormError(MessageConstants::ONLY_ONE_FIELD_FILLED)
         );
      }
   }
}
