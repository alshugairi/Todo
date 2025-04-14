<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoEmailOrPhone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check for email pattern
        $emailPattern = '/^(?!.*@)/';

        // Check for phone number pattern (3 or more consecutive digits)
        $phonePattern = '/^(?!.*\d{3})/';

        return preg_match($emailPattern, $value) && preg_match($phonePattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.no_email_or_phone');
    }
}
