---
title: 《快学Scala》笔记（第一章）
layout: post
---

0. 假设它和Java一样
1. REPL中tab键可以自动补全
2. 方法后面不需要跟着括号 "a".toUpperCase
3. 变量用var，常量用val：`var counter = 1` `val ansewer = 42`
3. 类型声明放在后面 val greeting : String = "hello"
5. 同时声明和初始化多个值或变量 val x, y = 10
6. 没有primative value，都是类 1.toString
7. range可以这么做 1.to(10)
8. StringOps 类自动包装 java.lang.String 类，所以可以用很多方法，比如 "Hello".intersect("World")
9. 同上，所以也有 RichInt RichDouble RichChar 包装对应类型，请多读文档
10. BigInt BigDecimal 对应任意大小和精度的数字。背后的类是 java.math.BigInteger 和 java.math.BigDecimal 但是有运算符重载！（操，这个牛逼了）
11. 不要使用强制类型转换，使用方法 99.4.toInt 99.toChar （编译器作者，摸摸头）
12. 运算符重载和C++是一样的！
13. a+b实际上是 a.+(b) （方法）而 1 to 10 实际上是 1.to(10) 对单参函数的语法糖，让我想到small talk
14. 没有++和--操作符
15. 引入 import scala.math._ （_是通配符，类似*）后，可以使用各种数学函数 sqrt pow min 等
16. Scala没有静态方法，但有单例对象 singleton object 每个类都对应一个伴生对象 companion object，比如 BigInt 类的伴生类。 BigInt.probablePrime(100, scala.util.Random)
17. "hello"(4) 等于 "hello"[4] ( 是 括号方法的重载，一般对应 apply方法。BigInt("121212121212") 生成大整数，Array(1,4,9,33) 生成Int数组
18. Scaladoc 在 www.scala-lang.org/api

答案

1. 很多
2. 一点
3. 不知道
4. StringOps
5. RichInt
6. BigInt(2) pow 1024
7. import BigInt.probablePrime; import scala.util.Random
8. BigInt.probablePrime(100, Random).toString(32)
9. a(0) a(a.length)
10. 不知道

