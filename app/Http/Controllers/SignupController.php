<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Services\Accounts\AccountCreationService;
use Illuminate\Http;

class SignupController extends Controller
{
    public function __construct(
       private AccountCreationService $accountCreationService
    ) {}

    public function signup(SignupRequest $request): Http\Response
    {
        $account = $this->accountCreationService->createAccount(
            $request->getEmail(),
            $request->getName()
        );

        return response($account);
    }
}
