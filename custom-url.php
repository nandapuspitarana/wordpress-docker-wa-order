<?php
if (isset($_SERVER['HTTP_HOST'])) {
    $http_host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST'];
    $http_proto = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) ? 'https://' : 'http://';
    
    if (!defined('WP_HOME')) {
        define('WP_HOME', $http_proto . $http_host);
    }
    if (!defined('WP_SITEURL')) {
        define('WP_SITEURL', $http_proto . $http_host);
    }
    
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
        $_SERVER['HTTPS'] = 'on';
    }
}
