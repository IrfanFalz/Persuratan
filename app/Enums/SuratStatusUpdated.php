<?php
namespace App\Events;

use App\Models\Surat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class SuratStatusUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public Surat $surat;

    public function __construct(Surat $surat)
    {
        $this->surat = $surat;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->surat->id_pengguna);
    }

    public function broadcastWith()
    {
        return [
            'id_surat' => $this->surat->id_surat,
            'status' => is_string($this->surat->status) ? $this->surat->status : $this->surat->status->value,
            'judul' => $this->surat->judul,
        ];
    }

    public function broadcastAs()
    {
        return 'SuratStatusUpdated';
    }
}
