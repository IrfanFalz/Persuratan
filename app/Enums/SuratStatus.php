<?php
namespace App\Enums;

enum SuratStatus: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case DONE = 'done';
}
