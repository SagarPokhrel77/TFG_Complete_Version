<?php
/**
 * Configuración UTF-8 para todo el proyecto.
 */
if (!defined("UTF8_BOOTSTRAP")) {
    define("UTF8_BOOTSTRAP", true);

    mb_internal_encoding("UTF-8");
    mb_http_output("UTF-8");
    mb_regex_encoding("UTF-8");

    ini_set("default_charset", "UTF-8");

    if (!headers_sent()) {
        header("Content-Type: text/html; charset=UTF-8");
    }
}
