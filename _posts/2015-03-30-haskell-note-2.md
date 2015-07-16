---
title: Learn You a Haskell for Great Good 笔记 第二弹
layout: post
---

# 高阶函数

将函数作为参数、或将函数作为返回值的函数，称之为高阶函数。

## 柯里化

在haskell中，每个函数都有且只有一个参数。

那么，多参函数是怎么实现的呢？

假设在 JavaScript 中，函数都只有一个参数，那么我们如何实现一个有加法功能的（带有两个参数的）函数呢？

    function add(x) {
        return function (y) {
            return x + y;
        }
    }
    add(3)(4)

这种技巧称之为柯里化。Curry。

顺带一提，有个人叫 Haskell Curry。他手臂上有个纹身（大魔法师都会有魔纹的对吗？），这个纹身叫 **Y组合子**，是此人原创的魔功，有时间给大家讲讲。

所以，现在再看看类型声明，有没有恍然大悟的感觉？

    multThree :: (Num a) => a -> a -> a -> a  
    multThree x y z = x * y * z  

类型声明是右结合的。

当我们调用 `multThree 3 5 9`时发生了什么？
是 `((multThree 3) 5) 9`，对吗？

请确保你理解了柯里化，因为它很重要。下面是个测验

    divideByTen :: (Floating a) => a -> a  
    divideByTen = (/10)  

##来点高阶函数尝鲜

    applyTwice :: (a -> a) -> a -> a  
    applyTwice f x = f (f x)  

之前我们没有在类型声明中使用括号是因为类型声明是右结合的。

    ghci> applyTwice (+3) 10  
    16  
    ghci> applyTwice (++ " HAHA") "HEY"  
    "HEY HAHA HAHA"  
    ghci> applyTwice ("HAHA " ++) "HEY"  
    "HAHA HAHA HEY"  
    ghci> applyTwice (multThree 2 2) 9  
    144  
    ghci> applyTwice (3:) [1]  
    [3,3,1]  

接下来我们要实现 zipWith

    zipWith' :: (a -> b -> c) -> [a] -> [b] -> [c]  
    zipWith' _ [] _ = []  
    zipWith' _ _ [] = []  
    zipWith' f (x:xs) (y:ys) = f x y : zipWith' f xs ys  

zipWith 能干的事情才让你大吃一惊

    ghci> zipWith' (+) [4,2,5,6] [2,6,2,3]  
    [6,8,7,9]  
    ghci> zipWith' max [6,3,2,1] [7,3,1,5]  
    [7,3,2,5]  
    ghci> zipWith' (++) ["foo ", "bar ", "baz "] ["fighters", "hoppers", "aldrin"]  
    ["foo fighters","bar hoppers","baz aldrin"]  
    ghci> zipWith' (*) (replicate 5 2) [1..]  
    [2,4,6,8,10]  
    ghci> zipWith' (zipWith' (*)) [[1,2,3],[3,5,6],[2,3,4]] [[3,2,2],[3,4,5],[5,4,3]]  
    [[3,4,6],[9,20,30],[10,12,12]]  

接下来我们要实现flip，它的作用是将两个参数的顺序互换。

    flip' :: (a -> b -> c) -> (b -> a -> c)  
    flip' f = g  
        where g x y = f y x  

羚羊挂角，无迹可踪。你甚至怀疑这个编译器是个人工智能。

接下来这个等价的声明，会让你好受许多

    flip' :: (a -> b -> c) -> b -> a -> c  
    flip' f y x = f x y  

