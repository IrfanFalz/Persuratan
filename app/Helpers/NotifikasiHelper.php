<?php

namespace App\Helpers;

use App\Models\Notifikasi;
use Carbon\Carbon;

class NotifikasiHelper
{
    public static function insert($idSurat, $idPengguna, $pesan, $status = null)
    {
        return Notifikasi::create([
            'id_surat'    => $idSurat,
            'id_pengguna' => $idPengguna,
            'pesan'       => $pesan,
            'status'      => $status,
            'created_at'  => Carbon::now(),
            'dibaca'      => null,
        ]);
    }

    public static function markAsRead($idNotif)
    {
        $n = Notifikasi::findOrFail($idNotif);
        $n->dibaca = Carbon::now();
        $n->save();
        return $n;
    }

    public static function unreadCount($idPengguna)
    {
        return Notifikasi::where('id_pengguna', $idPengguna)
            ->whereNull('dibaca')
            ->count();
    }

    public static function forUser($idPengguna, $limit = null)
    {
        $q = Notifikasi::where('id_pengguna', $idPengguna)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $q->limit($limit);
        }

        return $q->get();
    }
}
