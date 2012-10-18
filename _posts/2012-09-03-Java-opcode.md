---
title: Java 字节码
layout: post
---

 Java 字节码的指令占一个字节，大概有200来个指令，指令的构成大概是这样的。

> iload_1

第一个字母 i 表示操作数的类型，这里是 Integer ，还有 d 代表 double 等等。后面的 _n 代表操作的是变量列表的第几个数。

或许你会想当然的认为 Java 字节码和汇编语言一样。其实， Java 字节码和汇编语言并不太一样，因为 Java 字节码是**面向栈的编程语言**。

比如，指令 iconst_0 ，意思就是将整数值0（注意，不是变量列表的第0个）放入栈顶（即入栈）。

面向栈的编程语言是什么意思呢？就是操作数将从栈中取，而不用跟随指令给出。比如在汇编语言中，做加法大概会用这样的指令：

> add eax, edx

> mov ecx, eax

看，add指令后面要跟两个数，毕竟，加法确实要两个数才能相加。

但在面向栈的语言中，我们会这样处理：

> 0 iload_1

> 1 iload_2

> 2 iadd

> 3 istore_3

含义是这样的：

第0步 将变量列表的第一个数入栈；

第1步 将变量列表的第二个数入栈；

第2步 执行加法操作；

第3步 将栈顶的数存入变量列表的第三个数。

这里的重点在第2步，执行加法操作。加法操作将自动从栈中退出两个数，相加，并将它们的和放入栈顶。

ps: 犹记当年看书的时候，书中说道面向栈的系统很少。不知那位著书人现在怎么解释，呵呵。

参考：

1. [Java bytecode](http://en.wikipedia.org/wiki/Java_bytecode)
2. [Java bytecode instruction listings](http://en.wikipedia.org/wiki/Java_bytecode_instruction_listings)
