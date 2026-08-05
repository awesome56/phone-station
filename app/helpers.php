<?php

if (! function_exists('naira')) {
    /**
     * Format an integer amount (minor units) as Naira.
     * Stored amounts are treated as kobo-scale: displayed value = amount * 10.
     */
    function naira(int $amount, bool $decimals = false): string
    {
        return '₦'.number_format($amount * 10, $decimals ? 2 : 0);
    }
}

if (! function_exists('percent_delta')) {
    /**
     * Percentage change between two values (new vs old), null when undefined.
     */
    function percent_delta(int|float $current, int|float $previous): ?float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
