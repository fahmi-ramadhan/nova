<?php

namespace App\Services\DiscountService;

use App\Models\Customer;

class DiscountService
{
    public function email(Customer $customer, int $discount): void
    {
        logger('Sending discount email to ' . $customer->email . ' with ' . $discount . '% discount…');
        sleep(1);
        logger('Discount email sent!');
    }
}
