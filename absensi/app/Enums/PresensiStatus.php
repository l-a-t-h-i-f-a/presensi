<?php

namespace App\Enums;

enum PresensiStatus: string
{
    case Hadir = 'hadir';
    case Terlambat = 'terlambat';
    case Izin = 'izin';
    case Sakit = 'sakit';
    case TidakHadir = 'tidak_hadir';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
