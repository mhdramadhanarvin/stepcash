<?php

namespace App\Enums;

enum NotificationEnum: string
{
    case NEW_EXCHANGE = "Penukaran Baru";
    case EXCHANGE_ON_PROGRESS = "Penukaran Sedang Diproses";
    case EXCHANGE_READY_TO_PICKUP = "Penukaran Siap Diambil";
    case EXCHANGE_CANCELED = "Penukaran Dibatalkan";
    case EXCHANGE_DONE = "Penukaran Selesai";
    case COIN_CONVERT = "Konversi Langkah Harian";
    case TARGET_NOT_ACHIEVED = "Target Belum Tercapai";

    public static function getValue(string $name): string
    {
        foreach (self::cases() as $enum) {
            if ($name === $enum->name) {
                return $enum->value;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
    }
}
