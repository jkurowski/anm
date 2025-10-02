<?php

if (! function_exists('roomStatusBadge')) {
    function roomStatusBadge(int $number)
    {
        switch ($number) {
            case '1':
                return '<span class="text-success text-uppercase">DOSTĘPNE</span>';
            case '2':
                return '<span class="text-warning text-uppercase">REZERWACJA</span>';
            case '3':
                return '<span class="text-danger text-uppercase">SPRZEDANE</span>';
            case '4':
                return '<span class="text-warning text-uppercase">REZERWACJA USTNA</span>';
        }
    }
}
