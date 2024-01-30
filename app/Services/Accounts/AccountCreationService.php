<?php

namespace App\Services\Accounts;

use App\Models\Account;

class AccountCreationService
{
    public function createAccount(string $email, string $name): Account
    {
        $account = new Account();
        $account->email = $email;
        $account->name = $name;
        $account->generateAndSetDeveloperKey();
        $account->save();

        return $account;
    }
}
