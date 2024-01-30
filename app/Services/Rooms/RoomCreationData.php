<?php

namespace App\Services\Rooms;

interface RoomCreationData
{
    public function getName(): string;
    public function getFriendlyUrl(): string;
    public function getMaxDuration(): int;
}
