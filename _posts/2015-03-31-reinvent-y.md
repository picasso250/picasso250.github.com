---
title: 重新发明 Y 组合子 Python 版
layout: post
---

关于Y组合子的来龙去脉，我读过几篇介绍的文章，相比之下，还是[王垠大神的著作](http://www.slideshare.net/yinwang0/reinventing-the-ycombinator)
最易懂。但他原来所有的语言是scheme，我写一个python版的，来帮助大家理解吧。

首先我们来看一个阶乘函数，然后调用它。

    def fact(n): return 1 if n == 1 else n * fact(n-1)
    fact(5)

然后将之用lambda表示

    fact = lambda n: 1 if n == 1 else n * fact(n-1)

现在我们看到在lambda定义中，存在fact的名字，如果我们想要无名的调用它，是不行的。如下

    (lambda n: 1 if n == 1 else n * fact(n-1))(5) # there is still `fact` name

我们想要将名字消去，如何消去一个函数的名字呢？将之变为参数，因为参数是可以随意命名的。

    fact = lambda f, n: 1 if n == 1 else n * f(f, n-1)
    fact(fact, 5)

嗯，很好，看起来不错。不过，要记住在 lambda 演算里面，函数只能有一个参数，所以我们稍微做一下变化。

    fact = lambda f: lambda n: 1 if n == 1 else n * f(f)(n-1)
    fact(fact)(5)

你可能会说我在做无用功，别过早下结论，我们只需要将 `fact` **代入**，就得到了完美的匿名函数调用。

    (lambda f: lambda n: 1 if n == 1 else n * f(f)(n-1)) (lambda f: lambda n: 1 if n == 1 else n * f(f)(n-1)) (5)

看，我们成功了，这一坨代码，是完全可以运行的哦。这个叫做 **穷人的Y组合子**。可以用，但是不通用，你要针对每个具体函数改造。

于是我们继续改造。我们将把通用的模式提取出来，这个过程叫做 **抽象**。

首先我们看到了 `fact(fact)(5)`，这里面要将fact重复写两次，根据DRY原则，我们可以这么做

    (lambda f: f(f)) (fact) (5)

然后，看看 fact 本身 我们看到了 `f(f)(n-1)`，这让我们很不高兴，我们本来是想这么做的：

    Y(lambda n: 1 if n == 1 else n * f(n-1)) # Y 可以将普通的函数转换成递归函数

我们发现 `f` 是个自由变量，这可不行。于是我们稍微改进一下：

    Y(lambda f: lambda n: 1 if n == 1 else n * f(n-1)) # Y 可以将参数转换成递归函数

你很震惊，你觉得这不可能。但是我们知道haskell大神已经做到了，所以我们继续思考。fact中的`f(f)`可以变成g，只要g的值是 `f(f)` 就行了。

    fact = lambda f: ( (lambda g: lambda n: 1 if n == 1 else n * g(n-1)) ( f(f) ) )

因为python不是懒求值，而是贪求值，为了避免stackoverflow，我们需要将 f(f) 做一个 $\eta$ 变换。

    f(f) == lambda v: f(f)(v)

现在

    fact = lambda f: ( (lambda g: lambda n: 1 if n == 1 else n * g(n-1)) ( lambda v: f(f)(v) ) )
    (lambda f: f(f)) (fact) (5)

我们可以看到，正中间的 `lambda g: lambda n: 1 if n == 1 else n * g(n-1)` 正好就是阶乘函数。

我们来最终写完它吧。

    (lambda f: f(f)) ( lambda f: ( (lambda g: lambda n: 1 if n == 1 else n * g(n-1)) ( lambda v: f(f)(v) ) ) ) (5)

最后，让我们瞻仰一下Y组合子的风采

    Y = labmda f: (lambda u: u(u)) ( lambda g: f ( lambda v: g(g)(v) ) )

名调用中，可以这么写：

$$
\lambda f.(\lambda u. u \ u) (\lambda x. f (x \ x))
$$

或者使用更经典的形式

$$
\lambda f. (\lambda x. f (x \ x)) (\lambda x. f (x \ x))
$$
