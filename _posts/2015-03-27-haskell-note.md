---
title: Learn You a Haskell for Great Good 笔记, 又名半小时学haskell
layout: post
---

# 简介

这是一本书
[Learn You a Haskell for Great Good!](http://learnyouahaskell.com/chapters)
的笔记。如果觉得我写的不清楚，就去看原书。

我默认你懂的一些C系语言，也懂一些 lambda 演算的基础知识。

初学 Haskell 就用 [Haskell Platform](https://www.haskell.org/platform/)，
省心。

`ghci` 可以打开交互模式。

`:l myfunctions` 可以加载 `myfunctions.hs` 文件。
建议使用图形界面。

haskell最重要的三点特性

1. 纯函数，无副作用
3. 静态类型, 强类型
2. 懒求值

# 出发

加减乘除符号和普通的C系语言是一样的。除了
`!=` 在haskell中是 `\=`

函数调用不需要加括号，即使参数数量大于1（多参函数其本质是函数左结合和curry）

    succ 8

函数调用的优先级非常高

    succ 9 + max 5 4 + 1

二元函数有其中缀表达

    div 9 3
    9 `div` 3

函数定义

    doubleMe x = x + x
    doubleUs x y = doubleMe x + doubleMe y

`if else` 是个表达式，也有值

    doubleSmall x = if x > 100 then x else x*2

顺带说一句，函数不能以大写字母开头。为什么呢？你学完这个课程就知道了。

`'`是个合法的变量名组成部分。变量和函数的定义方法一样。考虑到函数是一等公民，而变量又不可变，这非常自然。（变量就是无参函数）。

    coder'girlfriend = "his left hand"

列表中的元素必须是基友，也就是同类型的。列表也是有类型的。C++中的这个意思 `List<Int>`

    let lostNumbers = [2,3,4]

`let` 在交互模式中使用，相当于在文件中定义之后，再load这个变量。

`++` 可以连接两个列表。

    [1,2,3,4] ++ [9,10,11,12]

字符串就是字符的列表，因为常用，所以有语法糖 `"` 双引号。

单引号表示 `Char`, 双引号表示字符串,`[Char]`

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

其余函数不能尽数...

## 范围

`[1..20]` 相当于 Python 语言中的 `range(1,20)`

`['a'..'z']` 也可以哟

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

看，就连笛卡儿积都是那么优雅。完爆python。

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

# 类型和型类

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

a怎么可以知道呢？可以人工指定（太流氓了）。
大家注意学习指定类型的办法，虽然在函数定义时你学习过了。

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

# 函数语法

模式匹配

    lucky :: (Integral a) => a -> String  
    lucky 7 = "LUCKY NUMBER SEVEN!"  
    lucky x = "Sorry, you're out of luck, pal!"   

上面的代码的意思是匹配给`lucky`的参数，如果你给7，就lucky，否则就打脸。你可能会想执行顺序是从上到下的，默认break,嗯，你可以这么理解。

接下来就是所有的函数式编程教材都要提到的函数：阶乘

    factorial :: (Integral a) => a -> a  
    factorial 0 = 1  
    factorial n = n * factorial (n - 1)  

如果match不到，就报错。

接下来就是最powerful的地方了。

    addVectors :: (Num a) => (a, a) -> (a, a) -> (a, a)  
    addVectors (x1, y1) (x2, y2) = (x1 + x2, y1 + y2)  

上面的函数是两个变量的相加。

简直和写数学公式一样简洁。

列表推导的时候也可以匹配模式。

    ghci> let xs = [(1,3), (4,3), (2,4), (5,3), (5,6), (3,1)]  
    ghci> [a+b | (a,b) <- xs]  
    [4,7,6,8,11,4]   

如何表达列表

    head' :: [a] -> a  
    head' [] = error "Can't call head on an empty list, dummy!"  
    head' (x:_) = x  

看到 `:` 你就明白，x是头部

然后验证一下我们的成果：递归版本的length函数

    length' :: (Num b) => [a] -> b  
    length' [] = 0  
    length' (_:xs) = 1 + length' xs  

`@`符号可以表示重复匹配。如`xs@(x:_)`表示后面`(x:_)`的整个列表。

    capital :: String -> String  
    capital "" = "Empty string, whoops!"  
    capital all@(x:xs) = "The first letter of " ++ all ++ " is " ++ [x]  

##守卫

守卫(Guard, 我觉得翻译成 **关隘** 更合理）大概是这个样子的，在函数名和参数后面添加一个竖线，然后守卫就是那个布尔表达式，能不能通过，就看你符不符合他的标准了。

下面这个函数是BMI体重指数检测。

    bmiTell :: (RealFloat a) => a -> String  
    bmiTell bmi  
        | bmi <= 18.5 = "You're underweight, you emo, you!"  
        | bmi <= 25.0 = "You're supposedly normal. Pffft, I bet you're ugly!"  
        | bmi <= 30.0 = "You're fat! Lose some weight, fatty!"  
        | otherwise   = "You're a whale, congratulations!"  

很多时候，最后一个守卫是 otherwise，就是True。

当然，下面是一个更实用的版本。

    bmiTell :: (RealFloat a) => a -> a -> String  
    bmiTell weight height  
        | weight / height ^ 2 <= 18.5 = "You're underweight, you emo, you!"  
        | weight / height ^ 2 <= 25.0 = "You're supposedly normal. Pffft, I bet you're ugly!"  
        | weight / height ^ 2 <= 30.0 = "You're fat! Lose some weight, fatty!"  
        | otherwise                 = "You're a whale, congratulations!"  

注意那个表达式 `weight / height ^ 2` 我们重复了3编，haskell逼格如此之高，怎么不会有解决方案呢？

    bmiTell :: (RealFloat a) => a -> a -> String  
    bmiTell weight height  
        | bmi <= 18.5 = "You're underweight, you emo, you!"  
        | bmi <= 25.0 = "You're supposedly normal. Pffft, I bet you're ugly!"  
        | bmi <= 30.0 = "You're fat! Lose some weight, fatty!"  
        | otherwise   = "You're a whale, congratulations!"  
        where bmi = weight / height ^ 2  

当然，魔数也可以去掉

    bmiTell :: (RealFloat a) => a -> a -> String  
    bmiTell weight height  
        | bmi <= skinny = "You're underweight, you emo, you!"  
        | bmi <= normal = "You're supposedly normal. Pffft, I bet you're ugly!"  
        | bmi <= fat    = "You're fat! Lose some weight, fatty!"  
        | otherwise     = "You're a whale, congratulations!"  
        where bmi = weight / height ^ 2  
              (skinny, normal, fat) = (18.5, 25.0, 30.0)

ps 如果where后面有多个赋值，一定要排版对齐啊，坑！

pps 那些说元组没用的人，你们不知道集合里要有元组才能方便的表达顺序吗？

    initials :: String -> String -> String  
    initials firstname lastname = [f] ++ ". " ++ [l] ++ "."  
        where (f:_) = firstname  
              (l:_) = lastname    

上面的取姓名缩写的函数，自己领会。

where也是可以嵌套的，因为where里面也是可以定义函数的。

    calcBmis :: (RealFloat a) => [(a, a)] -> [a]  
    calcBmis xs = [bmi w h | (w, h) <- xs]  
        where bmi weight height = weight / height ^ 2  

##let it be

`let` 和 `where` 很像,只是位置不同

    cylinder :: (RealFloat a) => a -> a -> a  
    cylinder r h = 
        let sideArea = 2 * pi * r * h  
            topArea = pi * r ^2  
        in  sideArea + 2 * topArea  

其形式是`let <bindings> in <expression>`。其中 bindings 中的定义在expression中是可用的。

where和let的区别是let是表达式（因此有值），而where是语法结构。

    4 * (let a = 9 in a + 1) + 2  

let也可以在列表推导中使用，此时，它对输出表达式（|前面的部分），断言和区块都是可见的。
如下面这个计算所有的胖子的BMI

    calcBmis :: (RealFloat a) => [(a, a)] -> [a]  
    calcBmis xs = [bmi | (w, h) <- xs, let bmi = w / h ^ 2, bmi >= 25.0]  

在ghci中的let默认是in在整个scope里的

    ghci> let zoot x y z = x * y + z  
    ghci> zoot 3 9 2  
    29  
    ghci> let boot x y z = x * y + z in boot 3 4 2  
    14  
    ghci> boot  
    <interactive>:1:0: Not in scope: `boot'  

let很强大，还要where干什么？因为let不能在守卫(guard)中使用。

# case

c语言中有switch语句，haskell中也有case表达式，如if表达式一样，都是表达式，也都有值。

case实质上就是在做模式匹配。

    head' :: [a] -> a  
    head' [] = error "No head for empty lists!"  
    head' (x:_) = x  

vs

    head' :: [a] -> a  
    head' xs = case xs of [] -> error "No head for empty lists!"  
                      (x:_) -> x  

这两种表达实质上是一样的，上面的函数的模式匹配实质上是case表达式的语法糖。

case 表达式的形式如下：

    case expression of pattern -> result  
                       pattern -> result  
                       pattern -> result  
                       ...  

看个例子

    describeList :: [a] -> String  
    describeList xs = "The list is " ++ case xs of [] -> "empty."  
                                                   [x] -> "a singleton list."   
                                                   xs -> "a longer list."  

当然，你可以使用where语句（更直观，语法糖之所以叫语法糖，就是因为更甜）

    describeList :: [a] -> String  
    describeList xs = "The list is " ++ what xs  
        where what [] = "empty."  
              what [x] = "a singleton list."  
              what xs = "a longer list."  

#递归

有递归，有可枚举类型，基本上这个语言就图灵完全了。

还是拿斐波那契数列举个例子，F(0) = 0, F(1)=1, F(n) = F(n-1)+F(n-2)
这个句子完整的定义了一个数列。
其中，F(0) = 0, F(1)=1被称为边界条件(edge condition)。

##最大化

想象一下如何用递归实现maximum。

如果列表的第一个元素是最大的元素，就返回之，如果不是，就返回除去头部的这个列表的最大元素。

边界条件要考虑到。

    maximum' :: (Ord a) => [a] -> a  
    maximum' [] = error "maximum of empty list"  
    maximum' [x] = x  
    maximum' (x:xs)   
        | x > maxTail = x  
        | otherwise = maxTail  
        where maxTail = maximum' xs  

模式匹配和递归配合的天衣无缝，好多命令式的语言没有模式匹配，所以就只能用if else判断。

##再来一些递归

好了，我们再来一个函数replicate，replicate接受两个参数，个数和元素，复制出一个列表。

    replicate' :: (Num i, Ord i) => i -> a -> [a]  
    replicate' 0 x  = []
    replicate' n x  = x:replicate' (n-1) x  

注意优先级，`:` 这种运算符的优先级总是落后于函数调用的。

再次注意，`Num` 不是 `Ord` 的子集，不是所有的数字都是可排序的（真严谨，不过也真无语）。

然后我们来实现take，take接受两个参数，第一个参数是数字，第二个参数是列表，从列表中提取前N个元素。

    take' :: (Num i, Ord i) => i -> [a] -> [a]  
    take' n _  
        | n <= 0   = []  
    take' _ []     = []  
    take' n (x:xs) = x : take' (n-1) xs  

reverse 会倒排一个数组。

    reverse' :: [a] -> [a]  
    reverse' [] = []  
    reverse' (x:xs) = reverse' xs ++ [x]  

haskell 支持无穷递归，无穷递归的后果就是一直重复某个动作，或者形成某种无穷的数据结构，比如无限列表。当然，我们会可以在某个合适的地方截断它们。

repeat 重复某个元素，形成一个列表。

    repeat' :: a -> [a]  
    repeat' x = x:repeat' x 

zip接受两个列表作为参数，将对应元素拼成一个二元组。
因为较长的列表的后面会被忽略，所以：

    zip' :: [a] -> [b] -> [(a,b)]  
    zip' _ [] = []  
    zip' [] _ = []  
    zip' (x:xs) (y:ys) = (x,y) : zip' xs ys  

elem，查看一个元素是否在列表中。

    elem' :: (Eq a) => a -> [a] -> Bool  
    elem' a [] = False  
    elem' a (x:xs)  
        | a == x    = True  
        | otherwise = a `elem'` xs   

##快速排序

快排这个名字起的真霸气，暗示其他排序方法都是慢的。

    quicksort :: (Ord a) => [a] -> [a]
    quicksort [] = []
    quicksort (x:xs) = smaller ++ [x] ++ bigger
            where smaller = quicksort [ a | a <- xs, a <= x]
                  bigger = quicksort [ a | a <- xs, a > x]

我们来实验一下效果：

    quicksort "the quick brown fox jumps over the lazy dog"  

ps 上面的狐狸跳过一条懒狗是一条检查打印机的著名的句子，包含了所有字母。

##递归的思维方式

哎呀，我天生就是递归的思维方式，以上。

# 总结

你看到这里一定花了不止半个小时，但是你要知道，半个小时学成haskell是不可能的，学C++还要21天呢。

但是你现在学习了大概三分之一了，还有一个小时就学完了，听上去好期待呀。
