<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetRoomsTest extends TestCase
{
    use RefreshDatabase;

    public function test_assert_rooms_route_is_protected(): void
    {
        $response = $this->getJson('rooms');
        $response->assertUnauthorized();
    }

    public function test_get_account_rooms(): void
    {
        $account = Account::factory()->create();
        $anotherAccount = Account::factory()->create();

        $rooms = Room::factory()->count(3)->for($account)->create();
        $rooms->each(static function (Room $room) {
            $room->refresh();
        });

        Room::factory()->count(3)->for($anotherAccount)->create();

        $response = $this->getJsonAs('rooms', $account);
        $response->assertOk();
        $response->assertExactJson($rooms->toArray());
    }
}
