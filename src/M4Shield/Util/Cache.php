<?php

/**
* 
* ███╗░░░███╗░░██╗██╗████████╗██╗░░██╗███████╗██╗░░░██╗░██████╗
* ████╗░████║░██╔╝██║╚══██╔══╝██║░░██║██╔════╝██║░░░██║██╔════╝
* ██╔████╔██║██╔╝░██║░░░██║░░░███████║█████╗░░██║░░░██║╚█████╗░
* ██║╚██╔╝██║███████║░░░██║░░░██╔══██║██╔══╝░░██║░░░██║░╚═══██╗
* ██║░╚═╝░██║╚════██║░░░██║░░░██║░░██║███████╗╚██████╔╝██████╔╝
* ╚═╝░░░░░╚═╝░░░░░╚═╝░░░╚═╝░░░╚═╝░░╚═╝╚══════╝░╚═════╝░╚═════╝░
*
* This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * @author m4theus.wtfkkj
*/

namespace M4Shield\Util;

class Cache implements ICache
{
    private static array $cache = [];

    public static function add(string $key, $value): void
    {
        self::$cache[$key] = $value;
    }

    public static function remove(string $key): void
    {
        unset(self::$cache[$key]);
    }

    public static function get(string $key)
    {
        return self::$cache[$key] ?? null;
    }

    public static function clearAll(): void
    {
        self::$cache = [];
    }
    
    public static function hasCache(string $key = "all"): bool
    {
        if ($key === "all") {
            return !empty(self::$cache);
        }

        return isset(self::$cache[$key]);
    }
}