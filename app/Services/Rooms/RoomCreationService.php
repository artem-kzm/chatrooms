<?php

namespace App\Services\Rooms;

use App\Models\Account;
use App\Models\Room;

class RoomCreationService
{
    public function createRoom(
        RoomCreationData $data,
        Account $forAccount
    ): Room {
        $room = new Room();
        $room->name = $data->getName();
        $room->friendly_url = $data->getFriendlyUrl();
        $room->max_duration = $data->getMaxDuration();
        $room->account_id = $forAccount->id;
        $room->created_at = now();
        $room->save();

        return $room;
    }
}
