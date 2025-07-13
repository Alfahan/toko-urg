<?php

if (! function_exists('formatPrice')) {
    /**
     * formatPrice
     *
     * @param mixed $str
     * @return void
     */
    function formatPrice($str) {
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}

if (! function_exists('formatPriceWithoutRp')) {
    /**
     * formatPriceWithoutRp
     *
     * @param mixed $str
     * @return void
     */
    function formatPriceWithoutRp($str) {
        return number_format($str, '0', '', '.');
    }
}
