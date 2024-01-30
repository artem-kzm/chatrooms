<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRoomTest extends TestCase
{
    use RefreshDatabase;

    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = Account::factory()->create();
    }

    public function test_assert_create_room_route_is_protected(): void
    {
        $response = $this->postJson('rooms');
        $response->assertUnauthorized();
    }

    public function test_create_a_room_with_no_data(): void
    {
        $response = $this->postJsonAs('/rooms', $this->account);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'friendly_url' => 'The friendly url field is required.',
            'max_duration' => 'The max duration field is required.'
        ]);
    }

    public function test_create_a_room_with_wrong_field_formats(): void
    {
        $response = $this->postJsonAs(
            '/rooms',
            $this->account,
            [
                'name' => ['name'],
                'friendly_url' => ['friendly_url'],
                'max_duration' => ['max_duration'],
            ]
        );

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'name' => 'The name must be a string.',
            'friendly_url' => 'The friendly url must be a string.',
            'max_duration' => 'The max duration must be an integer.'
        ]);
    }

    public function test_create_a_room_with_invalid_field_types(): void
    {
        $response = $this->postJsonAs(
            '/rooms',
            $this->account,
            [
                'name' => 123,
                'friendly_url' => 123,
                'max_duration' => 'duration',
            ]
        );

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'name' => 'The name must be a string.',
            'friendly_url' => 'The friendly url must be a string.',
            'max_duration' => 'The max duration must be an integer.'
        ]);
    }

    public function test_create_a_room_with_too_long_fields(): void
    {
        $longString = str_repeat('a', 256);
        $bigInteger = 4294967296;

        $response = $this->postJsonAs(
            '/rooms',
            $this->account,
            [
                'name' => $longString,
                'friendly_url' => $longString,
                'max_duration' => $bigInteger,
            ]
        );

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'name' => 'The name must not be greater than 255 characters.',
            'friendly_url' => 'The friendly url must not be greater than 255 characters.',
            'max_duration' => 'The max duration must not be greater than 4294967295.'
        ]);
    }

    public function test_create_an_account(): void
    {
        $name = 'cool room';
        $friendlyUrl = 'friendly_url';
        $maxDuration = 285;

        $response = $this->postJsonAs(
            '/rooms',
            $this->account,
            [
                'name' => $name,
                'friendly_url' => $friendlyUrl,
                'max_duration' => $maxDuration,
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('rooms', [
            'name' => $name,
            'friendly_url' => $friendlyUrl,
            'max_duration' => $maxDuration,
            'account_id' => $this->account->id
        ]);
    }
}
