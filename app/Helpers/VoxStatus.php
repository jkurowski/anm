<?php

if (! function_exists('voxStatus')) {
    function voxStatus(int $number)
    {
        switch ($number) {
            case '1':
                return 'Dostępne';
            case '2':
                return 'Rezerwacja ustna';
            case '3':
                return 'Umowa rezerwacyjna';
            case '4':
                return 'Sprzedane';
            case '5':
                return 'Umowa przedwstępna';
            case '6':
                return 'Umowa deweloperska';
            case '7':
                return 'Akt notarialny';
            case '8':
                return 'Odbiór';
            case '9':
                return 'Wstrzymany';
        }
    }
}
