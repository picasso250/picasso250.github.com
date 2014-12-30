---
title: 每一行代码都可能产生bug
layout: post
---

[Every Line Is a Potential Bug](http://www.teamten.com/lawrence/writings/every_line_is_a_potential_bug.html)

简译如下:

我去年<del>买了个</del>写了个程序, 是从一个hash表里取消息, 第一次取的时候, 消息可能还未生产出来, 所以代码是这样的:

    while ((message = map.get(key)) == null && System.currentTimeMillis() < timeoutTime) {
        wait(1000);
    }

`wait()` 调用会阻塞, 直到被生产者的 `notifyAll()` 唤醒. 1000 代表毫秒数. 超时时间定为5秒.

上面的代码简单而且正确. 它会一直循环, 直到超时. 超时时间可能会超过5秒后再超过一秒, 但这个不是问题. 或者说, 如果此事发生, 你要解决的重点就不再这里了.

然后我的两个同事审核这段代码, 他们说 `wait()` 的时间应该是计算出的精确时间, 而不应该是1秒. 他们说这个线程醒了5次, 这是不必要的. 我回应说, 最有可能的事情是第一秒就取得了message, 再者说唤醒一个线程又不是什么昂贵的操作. 我还说, 他们提议的代码更复杂, 因此更容易出bug.

那两个人说: "一个减法有什么复杂度!". 然后两个人帮我改好了代码. 但很不幸, 他们两个都引入了bug. 其中一个人是用错了常量, 这没什么可说的. 另一个人的bug就非常隐晦了.

    while ((message = map.get(key)) == null && System.currentTimeMillis() < timeoutTime) {
        wait(timeoutTime - System.currentTimeMillis());
    }

有很小的几率, 对第二个 `System.currentTimeMillis()` 求值的时候, 时间稍微前进了那么一小会, 那么这次返回的值更大, 所以 `wait()` 的参数就是负的了, 报了一个 `IllegalArgumentException`. 为了节省一个并不太可能发生的线程切换, 他引入了一个 bug. 还有更惨的一种可能, 如果wait的参数为0, 那么就会一直等待.

你写的每行代码都可能藏着一个bug. 不要多写一行代码. 除非你现在就需要它, 没它就会死. 不要预测未来的需求, 一切你现在不需要的抽象层都不要写(这就是我不喜欢大框架的原因). 如果一项优化会引入复杂度, 哪怕是一个减号, 反对优化. 如果你化蛇添足的代码里产生了bug, 夜深人静, 扪心自问, 你不感到羞愧吗?