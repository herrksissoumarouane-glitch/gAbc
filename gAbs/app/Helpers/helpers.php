<?php

if (!function_exists('NumberToLetters')) {
    function NumberToLetters($number) {
        $convert = explode('.', $number);
        $num[17] = array('ZERO', 'UN', 'DEUX', 'TROIS', 'QUATRE', 'CINQ', 'SIX', 'SEPT', 'HUIT',
                         'NEUF', 'DIX', 'ONZE', 'DOUZE', 'TREIZE', 'QUATORZE', 'QUINZE', 'SEIZE');
                          
        $num[100] = array(20 => 'VINGT', 30 => 'TRENTE', 40 => 'QUARANTE', 50 => 'CINQUANTE',
                          60 => 'SOIXANTE', 70 => 'SOIXANTE-DIX', 80 => 'QUATRE-VINGT', 90 => 'QUATRE-VINGT-DIX');
                          
        if (isset($convert[1]) && $convert[1] != '') {
            return NumberToLetters($convert[0]).' virgule '.NumberToLetters($convert[1]);
        }
        
        if ($number < 0) return 'moins '.NumberToLetters(-$number);
        if ($number < 17) return $num[17][$number];
        
        if ($number < 20) return 'dix-'.strtolower($num[17][$number-10]);
        if ($number < 100) {
            if ($number%10 == 0) return $num[100][$number];
            
            if ($number < 70) return $num[100][floor($number/10)*10].'-'.strtolower($num[17][$number%10]);
            
            if ($number < 80) return 'soixante-'.strtolower($num[17][$number-60]);
            
            if ($number == 80) return 'quatre-vingts';
            
            return 'quatre-vingt-'.strtolower($num[17][$number-80]);
        }
        
        if ($number == 100) return 'cent';
        if ($number < 200) return 'cent '.NumberToLetters($number-100);
        if ($number < 1000) return $num[17][floor($number/100)].' cent '.NumberToLetters($number%100);
        if ($number == 1000) return 'mille';
        if ($number < 2000) return 'mille '.NumberToLetters($number%1000);
        if ($number < 1000000) {
            if ($number%1000 == 0) return NumberToLetters(floor($number/1000)).' mille';
            return NumberToLetters(floor($number/1000)).' mille '.NumberToLetters($number%1000);
        }
        
        if ($number == 1000000) return 'un million';
        if ($number < 2000000) return 'un million '.NumberToLetters($number%1000000);
        if ($number < 1000000000) {
            if ($number%1000000 == 0) return NumberToLetters(floor($number/1000000)).' millions';
            return NumberToLetters(floor($number/1000000)).' millions '.NumberToLetters($number%1000000);
        }
    }
} 