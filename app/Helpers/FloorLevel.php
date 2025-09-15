<?php

use Illuminate\Support\Str;

if (! function_exists('floorLevel')) {
    function floorLevel($number, bool $slug): string
    {
        $floorLevels = [
            0 => 'Parter',
            1 => '1 piętro',
            2 => '2 piętro',
            3 => '3 piętro',
            4 => '4 piętro',
            5 => '5 piętro',
            6 => '6 piętro',
            7 => '7 piętro',
            8 => '8 piętro',
            9 => '9 piętro',
            10 => '10 piętro',
        ];

        $default = 'Brak'; // <-- custom fallback

        $value = $floorLevels[$number] ?? $default;

        return $slug ? Str::slug($value) : $value;
    }
}
