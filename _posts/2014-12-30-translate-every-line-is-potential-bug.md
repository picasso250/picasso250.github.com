---
title: PHP, 每一行代码都可能产生bug
layout: post
---

[Every Line Is a Potential Bug](http://www.teamten.com/lawrence/writings/every_line_is_a_potential_bug.html)

去年我<del>买了个</del>写了个程序, 是从一个hash表里取出一条消息, 很可能消息还不在那里, 所以代码是这样的:

    while ((message = map.get(key)) == null && System.currentTimeMillis() < timeoutTime) {
        wait(1000);
    }

`wait()` 调用会阻塞, 直到被生产者的 `notifyAll()` 唤醒. 1000 代表毫秒数. timeout 定为5秒.

上面的代码简单而且正确. 它会一直循环, 直到超时. 超时时间可能会超过5秒后再超过一秒, 但这个不是问题. 或者说, 如果此事发生, 你要解决的重点就不再这里了.

然后我的两个同事审核这段代码, 他们说wait的时间应该是计算出的精确时间, 而不应该是1秒. 他们说这段代码醒了5次, 这是不必要的. 我回应说, 最有可能的事情是第一秒就取得了message, 再者说唤醒一个线程又不是什么昂贵的操作. 我还说, 他们提议的代码更复杂, 因此也容易出bug.

那两个人说: "一个减法有什么复杂度!". 然后两个人帮我改好了代码. 但很不幸, 他们两个都写了bug. 其中一个人是用错了常量, 这没什么可说的. 另一个人的bug就非常隐晦了.

    while ((message = map.get(key)) == null && System.currentTimeMillis() < timeoutTime) {
        wait(timeoutTime - System.currentTimeMillis());
    }

有很小的几率, 对第二个System.currentTimeMillis()求值的时候, 时间稍微进行了那么一小会, 那么这次返回的值更大, 所以wait的参数就是负的了.