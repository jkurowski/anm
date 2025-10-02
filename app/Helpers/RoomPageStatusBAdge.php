<?php

if (! function_exists('roomPageStatusBadge')) {
    function roomPageStatusBadge(int $number)
    {
        switch ($number) {
            case '1':
                return '<span class="text-success subtitle-1">DOSTĘPNE</span>';
            case '2':
                return '<span class="text-warning subtitle-1">REZERWACJA</span>';
            case '3':
                return '<span class="text-danger subtitle-1">SPRZEDANE</span>';
            case '4':
                return '<span class="text-warning subtitle-1">REZERWACJA USTNA</span>';
        }
    }
}
