#!/usr/bin/php
<?php

$post_dir = __DIR__ . '/_posts/';

$file_list = array_map(
    function ($filename) use($post_dir) {
        return $post_dir . $filename;
    }, 
    array_filter(
        scandir($post_dir), 
        function ($filename) {
            return end(explode('.', $filename)) === 'md';
        }
    )
); // that not self documentation code

foreach ($file_list as $file) {
    $content = file_get_contents($file);
    d($content);
}

function d($var)
{
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
}
