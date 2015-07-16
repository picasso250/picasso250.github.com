---
title: Learn You a Haskell for Great Good 笔记 第三弹
layout: post
---

# 模块

## 载入模块

模块是一大堆相关函数、类型、类型类的集合。程序是一堆模块的集合，主模块载入其他的模块，并使用其中的函数，做一些有意义的事情。
将程序划分为模块有很多好处。如果一个模块足够通用，那么它就可以被其他程序使用。

Haskell标准库就分为很多模块。有的模块操作列表，有的是为了并行编程，有的处理复数。我们至今为止使用的函数都属于Prelude (前奏曲) 模块。

先学一下如何载入模块。

语法是 `import <module name>`
这个必须在定义任何函数之前写下，所以通常都是在文件头部。
一个文件当然可以导入很多模块，每个一行就行了。
我们导入 `Data.List` 模块，然后写一个告诉我们列表中有多少互不相同的元素的函数。

    import Data.List  
      
    numUniques :: (Eq a) => [a] -> Int  
    numUniques = length . nub  

当你导入 `Data.List` 时,  `Data.List` 导出的所有函数在全局命名空间可见。 `nub` 是一个在 `Data.List` 中定义的函数，拿一个列表，去除掉所有的重复元素。将 `length` and `nub` 结合， `length . nub` 产生了一个函数，相当于 `\xs -> length (nub xs)`.

在使用 GHCI 的时候也可以将函数导入到全局命名空间里。

    ghci> :m + Data.List  

导入多个模块也是可以的。

    ghci> :m + Data.List Data.Map Data.Set

如果你只需要其中的几个函数，这样做：

    import Data.List (nub, sort) 

你可以导入模块中的所有函数除了某一个。有时候不同的模块由命名冲突，你需要避开某个函数。比如说我们已经有nub函数了，那么，导入的时候只要这样：

    import Data.List hiding (nub)  

