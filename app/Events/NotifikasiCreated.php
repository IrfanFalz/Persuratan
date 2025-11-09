<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Notifikasi;

class NotifikasiCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $notifikasi;

    public function __construct(Notifikasi $notifikasi)
    {
        $this->notifikasi = $notifikasi;
    }

    public function broadcastOn()
    {
        return new Channel('notifikasi');
    }

    public function broadcastAs()
    {
        return 'NotifikasiCreated';
    }
}
