---
title: Learn You a Haskell for Great Good 笔记 第二弹 未完待续
layout: post
---

# 高阶函数

把函数作为函数，或者把函数作为返回值的函数，称之为高阶函数。

## 柯里化

在haskell中，每个函数都有且只有一个参数。

那么，多参函数是怎么实现的呢？

假设在javascript中，函数都只有一个参数，那么我们如何实现一个有加法功能的（带有两个参数的）函数呢？

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

确保你理解了柯里化，因为据说它很重要。下面是个测验

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


