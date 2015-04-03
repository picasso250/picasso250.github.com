---
title: yield 出来的协程
layout: post
---

PHP5.6引入了yield关键字，我假设你知道了yield和foreach结合的用法。

我们来写个函数，随机产生一些值：

    function do_something()
    {
        $n = mt_rand(5, 22);
        for ($i=0; $i<$n; $i++) {
            yield $i;
        }
        return;
    }

然后你可以看到，我们用一个顺序流来得到这些值。

    // test
    $g = do_something();
    echo $g->current(),PHP_EOL;
    $g->next();
    echo $g->current(),PHP_EOL;

现在，我们将引入协程的概念。在很久以前，人们就想要使用协程。远在使用磁带的时代，大神在写编译器的时候，希望能够只读一次文件，就能编译成功。这就要求词法的token可以像流一样，产生一点，就被语法分析器处理一点。CPU是在词法分析器和语法分析器之间反复切换的，现在linux用个管道就可以解决。可是linux在那个时代还没产生（进程也没有），大神的想法是使用协程。所谓协程就是两个程序（实际上是两个function）相互协作，你休息时我工作。后来大家就有了磁盘，就谁也不提协程的事了，再后来有了进程，就更没协程什么事了。直到有一天出现了C10K问题，大家开始想起来这个小伙子。

协程，就是CPU可以在一个函数中脱身，然后执行另一个函数，等它回来的时候，从原来脱身的点，继续执行。

我们看看上面写的 `do_something()` 函数，它的局部变量 `$n` 在每次重入时，都在，CPU第一次进入时为0，第二次进入时为1，以此类推。

于是我们知道，yield可以做出来协程。

    $n = 8; // 协程数量

    // 初始化句柄
    for ($i=0; $i<$n; $i++) {
        $pool[$i] = do_something();
    }

    // 消费
    for ($j=0; $j < 33; $j++) {
        for ($i=0; $i<$n; $i++) {
            if ($pool[$i]->valid()) {
                echo $pool[$i]->current(), PHP_EOL;
                $pool[$i]->next();
            }
        }
    }

和socket结合，我们就可以做出使用协程的服务器了。
