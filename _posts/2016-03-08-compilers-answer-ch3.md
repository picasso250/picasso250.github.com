---
title: 龙书习题 第三章
layout: post
---

## 练习 3.1.1

> 根据 3.1.2 节中的讨论，将下面的 C++ 程序
>
>     float limitedSquare(x){
>         float x;
>         /* return x-squared, but never more than 100 */
>         return (x<=-10.0||x>=10.0)?100:x*x;
>     }
>
> 划分成正确的词素序列。哪些词素应该有相关联的词法值？应该具有什么值？

---

解答：再强调一下，词素分成5类关键字

1. keyword运算符
2. op标识符
3. id常量
4. number/string
5. 标点符号（直接写出来）

词素序列如下：

    <keyword, float>
    <id, *limitedSquare>
    (
    <id, *x>
    )
    {
    <keyword, float>
    <id, *x>
    ;
    <keyword, return>
    (
    <id, *x>
    <op, '<=' >
    <op, '-'>
    <number, 10.0>
    <op, '||' >
    <id, *x>
    <op, '>=' >
    <number, 10.0>
    )
    <op, '?' >
    <number, 100>
    <op, ':' >
    <id, *x>
    <op, '*' >
    <id, *x>
    ;
    }

##练习 3.1.2

> 像 HTML 或 XML 之类的标记语言不同于传统的程序设计语言，它们要么包含有很多标点符号（标记），如 HTML，要么使用由用户定义的标记集合，如 XML。而且标记还可以带有参数。请指出如何把如下的 HTML 文档
> 
>     Here is a photo of <B>my house</B>;
>     <P><IMG SRC = "house.gif"><BR>
>     See <A HREF = "morePix.html">More Pictures</A> if you
>     liked that one.<P>
> 
> 划分成合适的词素序列。哪些词素应该具有相关联的词法值？应该具有什么样的值？

解答：

    (text, "Here is a photo of ")
    (<)
    (tag, B)
    (>)
    (text, "my house")
    (</)
    (tag, B)
    (>)
    (text, ";")
    (<)
    (tag P)
    (>)
    (<)
    (tag IMG)
    (tag "SRC")
    (=)
    (tag "house.gif")
    (<)
    (tag BR)
    (text "See ")
    ...
