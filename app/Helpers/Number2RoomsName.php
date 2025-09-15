<?php

use Illuminate\Support\Str;

if (! function_exists('number2RoomsName')) {
    function number2RoomsName(int $number, bool $slug): string
    {
        $rooms = [
            1 => '1 pokój',
            2 => '2 pokoje',
            3 => '3 pokoje',
            4 => '4 pokoje',
            5 => '5 pokoi',
            6 => '6 pokoi',
            7 => '7 pokoi',
            8 => '8 pokoi',
            0 => 'brak',
        ];

        if(!$slug){
            return $rooms[$number] ?? '';
        } else {
            return Str::slug($rooms[$number] ?? '');
        }
    }
}
