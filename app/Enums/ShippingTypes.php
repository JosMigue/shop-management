<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class ShippingTypes extends Enum implements LocalizedEnum
{
    const Pending =  0;
    const Shipped =  1;
}
