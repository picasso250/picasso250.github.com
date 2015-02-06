---
title: 译.lambda演算笔记.草
layout: post
---

[http://ryanflannery.net/research/logic-notes/Church-CalculiOfLambdaConversion.pdf](http://ryanflannery.net/research/logic-notes/Church-CalculiOfLambdaConversion.pdf)

# 0 介绍

如下对集合论做了简介.

## 0.1 数

我们知道空集 $\emptyset$, 用它来表示自然数 0. 其他的自然数 $n$ 表示为之前的所有自然数的集合.

$$
\begin{align} 
0_{set} & = \emptyset \tag{0.1.0} \\
1_{set} & = \{\emptyset\} \tag{0.1.1} \\
2_{set} & = \{\emptyset,\{\emptyset\}\} \tag{0.1.2} \\
3_{set} & = \{\emptyset,\{\emptyset\},\{\emptyset,\{\emptyset\}\}\} \tag{0.1.3} \\
& \vdots \\
n_{set} & = \{0_{set},1_{set},2_{set},...,n-1_{set}\}
\end{align}
$$

## 0.2 有序列表(元组)

就是 **元组**

## 0.3 关系

也是用集合来表示

$$
\le \ = \ \{(a,b) \ | \ \text{$a$ is less than or equal to $b$}\}
$$

二元关系 $\le$ 可以被表示为如下集合, 其中所有元组都符合此关系.

$$
\begin{align} 
\le_{set} = \{ & (0,0),(0,1),(0,2),(0,3),\cdots \\
& (1,1),(1,2),(1,3),(1,4),\cdots \\
& (2,2),(2,3),(2,4),(2,5),\cdots \\
& \vdots \\
\} &
\end{align}
$$

那么 $x\le y$ 可以被重述为 $(x,y)\in\le$.

## 0.4 函数

类似的, 我们可以将 **函数** 也表示为集合. 一个 $n$ 元函数可以表示为 $n+1$ 元关系. 我们看一个众所周知的二元函数 $+$ (加法函数), 经常写为 $a+b=c$. 我们可以表示为三元关系,

$$
+_{set} = \{(a,b,c)| \text{the sum of $a$ and $b$ is equal to $c$}\}
$$

$+$表示为如下集合.

$$
\begin{align} 
+_{set} = \{ & (0,0,0),(0,1,1),(0,2,2),(0,3,3),\cdots \\
& (1,0,1),(1,1,2),(1,2,3),(1,3,4),\cdots \\
& (2,0,2),(2,1,3),(2,2,4),(2,3,5),\cdots \\
& \vdots \\
\} &
\end{align}
$$

## 0.5 其他都是不重要的细节

如此一来, 在集合论中, 当我们在讨论数, 有序列表, 关系和函数时, 我们 **实际上** 在讨论集合. **所有东西都是集合**, 我们从基本的集合开始, 构建更复杂的东西, 也是集合.

虽然这种表述很简单, 但不是太符合直觉, 尤其是函数. <u>lambda演算</u>的亲爸爸 <u>丘奇</u> 认为函数更像 **动作**, 你给函数$f$一个输入, 这个函数做一些事情(根据函数的定义), 最后输出一个东西. 丘奇的lambda演算, 就是一个反映这种过程的系统. 和集合论不同, 在lambda演算中, 基本的东西**是函数**, 所有的其他东西(包括数, 有序列表, 关系, 和集合)都用函数来表示.

与集合论相比, lambda演算对东西的表述可能有点抽象, 反直觉, 但能和算法一起愉快的玩耍.

# 1 介绍

## 1.1 lambda演算中的函数

lambda演算(此后用 _λ演算_)中的基础概念是函数, 尽管不是一个全新的概念, 但正是 _λ演算_ 对待函数的方式使得它有别于其他形式系统.

**定义 1.1.1** (函数). **函数** 是一种规则, 对一些给定的东西(作为**参数**), 可以获得另一个东西(作为函数对这个**参数**的**值**). 简单点说, 函数是一种**动作**, 可以给应用在一个东西(参数)身上, 来获得另一个东西(值). 一个函数并不需要对所有的东西都有用; 某个函数可能只对某一个集合中的元素有意义, 这就叫做 **参数区间**(也就是定义域). 对所有可能的参数应用这个函数, 得到的所有可能的值的集合, 叫做 **值的区间**(也就是值域).

不像集合论, 集合论将函数看作有序列表. 在$\lambda$演算中, 函数是动作, 你将函数应用在神马东西(参数)上面, 会产生其他的东西(值). 在纯粹的$\lambda$演算中, 所有东西都看成函数, 即使是数, 我们即将看到.

**记法 1.1.2** 如 $f$表示一个函数, 那么$(f\alpha)$ 表示$f$对参数$\alpha$的值. 我们也接受如下简写: $(f\alpha)=f\alpha$.

注意在$\lambda$演算中, 没有什么可以阻挡一个函数应用于自身. 就是说, 对于一个函数 $f$, 没有什么能够阻挡 $f$在自己的定义域内.

接下来我们定义两个基本函数...

**定义 1.1.3** ($I$, <u>恒等函数</u>). $I$的定义如下: $(Ix)$ 就是 $x$, 不论 $x$ 是什么.

**定义 1.1.4** ($H$). $H$被定义为永远返回恒等函数的函数. 也就是, $(Hx)$就是$I$, 不论 $x$ 是什么.

### 1.2 相等性的记号

$\lambda$演算中的函数概念允许用两种不同的方式定义相等性. 第一种只和函数的结果相关, 第二种和函数如何动作相关.

**定义 1.2.1** (外延相等). 两个函数, $f$和$g$被认为是外延相等的, 如果他们的参数范围相同, 并且对每个范围内的参数 $\alpha$, $f(\alpha)$和 $g(\alpha)$相同.

令 $f=x+1$且 $g=(x+3)-2$. 显然, 对每个可能的数值参数 $\alpha$, $f(\alpha)=g(\alpha)$. 虽然 $f$和$g$的形式不同. 这也为我们带来下一种相等性的记号...

**定义 1.2.2** (内涵相等). 我们说两个函数内涵相等, 如果它们外延相等, 并且对相同的参数, 它们产生值的方式也相同.

## 1.3 几种变量的函数

λ演算中, 函数可以有多余一个的参数. 然而, 意义和我们之前所讲的函数记号中的参数有些微不同. 在λ演算中, 一个$n+1$元的函数, 实际上是一个一元函数, 返回一个n元函数.

**记号 1.3.1** 如下简写可以: $((fa)b) = (fab) = f \ ab$.

注意以下: 令$f$表示一个3元函数. 那么$f \ abc$表示$f$应用于$a$, $b$和$c$上时产生的值. 根据对函数记号的解释, 注意$f \ a$是一个小函数, 这个小函数是一个二元函数, $f \ ab$是一个小小函数, 这个小小函数是一个一元函数. $f \ abc$表示一个实际的值(或许是一个任意多元的函数). 从这个角度来讲, 我们可以对任意函数提供任意多参数.

**定义 1.3.2** ($K$, 常函数). $K$定义为 $Kxy$ 是 $x$, 不论$x$和$y$是什么.

注意$KII$ 是 $I$, $KHI$ 是 $H$. 更有趣的是 $KI$ 是 $H$, 而$KK$, 简单点说, 是$K$.

蓝后, 我们定义这些函数...

**定义 1.3.3** ($T$). $Txf$ 是 $f(x)$

**定义 1.3.4** ($J$). $Jfxyz$ 是 $fx(fzy)$

**定义 1.3.5** ($B$, 积函数). $Bfgx$是$f(gx)$

**定义 1.3.6** ($C$, 颠倒函数). $Cfxy$是$f(yx)$

**定义 1.3.7** ($D$). $Dx$是$xx$

**定义 1.3.8** ($W$). $Wfx$是$fxx$

**定义 1.3.9** ($S$). $Snfx$是$f(nfx)$

**注**: 这个和 _Barendregt_ 的教科书中的$S$绝不一样, 那里, $Sfgx$是$fx(gx)$.

## 1.4 摘要

**记号 1.4.1** 设$M$是一个**表达式**, 包含一个自由变量 $x$(即, $M$的含义取决于$x$的值). 那么 $\lambda xM$ 就是一个函数, 其对一个参数 $\alpha$的值, 就是 用 $\alpha$替换$M$中所有$x$.

注意, 如果 $M$ 不包含 符号 $x$, 则$(\lambda xM)$是一个常量, 等于 $M$. 而且, 虽然 $x$在$M$中是自由的, 但它被**绑定**在$(\lambda xM)$中, 所以 $\lambda x$是不完全的表达式($\lambda x 自己没有任何意思)!

注意上述表达式和函数的区别. 设有以下两个表达式, $M=(x+x)$和$N=(y+y)$. 等式$M=N$表示一个x和y的关系, 成立与否取决于 x 和y.

而 $(\lambda xM)=(\lambda yN)$:

$$
(\lambda x(x+x))=(\lambda y(y+y))
$$

这个等式表达的意思完全不同; 它表示这两个函数$(\lambda xM)$和$(\lambda yN)$是等同的(真), 没有提及x和y的值.

当我们将 $\lambda x$ 添加到表达式 $M$ 前面的时候, 我们就创造了一个函数. 此操作被称为 _抽象_, 符号 $(\lambda)x$ 被称为 _抽象符_(译者: 越来越有魔法原理的感觉了呢).

**记法 1.4.2** 表达式 $(\lambda x(\lambda yM))$ 简写为 $\lambda xy.M$, 表示一个函数, 其对参数x的值是 $(\lambda yM)$. (译者: f对参数x的值, 意思就是当f应用于x时产生的值)

# 2 lambda转化

## 2.1 基本符号和公式

现在我们可以介绍λ演算的语言了. 它由以下基本符号构成(称之为**不恰当的符号**(译者: improper symbol怎么翻译?)):

$$
\lambda(\text{the abstraction operator}),(,)
$$

还有一个无限的**变量**列表:

$$
a,b,c,\cdots,x,y,z,\overline a, \overline b, \cdots, \overline{\overline a}, \cdots
$$

**定义 2.1.2** (公式). 公式是原始符号构成的任何有限序列.

**定义 2.1.2** (良构公式). 良构公式是一个公式, 其中出现的所有变量都是自由或者绑定的, 根据以下规则:

1. 变量 $x$ 是一个良构公式, 此公式中的x是自由的.
2. 如果 $M$ 和 $N$ 是良构的, 那么 $(MN)$ 也是良构的. 所有 $M$ 或 $N$中出现的自由(或绑定)的变量在$(MN)$中也是自由(或者绑定)的.
3. 如果M是良构的, 包含自由变量$x$, 那么$(\lambda xM)$也是良构的, 其中出现的所有x都是绑定的.

**记号 2.1.3** 在λ演算中, 黑体大写字母($M,N,A,B\cdots$)表示公式, 黑体小写字母($a,b,c,\cdots$)表示变量.

除非特别说明, 所有公式都是良构的.

我们现在引入一个记号, 当学习替换的概念的时候我们会用到...

**记号 2.1.4** 令 $S^x_N M\|$ 表示一个公式, 此公式是当$M$中的所有$x$都被$N$替换后的结果.

这个和 Barendregt 的语法 $M[x:=N]$是一样的. 因为Barendregt的语法明显更简明, 此后使用他的语法.

$\rightarrow$可以被读作**代表**, 可以用来展开公式, 用东西的意义替换东西的文本. 举例, 上述定义的函数 $I$表示为公式 $\lambda \alpha\alpha$, 所以...

$$
I\rightarrow(\lambda \alpha\alpha)
$$

相应的

$$
(II)\rightarrow((\lambda \alpha\alpha)(\lambda \alpha\alpha))
$$

现在有如下定义:

**定义 2.1.5**

$$
\begin{align} 
[M+N]       & \rightarrow(\lambda a(\lambda b((Ma)((Na)b)))) \\
[M\times N] & \rightarrow(\lambda a(M(N))) \\
[M^N]       & \rightarrow(NM) \\
\end{align} 
$$

(译者:如上定义我是不明白的)

**记号 2.1.6** 方便起见, 大公式中的括号经常被省略. 我们要记住 _λ演算_ 是 _左结合_ 的. 也就是说, $MNS$其实是$((MN)S)$.

类似的, $x+y+z$其实是$[[x+y]+z]$.

当对这种形式$(\lambda xM)$ 的表达式求值时, 你可能产生困惑. 如果令$M=xyz$, 那么有$(\lambda xxyz)$, 这就产生了歧义. 抽象符在哪里结束? 表达式在哪里开始? 为了解决这个问题, 我们使用 `.`.

**记号 2.1.7** 当省略括号可能引起歧义的时候, 将 `.` 放在小括号或者中括号的左边. 解释规则如下: 将点替换成左括号, 右括号放在尽可能远的右方. 举例: $(\lambda x.MN)\leftarrow(\lambda x(MN))$, $(\lambda xy.MN)\leftarrow(\lambda xy(MN))$, $(\lambda x.\lambda y.MN)\leftarrow(\lambda x(\lambda y(MN)))$

到现在为止, 我们已经形式上建立了 _λ演算_. 已定义的函数如下:

$$
\begin{align} 
I & \leftarrow\lambda a.a \\
H & \leftarrow\lambda a.I \\
T & \leftarrow\lambda xf.fx \\
J & \leftarrow\lambda fxyz.fx(fzy) \\
B & \leftarrow\lambda fgx.f(gx) \\
C & \leftarrow\lambda fxy.fyx \\
D & \leftarrow\lambda x.xx \\
W & \leftarrow\lambda fx.fxx \\
S & \leftarrow\lambda nfx.f(nfx) \\
\end{align}
$$

## 2.2 转换 ##

现介绍在良构的公式上的基本操作: _转化_. 尽管有很多形式的转化, 我们先介绍三种:

* I 给定一个公式, 我们可以对任意部分$M$做替换操作$M[x:=y]$, 只要x不是M的自由变量且y不在M中. 注意: 我们所做的只是替换名称. 这个转化一般称之为 _α转化_.
* II 给定一个公式, 我们可以替换其中任意一个 $((\lambda xM)N)$ 为 $M[x:=N]$, 只要M的绑定变量不包含x, 也不包含N. 应用这条规则, 称为 _收缩_. 这个转化方式通常称为 _β-转化_.
* III 给定一个公式, 我们可以替换其中任意一个 $M[x:=N]$ 为 $((\lambda xM)N)$, 只要 $((\lambda xM)N)$ 是良构的, 且N的绑定变量和x不同, 也和N的自由变量不同. 注意: 前一个规则是缩写, 这个规则是扩写.

**定义 2.2.1** 一个公式的 _部分_ 指的是任何连贯而良构的公式, 且不从 $\lambda$ 之后立即开始的.

注意: _规则I_ 可以将 
