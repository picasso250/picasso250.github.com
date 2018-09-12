---
title: Quick and dirty php functions
layout: post
---

json decode on the fly

    php -r "print_r(json_decode(file_get_contents('php://stdin')));"
    
debug online

    function d($str,$data=null){
        error_log(sprintf("/tmp/php_debug_%s.log",date('Ymd')),3,sprintf("[%s] %s %s\n",date('c'),$str,json_encode($data,JSON_UNESCAPED_UNICODE));
    }
