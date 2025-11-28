<?php

namespace App\Services\DiscountService;

use App\Models\Customer;

class DiscountService
{
    public function email(Customer $customer, int $dicount): void
    {
        // Logic to email the customer about the discount
    }
}
