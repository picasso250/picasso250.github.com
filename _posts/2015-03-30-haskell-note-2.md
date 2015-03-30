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

接下来我们要实现

    zipWith' :: (a -> b -> c) -> [a] -> [b] -> [c]  
    zipWith' _ [] _ = []  
    zipWith' _ _ [] = []  
    zipWith' f (x:xs) (y:ys) = f x y : zipWith' f xs ys  
