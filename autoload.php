<?php

/**
 * Classe simples para autoload do projeto, para carregar as classes dentro da 
 * pasta 'inc'
 * Author: Claudio Hessel 2021
 */
spl_autoload_register(function ($class) {
    $prefix = 'SWPER\\GitHubRepoLiveSearch\\';

    $base_dir = __DIR__ . '/inc/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
