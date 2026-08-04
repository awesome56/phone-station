<?php

if (! function_exists('naira')) {
    /**
     * Format an integer amount (minor units) as Naira.
     * Stored amounts are treated as kobo-scale: displayed value = amount * 10.
     */
    function naira(int $amount, bool $decimals = false): string
    {
        return '₦' . number_format($amount * 10, $decimals ? 2 : 0);
    }
}
