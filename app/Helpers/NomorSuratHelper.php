<?php

namespace App\Helpers;

use App\Models\Surat;
use App\Models\Provinsi;
use App\Models\DinasSurat;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class NomorSuratHelper
{
    /**
     * Generate and persist nomor_urut and nomor_surat for a Surat model.
     * If surat already has nomor_surat, the function does nothing.
     */
    public static function generate(Surat $surat)
    {
        if (!$surat) return null;

        if (!empty($surat->nomor_surat)) {
            return $surat->nomor_surat;
        }

        $year = now()->year;

        // Determine jenis code from config mapping (fallback to raw id)
        $jenisId = $surat->id_jenis_surat;
        $jenisCodes = Config::get('nomor_surat.jenis_codes', []);
        $kodeJenis = isset($jenisCodes[$jenisId]) ? $jenisCodes[$jenisId] : ($jenisId ? (string)$jenisId : '');

        // Regional codes: prefer lookup table kode fields when FK present, otherwise from config
        $provinsiCode = null;
        if (! empty($surat->id_provinsi)) {
            $provinsiCode = Provinsi::where('id', $surat->id_provinsi)->value('kode_provinsi');
        }
        if (empty($provinsiCode)) {
            $provinsiCode = Config::get('nomor_surat.provinsi_code');
        }

        $dinasCode = null;
        if (! empty($surat->id_dinas)) {
            $dinasCode = DinasSurat::where('id', $surat->id_dinas)->value('kode_dinas');
        }
        if (empty($dinasCode)) {
            $dinasCode = Config::get('nomor_surat.dinas_code');
        }

        $sekolahCode = null;
        if (! empty($surat->id_sekolah)) {
            $sekolahCode = Sekolah::where('id', $surat->id_sekolah)->value('kode_sekolah');
        }
        if (empty($sekolahCode)) {
            $sekolahCode = Config::get('nomor_surat.sekolah_code');
        }

        // Determine next nomor_urut for this jenis in the current year
        $last = Surat::where('id_jenis_surat', $jenisId)
            ->whereYear('dibuat_pada', $year)
            ->orderByDesc('nomor_urut')
            ->first();

        $next = ($last && $last->nomor_urut) ? ($last->nomor_urut + 1) : 1;

        $digits = Config::get('nomor_surat.sequence_digits', 4);
        $nomorUrutFormatted = str_pad($next, $digits, '0', STR_PAD_LEFT);

        // Build the nomor_surat string: {kodeJenis} / {nomorUrut} / {provinsi}.{dinas}.{sekolah} / {year}
        $regionalPart = implode('.', array_filter([(string)$provinsiCode, (string)$dinasCode, (string)$sekolahCode]));

        $parts = array_filter([$kodeJenis, $nomorUrutFormatted, $regionalPart, (string)$year]);
        $nomor = implode(' / ', $parts);

        // Persist to model
        $surat->nomor_urut = $next;
        $surat->nomor_surat = $nomor;
        $surat->save();

        return $nomor;
    }
}
