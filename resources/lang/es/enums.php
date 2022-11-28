<?php

use App\Enums\ShippingTypes;

return [

    ShippingTypes::class => [
        ShippingTypes::Pending => 'Pendiente',
        ShippingTypes::Shipped => 'Envíado'
    ],

];