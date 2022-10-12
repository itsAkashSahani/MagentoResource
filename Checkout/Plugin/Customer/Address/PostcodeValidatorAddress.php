<?php

namespace Albatool\Checkout\Plugin\Customer\Address;

class PostcodeValidatorAddress
{
    public function afterValidateValue($subject)
    {
        return true;
    }

}