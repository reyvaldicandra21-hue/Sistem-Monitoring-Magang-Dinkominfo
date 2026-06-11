<?php

use Illuminate\Support\Facades\Crypt;

if (! function_exists('encrypted_route')) {
    /**
     * Generate a route URL with encrypted numeric parameters.
     */
    function encrypted_route(string $name, array|int|string $parameters = [], bool $absolute = true): string
    {
        if (! is_array($parameters)) {
            $parameters = [$parameters];
        }

        $parameters = array_map(function ($value) {
            if (is_numeric($value)) {
                return Crypt::encryptString((string) $value);
            }

            if (is_object($value) && method_exists($value, 'getKey')) {
                return Crypt::encryptString((string) $value->getKey());
            }

            return $value;
        }, $parameters);

        return route($name, $parameters, $absolute);
    }
}
