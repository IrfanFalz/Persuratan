<?php

namespace App\Events;

use App\Models\Surat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class SuratStatusUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $surat;

    public function __construct(Surat $surat)
    {
        $this->surat = $surat;
    }

    public function broadcastOn()
    {
        // Channel publik, bisa diganti ke private kalau perlu otentikasi
        return new Channel('surat.status');
    }

    public function broadcastAs()
    {
        return 'SuratStatusUpdated';
    }

    public function broadcastWith()
    {
        return [
            'id_surat' => $this->surat->id_surat,
            'status_berkas' => $this->surat->status_berkas,
        ];
    }
}
