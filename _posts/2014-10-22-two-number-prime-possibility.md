---
title: 任意选取两个整数，互质的概率是多少？
layout: post
---

任意选取两个整数，互质的概率是多少？

解答：

$gcd(a, b)=d$ ，当且仅当 $d|a$ , $d|b$ , $gcd(\frac{a}{d}, \frac{b}{d})=1$

设 $gcd(a, b)=1$ 概率为 $p$，则 $gcd(a,b)=d$ 的概率是 $\frac{p}{d^2}$

$$
\sum_{n=1}^{\infty}\frac{p}{n^2} = 1
$$

于是可以求得 $p=\frac{6}{\pi^2}$

---

那么，问题来了，如何求出

$$
\sum_{n=1}^{\infty}\frac{p}{n^2} = 1
$$

注意到 $p$ 是一个常数。

$$
\sum_{n=1}^{\infty}\frac{p}{n^2} 
= p\sum_{n=1}^{\infty}\frac{1}{n^2}
$$