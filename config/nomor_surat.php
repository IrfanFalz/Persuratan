<?php

return [
    // Map id_jenis_surat to its printed code prefix (example: 1 => '421.7')
    'jenis_codes' => [
        1 => '421.7', // dispensasi
        2 => '895.7', // perintah tugas
    ],

    // Default regional codes (you can override these as needed)
    'provinsi_code' => '101.6',
    'dinas_code'    => '10',
    'sekolah_code'  => '14',

    // Number of digits for the running sequence (e.g. 4 => 0001)
    'sequence_digits' => 4,
];
