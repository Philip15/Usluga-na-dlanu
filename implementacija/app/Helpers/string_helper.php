<?php
/**
  * @author Lazar Premović  2019/0091
  */
  
/**
 * Funkcija koja proverava da li string sadrzi rec
 * dodata zbok kompatibilnosti sa PHP verzijama <8
 * 
 * @param string $haystack string u kome se trazi podstring
 * @param string $needle string koji se trazi
 * 
 * @return bool
 */
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

if (!function_exists('str_starts_with')) {
  function str_starts_with(string $haystack, string $needle): bool
  {
      return substr($haystack, 0, strlen($needle)) === $needle;
  }
}