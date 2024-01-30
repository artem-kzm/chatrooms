<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rooms\CreateRoomRequest;
use App\Http\Requests\Rooms\UpdateRoomRequest;
use App\Models\Account;
use App\Models\Room;
use App\Services\Rooms\RoomCreationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct(
        public RoomCreationService $roomCreationService
    ) {}

    public function getRooms(): Http\Response
    {
        /** @var Account $account */
        $account = Auth::user();

        return response($account->rooms);
    }

    public function createRoom(CreateRoomRequest $createRoomRequest): Http\Response
    {
        /** @var Account $account */
        $account = Auth::user();

        $room = $this->roomCreationService->createRoom($createRoomRequest, $account);

        return response($room);
    }

    /**
     * @throws AuthorizationException
     */
    public function getRoom(Room $room): Http\Response
    {
        /** @var Account $account */
        $account = Auth::user();

        if (!$room->belongsToAccount($account)) {
            throw new AuthorizationException();
        }

        return response($room);
    }

    /**
     * @throws AuthorizationException
     */
    public function deleteRoom(Room $room): Http\Response
    {
        /** @var Account $account */
        $account = Auth::user();

        if (!$room->belongsToAccount($account)) {
            throw new AuthorizationException();
        }

        $room->delete();

        return response($room);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateRoom(UpdateRoomRequest $request, Room $room): Http\Response
    {
        /** @var Account $account */
        $account = Auth::user();

        if (!$room->belongsToAccount($account)) {
            throw new AuthorizationException();
        }

        $room->update($request->all());

        return response($room);
    }
}
