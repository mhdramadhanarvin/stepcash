<?php

namespace App\Enums;

enum NotificationEnum: string
{
    case NEW_EXCHANGE = "Penukaran Baru";
    case EXCHANGE_ON_PROGRESS = "Penukaran Sedang Diproses";
    case EXCHANGE_READY_TO_PICKUP = "Penukaran Siap Diambil";
    case EXCHANGE_CANCELED = "Penukaran Dibatalkan";
    case COIN_CONVERT = "Konversi Langkah Harian";
}
