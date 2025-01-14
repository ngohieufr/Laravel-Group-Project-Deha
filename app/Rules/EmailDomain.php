<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailDomain implements Rule
{
    protected $allowedDomain = 'deha-soft.com';

    /**
     * Xác định rule này có pass qua validation hay không.
     */
    public function passes($attribute, $value)
    {
        return str_ends_with($value, '@' . $this->allowedDomain);
    }

    /**
     * Trả về message lỗi khi validation không pass.
     */
    public function message()
    {
        return 'The :attribute must be an email from the domain @deha-soft.com.';
    }
}
