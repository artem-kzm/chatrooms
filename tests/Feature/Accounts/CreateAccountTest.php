<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_an_account_with_no_data(): void
    {
        $response = $this->postJson('/signup');

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
            'name' => 'The name field is required.'
        ]);
    }

    public function test_create_an_account_with_invalid_email_format(): void
    {
        $invalidEmails = [
            'invalid@email;',
            'invalid',
            'invalid@@mail.com',
            'invalid@.mail.com',
            ':invalid@sdf.fm'
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $response = $this->postJson('/signup', ['email' => $invalidEmail]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['email' => 'The email must be a valid email address.']);
        }
    }

    public function test_create_an_account_with_too_long_email(): void
    {
        $longEmail = 'test@' . str_repeat('a', 247) . '.com';

        $response = $this->postJson('/signup', ['email' => $longEmail]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email' => 'The email must not be greater than 255 characters.']);
    }

    public function test_create_an_account_with_existent_email(): void
    {
        $existentEmail = 'existent@email.com';

        DB::table('accounts')->insert([
            'email' => $existentEmail,
            'name' => 'test',
            'developer_key' => 'test'
        ]);

        $response = $this->postJson('/signup', ['email' => $existentEmail]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email' => 'The email has already been taken.']);

        $count = DB::table('accounts')->where('email', $existentEmail)->count();
        self::assertSame(1, $count);
    }

    public function test_create_an_account_with_wrong_field_formats(): void
    {
        $data = [
            'email' => ['test@email.com'],
            'name' => ['test@email.com']
        ];
        $response = $this->postJson('/signup', $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'email' => 'The email must be a valid email address.',
            'name' => 'The name must be a string.'
        ]);
    }

    public function test_create_an_account_with_wrong_name_type(): void
    {
        $response = $this->postJson('/signup', ['name' => 123]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name' => 'The name must be a string.']);
    }

    public function test_create_an_account_with_too_long_name(): void
    {
        $name = str_repeat('a', 256);

        $response = $this->postJson('/signup', ['name' => $name]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name' => 'The name must not be greater than 255 characters.']);
    }

    public function test_create_an_account(): void
    {
        $email = 'test@email.com';
        $name = 'name';
        $requestData = [
            'email' => $email,
            'name' => $name,
        ];

        $response = $this->postJson('/signup', $requestData);
        $response->assertOk();

        $account = DB::table('accounts')
            ->where('email', $email)
            ->where('name', $name)
            ->first();

        self::assertNotNull($account);
        self::assertSame(strlen($account->developer_key), 64);

        $response->assertJsonFragment($requestData);
    }
}
