<?php

namespace App\Enums;

/**
 * @method static self Pending()
 * @method static self Active()
 * @method static self Ended()
 */
enum ContestStatus: string
{
   case Pending = 'pending';
   case Active = 'active';
   case Ended = 'ended';
}
