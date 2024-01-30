<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateRoomTest extends TestCase
{
    use RefreshDatabase;

    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = Account::factory()->create();
    }

    public function test_assert_update_room_route_is_protected(): void
    {
        $response = $this->postJson('rooms/1');
        $response->assertUnauthorized();
    }

    public function test_update_nonexistent_room(): void
    {
        $response = $this->postJsonAs('rooms/1', $this->account);
        $response->assertNotFound();
    }

    public function test_update_a_room_of_another_account(): void
    {
        $anotherAccount = Account::factory()->create();
        $room = Room::factory()->for($anotherAccount)->create();

        $updateData = [
            'name' => 'name',
            'friendly_url' => 'friendly_url',
            'max_duration' => 45,
        ];

        $response = $this->postJsonAs("rooms/{$room->id}", $this->account, $updateData);
        $response->assertForbidden();
    }

    public function test_update_a_room_with_wrong_field_formats(): void
    {
        $room = Room::factory()->for($this->account)->create();

        $response = $this->postJsonAs(
            "/rooms/{$room->id}",
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

    public function test_update_a_room_with_invalid_field_types(): void
    {
        $room = Room::factory()->for($this->account)->create();

        $response = $this->postJsonAs(
            "/rooms/{$room->id}",
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

    public function test_update_a_room_with_too_long_fields(): void
    {
        $room = Room::factory()->for($this->account)->create();

        $longString = str_repeat('a', 256);
        $bigInteger = 4294967296;

        $response = $this->postJsonAs(
            "/rooms/{$room->id}",
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

    public function test_update_a_room(): void
    {
        $room = Room::factory()->for($this->account)->create();

        $name = 'cool room';
        $friendlyUrl = 'friendly_url';
        $maxDuration = 285;

        $response = $this->postJsonAs(
            "/rooms/{$room->id}",
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

        $this->assertDatabaseMissing('rooms', [
            'name' => $room->name,
            'friendly_url' => $room->friendly_url,
            'max_duration' => $room->max_duration,
            'account_id' => $this->account->id
        ]);
    }
}
