<?php

if (! function_exists('roomStatus')) {
    function roomStatus(int $number)
    {
        switch ($number) {
            case '1':
                return 'Dostępne';
            case '2':
                return 'Rezerwacja';
            case '3':
                return 'Sprzedane';
            case '4':
                return 'Rezerwacja ustna';
        }
    }
}
