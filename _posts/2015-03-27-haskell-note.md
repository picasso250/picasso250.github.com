---
title: haskell笔记,又叫 一小时学haskell
layout: post
---

#简介

初学Haskell就用 [Haskell Platform](https://www.haskell.org/platform/)
省心。

`ghci` 可以打开交互模式。

`:l myfunctions` 可以加载 `myfunctions.hs` 文件。

haskell最重要的三点特性

1. 纯函数, 所以烧脑
3. 静态类型, 强类型, 所以烧脑
2. 懒求值

#出发

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

字符串就是字符的列表，因为常用，所以有语法糖。单引号表示 `Char`, 双引号表示字符串,`[Char]`

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

`take`截取列表. 列表可以是无限的, 是懒求值赋予了这种可能性.

    take 3 [5, 4,3,2,1]

`drop` 的含义恰恰相反

    drop 3 [5,4,3,2,1]

`minimum` 和 `maximum` 分别返回列表中的最大值和最小值

`sum`和`product`分别返回列表的和与积.

`elem` 相当于 ptyhon 中的 `in` 或者 php 中的 `in_array()`

    4 `elem` [1,3,4,5]

不能尽数...

## 范围

`[1..20]` 相当于p系语言的 `range(1,20)`

`['a'..'z'] 也可以哟

其本质上是个等差数列

    [2,4..20]

`cycle` 循环一个列表

    take 10 (cycle [1,2,3])
    take 12 (cycle "^_^ ")

`repeat` 重复一个元素

    take 10 (repeat 5)

## 列表推导

python中也有

    [x*2 for x in a_list]

haskell中这样表示

    [x*2 | x <- [1..20]]
    [x*2 | x <- [1..20], x*2 > 12]
    [ x | x <- [10..20], x /= 13, x /= 15, x /= 19]
    [ x*y | x <- [2,5,10], y <- [8,10,11]]

看，就连笛卡儿积都是那么优雅。

我们来写一个优雅的`length`函数

    length' xs = sum [ 1 | _ <- xs]

对了，haskell中的列表变量一般用 `xs` 表示，恐怕是 `x` 的复数形式吧。

##元组

元组和python中的概念一样，元组是有类型的，长度和元素类型都决定类型。元组内的元素类型可以不同。

    ('xiaochi', 26)
    (2, 3, 0)

第一个元组用名称和年龄表示人，第二个元组表示三维空间中的一个点。

元组非常死板，你可能会怀疑它存在的目的。好吧，我告诉你它存在的目的是为了模式匹配。如果不懂，没关系。继续走下去。

有个函数叫`zip` 很有用。

    ghci> zip [1,2,3,4,5] [5,5,5,5,5]
    [(1,5),(2,5),(3,5),(4,5),(5,5)] 

如果两个列表长度不同(或者某个列表是无限列表), 那么以短列表的长度为准.

接下来做一些练习.

边长为10以下的所有三角形.

    triangles = [ (a,b,c) | c <- [1..10], b <- [1..10], a <- [1..10] ]

上面的程序逻辑正确吗?如果不正确,请指出.(你可能需要一些初中的几何知识)

边长10以下的所有直角三角形.

    rightTriangles = [ (a,b,c) | c <- [1..10], b <- [1..c], a <- [1..b], a^2 + b^2 == c^2]

满足上面的条件而且边长之和为24的三角形

    rightTriangles' = [ (a,b,c) | c <- [1..10], b <- [1..c], a <- [1..b], a^2 + b^2 == c^2, a+b+c == 24]

你看, haskell表达能力爆表.

# 类型

haskell是静态类型, 强类型.

我知道你烦Java, 所幸haskell还提供了 **类型推断**.

在ghci 中 `:t` 可以看变量的类型。

    ghci> :t 'a'
    'a' :: Char
    ghci> :t "HELLO!"
    "HELLO!" :: [Char]
    ghci> :t (True, 'a')
    (True, 'a') :: (Bool, Char)

注意看，haskell是怎样表示列表和元组类型的。

你可以声明一个函数的类型。这是个很好的习惯。除非函数太短。

    removeNonUppercase :: [Char] -> [Char]
    removeNonUppercase st = [ c | c <- st, c `elem` ['A'..'Z']] 

多参函数的类型

    addThree :: Int -> Int -> Int -> Int
    addThree x y z = x + y + z

你可能会想为什么不是 `addTree :: (Int, Int, Int) -> Int`
如果你学过一点lambda演算。你应该猜到，这是curry

下面说一下类型

- `Int`
- `Integer` 大整数(真的很大哦,和python一个尿性,但是性能略低)
- `Float`
- `Double`
- `Bool`
- `Char`

## 类型变量

类型也可以是个变量.

`head` 的类型是 `head :: [a] -> a` 注意其中的a, 这就是类型变量. 和C++的模板类型一个意思.

Typeclass （或许可以翻译成型类） 是一个规范, 和Java中的接口概念类似。

    elem :: Eq a => a -> [a] -> Bool

`Eq`就是一个型类，意思就是可进行相等比较的(Eqauable).

`(>) :: (Ord a) => a -> a -> Bool` `Ord` 可比较大小的。

`Show` 可以被显示的，所有的变量都可以。ps，下面的 `show` 是个函数。

    show 3

`Read` 可以从终端读入的。

    read :: (Read a) => String -> a

a怎么可以知道呢？可以人工指定（太流氓了），大家注意学习制定类型的办法，虽然在函数定义时你学习过了。

    read "5" :: Int

`Enum` 可枚举的。这个型类就是下面的代码可以工作的原因。

    ['a'..'e']

`Bounded` 有界的。

    minBound :: Int

`Num` 数字。 说到这里就得说下haskell变态的地方了，所有的数字都是多态的（ruby表示很轻松）

    ghci> 20 :: Int
    20
    ghci> 20 :: Integer
    20

`Integral` 是 `Int` 和 `Integer` 的合集。

`Floating` 是 `Double` 和 `Float` 的合集。

说到合集，上面的表述是严谨的，仅当我们承认选择公理。

说一个有意思的函数， `fromIntegral` 和PHP中的`intval()`差不多，将数字转化成整数。

    fromIntegral :: (Num b, Integral a) => a -> b
