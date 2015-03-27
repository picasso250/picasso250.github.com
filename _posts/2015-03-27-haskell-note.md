---
title: yield 出来的协程
layout: post
---

初学Haskell就用 [Haskell Platform](https://www.haskell.org/platform/)
省心。

`ghci` 可以打开交互模式。

`:l myfunctions` 可以加载 `myfunctions.hs` 文件。

haskell最重要的两点特性

1. 纯函数, 所以烧脑
2. 懒求值

`!=` 在haskell中是 `\=`

函数调用不需要加括号，即使参数数量大于1（多参函数其本质是函数左结合和curry）

    succ 8

函数调用的优先级非常高

    succ 9 + max 5 4 + 1

二元函数可以有中缀表达

    div 9 3
    9 `div` 3

函数定义

    doubleMe x = x + x
    doubleUs x y = doubleMe x + doubleMe y

if else 是个表达式，也有值

    doubleSmall x = if x > 100 then x else x*2

顺带说一句，函数不能以大写字母开头。为什么呢？你学完这个课程就知道了。

`'`是个合法的变量名组成部分。变量和函数的定义方法一样。考虑到函数是一等公民，而变量又不可变，这非常自然。你就把变量看成无参函数吧。

    xiaochi'girlfriend = "his left hand"

列表中的元素必须是基友，也就是同类型的。列表也是有类型的。C++中的这个意思 `List<Int>`

    let lostNumbers = [2,3,4]

`let` 在交互模式中使用，相当于在文件中定义之后，再load这个变量。

`++` 可以连接两个列表。

    [1,2,3,4] ++ [9,10,11,12]

字符串就是字符的列表，因为常用，所以有语法糖。

    ['w','o'] ++ " cao"

连接两个m长度和n长度的列表的复杂度是O(m)，在头部增加新的元素是常数代价。入栈符是 `:`

    5:[1,2,3,4,5]

`[1,2,3]` 只是 `1:2:3:[]` 的语法糖。

c语言中 `a[1]` 在 haskell中是

    [9.4,33.2,96.2,11.2,23.25] !! 1

下标一样都是从0开始。

列表可以比较大小。

对列表的操作 `head`返回头部，`tail`返回除了头部之外的一个列表。`last`返回最后一个元素。`init`返回除了最后一个元素的其他部分。

    head [1,3,2]

`length` 返回长度

`reverse` 返回一个反转的列表

`take`截取列表

    take 3 [5, 4,3,2,1]

`drop` 的含义恰恰相反

    drop 3 [5,4,3,2,1]

`minimum` 和 `maximum` 分别返回列表中的最大值和最小值

`sum`和`product`分别返回列表的和与积.

`elem` 相当于 ptyhon 中的 `in` 或者 php 中的 `in_array()`

    4 `elem` [1,3,4,5]

不能尽数...


