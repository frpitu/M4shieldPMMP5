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
namespace M4Shield\Util {
    interface ICache {
        public static function add(string $key, $value): void;
        
        public static function remove(string $key): void;
        
        public static function get(string $key);
        
        public static function clearAll(): void;
        
        public static function hasCache(string $key = "all"): bool;
    }
}
