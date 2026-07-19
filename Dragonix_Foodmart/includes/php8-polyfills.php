<?php
/**
 * Small polyfills for functions removed in PHP 7/8 that the legacy code still
 * calls. Magic quotes were removed in PHP 5.4 (always off), so these return the
 * "disabled" value.
 */
if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc() { return false; }
}
if (!function_exists('get_magic_quotes_runtime')) {
    function get_magic_quotes_runtime() { return false; }
}
if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($v = false) { return false; }
}