不过flip用出来倒是非常直观

    ghci> flip' zip [1,2,3,4,5] "hello"  
    [('h',1),('e',2),('l',3),('l',4),('o',5)]  
    ghci> zipWith (flip' div) [2,2..] [10,8,6,4,2]  
    [5,4,3,2,1]  

map ，作用就是biu biu biu，像子弹飞

    map :: (a->b) -> [a] -> [b]
    map _ [] = []
    map f (x:xs) = f x : map f xs

大家要多看代码，才能转换思维方式呀

    ghci> map (+3) [1,5,3,1,6]  
    [4,8,6,4,9]  
    ghci> map (++ "!") ["BIFF", "BANG", "POW"]  
    ["BIFF!","BANG!","POW!"]  
    ghci> map (replicate 3) [3..6]  
    [[3,3,3],[4,4,4],[5,5,5],[6,6,6]]  
    ghci> map (map (^2)) [[1,2],[3,4,5,6],[7,8]]  
    [[1,4],[9,16,25,36],[49,64]]  
    ghci> map fst [(1,2),(3,5),(6,3),(2,6),(2,5)]  
    [1,3,6,2,2]  

filter 过滤器

    filter :: (a->Bool) -> [a] -> [a]
    filter _ [] = []
    filter p (x:xs) = if p x then x : r else r
                      where r = filter p xs

一定要多看,才能转换成函数式的思维方式.因为很重要所以再说一遍。

    ghci> filter (>3) [1,5,3,2,1,6,4,3,2,1]  
    [5,6,4]  
    ghci> filter (==3) [1,2,3,4,5]  
    [3]  
    ghci> filter even [1..10]  
    [2,4,6,8,10]  
    ghci> let notNull x = not (null x) in filter notNull [[1,2,3],[],[3,4,5],[2,2],[],[],[]]  
    [[1,2,3],[3,4,5],[2,2]]  
    ghci> filter (`elem` ['a'..'z']) "u LaUgH aT mE BeCaUsE I aM diFfeRent"  
    "uagameasadifeent"  
    ghci> filter (`elem` ['A'..'Z']) "i lauGh At You BecAuse u r aLL the Same"  
    "GAYBALLS"  

有了filter函数，我们可以写一个更容易理解的快排函数

    quicksort :: (Ord a) => [a] -> [a]
    quicksort [] = []
    quicksort (x:xs) = quicksort (filter (<x) xs) ++ [x] ++ quicksort (filter (>=x) xs)

因为haskell是懒求值的，所以就算你map和filter一个列表多次，最后实际上只会有一次遍历。

**求1000以下，最大的，能被3829整除的数。**

    largestDivisible :: (Integral a) => a  
    largestDivisible = head (filter p [100000,99999..])  
        where p x = x `mod` 3829 == 0  

因为haskell是懒求值，而head只需要第一个元素，所以，列表是不是无限的，在这里是无所谓的。当它找到第一个元素时，整个求值就会停止。

**求所有小于10000的齐平方数的和**

简要介绍一下 takeWhile函数 while true，take，else break

    ghci> sum (takeWhile (<10000) (filter odd (map (^2) [1..])))  
    166650  

[奇偶归一](http://zh.wikipedia.org/wiki/%E8%80%83%E6%8B%89%E5%85%B9%E7%8C%9C%E6%83%B3)

从1到100，找出所有步骤数大于15的数。

    next :: Integral a => a -> a
    next n
        | even n = n `div` 2
        | odd n  = n * 3 + 1

    chain :: Integral a => a -> [a]
    chain 1 = [1]
    chain n = n : chain (next n)

    let isLong xs = length xs > 15 in filter isLong (map chain [1..100])

列表中可以是任何类型，包括函数

    ghci> let listOfFuns = map (*) [0..]  
    ghci> (listOfFuns !! 4) 5  
    20  

## lambda

lambda基本上就是匿名函数。

以 `\` 开头，`->` 分割参数和函数体。一般整个的被括号括起来。否则就把函数体一直扩展到最后了去了。

    ghci> zipWith (\a b -> (a * 30 + 3) / b) [5,4,3,2,1] [1,2,3,4,5]  
    [153.0,61.5,31.0,15.75,6.6]  

lambda中也可以做模式匹配

    ghci> map (\(a,b) -> a + b) [(1,2),(3,5),(6,3),(2,6),(2,5)]  
    [3,8,9,8,7]  

以下两种定义形式等价

    addThree :: (Num a) => a -> a -> a -> a  
    addThree x y z = x + y + z  
    addThree :: (Num a) => a -> a -> a -> a  
    addThree = \x -> \y -> \z -> x + y + z  

第二种更能表达本质。

接下来是最容易理解的flip函数

    flip' :: (a -> b -> c) -> b -> a -> c  
    flip' f = \x y -> f y x

## 折叠

前面，我们看到了好多 `x:xs` 这种形式，要有函数来封装之。我们称之为折叠。和map不同，这个函数会将列表收敛成单值。

折叠函数需要一个二元函数，一个起始值（称之为累元），还有一个列表。
二元函数用在累元和第一个（或最后一个）元素上，产生一个新的累元。然后，二元函数用在新的累元和新的第一个（或最后一个）元素上，直到列表为空。
累元的值就是结果。

首先来看看 `foldl`, 左折叠。从左边开始折叠。

用foldl实现sum

    sum' :: (Num a) =>[a] -> a
    sum' xs = foldl (+) 0 xs

更易读的：

    sum' xs = foldl (\acc x -> acc + x) 0 xs

大家会发现xs在定义式的两边都有，所以

    sum' = foldl (+) 0

我可以说，这个比较接近sum的本质。也可以说，这是sum的另一种表达方式。

一般的，如果你有一个函数 `foo a = bar b a`, 因柯里化，你就可以重写为 `foo = bar b`

然后再用foldl实现elem（elem出镜率很高呀）

    elem' :: (Eq a) => a -> [a] -> Bool
    elem' x xs = foldl (\acc y -> if x == y then True else acc) False xs

foldr 右折叠，从右开始折叠。
它的第一个参数（二元函数）是第一个参数是当前值，第二个参数是累元，也就是 
`\x acc -> ...`

累元可以是任何类型，可能是数字，布尔值，甚至是一个list。
让我们来用foldr实现map吧。

    map' f xs = foldr (\x acc -> f x : acc) [] xs

有时foldl和foldr没有什么区别，比如用来实现sum时。还是有区别的，foldr可以用来折叠无穷列表。从某个点开始，向左折叠，总会到达头部。

如果你想遍历一个列表一次，就是fold显身手的时候。

foldl1 and foldr1 很像 foldl and foldr，但是你不必提供一个起始值。他们会自动将第一个值作为起始值。

sum可以这样实现 `sum = foldl1 (+)`
当时当空列表时，就会出问题。

来来来，做做练习

    maximum' :: (Ord a) => [a] -> a
    maximum' = foldl1 (\x y -> if x > y then x else y) 0

    reverse' :: [a] -> [a]
    reverse' = foldl (\acc x -> x : acc) []

    product' :: (Num a) => [a] -> a
    product' = foldl (*) 1

    filter' p :: (a -> Bool) -> [a] -> [a]
    filter' p = foldr (\x acc -> if p x then x : acc else acc) []

    head' :: [a] -> a
    head' = foldr1 (\x _ -> x)

    last' :: [a] -> a
    last' = foldl1 (\_ x -> x)

在reverse的实现中，我们的 `\acc x -> x : acc` 很像 (:) 也就是 `\x acc -> x : acc`，但是参数是反着的，所以，reverse就是 `foldl (flip (:)) []`

scanl and scanr 和 foldl and foldr 很像，只是每次累元的值都会返回。也有scanl1 and scanr1, 概念上模仿 foldl1 and foldr1.

    ghci> scanl (+) 0 [3,5,2,1]  
    [0,3,8,10,11]  
    ghci> scanr (+) 0 [3,5,2,1]  
    [11,8,3,1,0]  
    ghci> scanl1 (\acc x -> if x > acc then x else acc) [3,4,5,3,7,9,2,1]  
    [3,4,5,5,7,9,9,9]  
    ghci> scanl (flip (:)) [] [3,2,1]  
    [[],[3],[2,3],[1,2,3]]  

scanl 和 scanr的生成列表的顺序也是相反的

    Prelude> scanr (+) 0 [3,5,2,1]  
    [11,8,3,1,0]

scan用来监视fold过程发生的事情

让我们来回答一个问题：自然数列的平方根数列之和超过1000后的第一个自然数是多少？

    sqrtSums :: Int  
    sqrtSums = length (takeWhile (<1000) (scanl1 (+) (map sqrt [1..]))) + 1  

### $ 和函数应用

首先看看$的定义

    ($) :: (a -> b) -> a -> b  
    f $ x = f x  

你不禁要问：有什么用呢？

函数应用的优先级最高，但是$的优先级最低。

函数调用是左结合的，但是$是右结合的。

这样一来，就可以省很多括号。

考虑一下 `sum (map sqrt [1..130])`. 因为$的优先级最低，等价于 `sum $ map sqrt [1..130]`, 这样就省下了很多心智负担。

再来看看 `sqrt (3 + 4 + 9)` 等价于 `sqrt $ 3 + 4 + 9`

再看看 `sum (filter (> 10) (map (*2) [2..10]))`，等价于
`sum $ filter (> 10) $ map (*2) [2..10]`

从这一点来看， $ 像是逆向的管道。

除了消灭括号之外，$意味着函数调用本身就可以被当作一个函数。

    ghci> map ($ 3) [(4+), (10*), (^2), sqrt]  
    [7.0,30.0,9.0,1.7320508075688772]

### 函数复合

在数学中，函数复合就像 $(f \circ g)(x)=f(g(x))$

Haskell 中也有函数复合啦，就是 `.` 运算符

    (.) :: (b -> c) -> (a -> b) -> a -> c  
    f . g = \x -> f (g x)  

比如我们有

    map (\x -> negate (abs x)) [5,-3,-6,7,-3,2,-19,24]

那么，就可以写成

    map (negate . abs) [5,-3,-6,7,-3,2,-19,24]

函数复合是右结合的。我们可以同时复合多个函数，如
`(f . g . h) x` 就是 `f (g (h x))`

没有重点的风格：如果一个定义左右两边省掉了参数，就是无重点风格。
比如：

    sum' xs = foldl (+) 0 xs 

左右两边都有 xs，去掉之后

    sum' = foldl (+) 0

那么这个怎么变成 无重点风格？

    fn x = ceiling (negate (tan (cos (max 50 x))))

只要使用 复合符 就可以了

    fn = ceiling . negate . tan . cos . max 50

excited，很多情况下，无重点风格的函数更加直观，精确。因为它让你更关注函数的构成，而不是数据的流动。但当函数太复杂之后，这样做反而会增加阅读难度。

之前曾经解决过一个不超过10000的奇数的和的问题。

    oddSquareSum = sum (takeWhile (<10000) (filter odd (map (^2) [1..])))

可以等价成

    oddSquareSum = (sum . takeWhile (<10000) . filter odd . map (^2)) $ [1..]

当然，人类能读懂的版本在这：

    oddSquareSum =   
        let oddSquares = filter odd $ map (^2) [1..]  
            belowLimit = takeWhile (<10000) oddSquares  
        in  sum belowLimit  
