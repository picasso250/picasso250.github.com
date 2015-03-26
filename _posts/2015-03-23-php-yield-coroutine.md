---
title: yield 出来的协程
layout: post
---

yield 是什么

    function do_something()
    {
        $n = mt_rand(5, 22);
        for ($i=0; $i<$n; $i++) {
            yield $i;
        }
        return;
    }

    // test
    $g = do_something();
    echo $g->current(),PHP_EOL;
    $g->next();
    echo $g->current(),PHP_EOL;


    $n = 8; // 协程数量
    for ($i=0; $i<$n; $i++) {
        $pool[$i] = do_something();
    }
    for ($j=0; $j < 33; $j++) {
        for ($i=0; $i<$n; $i++) {
            if ($pool[$i]->valid()) {
                echo $pool[$i]->current(), PHP_EOL;
                $pool[$i]->next();
            }
        }
    }
