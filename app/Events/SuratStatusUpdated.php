<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Surat;

class SuratStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $surat;

    public function __construct(Surat $surat)
    {
        $this->surat = $surat;
    }

    public function broadcastOn()
    {
        return new Channel('surat');
    }

    public function broadcastAs()
    {
        return 'SuratStatusUpdated';
    }
}
