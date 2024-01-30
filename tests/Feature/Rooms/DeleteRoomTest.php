<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRoomTest extends TestCase
{
    use RefreshDatabase;

    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = Account::factory()->create();
    }

    public function test_assert_delete_a_room_route_is_protected(): void
    {
        $response = $this->deleteJson('rooms/1');
        $response->assertUnauthorized();
    }

    public function test_delete_nonexistent_room(): void
    {
        $response = $this->deleteJsonAs('rooms/1', $this->account);
        $response->assertNotFound();
    }

    public function test_delete_a_room_of_another_account(): void
    {
        $anotherAccount = Account::factory()->create();
        $room = Room::factory()->for($anotherAccount)->create();

        $response = $this->deleteJsonAs("rooms/{$room->id}", $this->account);
        $response->assertForbidden();
    }

    public function test_delete_a_room(): void
    {
        $room = Room::factory()->for($this->account)->create();

        $response = $this->deleteJsonAs("rooms/{$room->id}", $this->account);
        $response->assertOk();

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }
}
