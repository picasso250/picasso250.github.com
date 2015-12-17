---
title: 《快学Scala》笔记（第二章 控制结构和函数）
layout: post
---

1. 一切都有值，当然啦，因为这是函数式语言。块有值，值是最后一个表达式。因此，不用return关键字啦。
2. 也可以用分号。
3. void 类型是Unit 也就是 ()
4. if 表达式有值。s = if (x > 0) 1 else -1 如果类型不同 if (x>0) "" : 1 那么就是 字符串和Int 的公共超类型，就是Any 如没有else语句，那么就是 if (x>0) 1 相当于 if (x>0) 1 else ()
5. REPL有近视问题，可能找不到下一个else，使用:paste, 然后^D 就可以。
6. 如果你一行写不下，最后写 // 然后换行继续写（那注释用什么呢？）
7. 赋值语句本身的值是()，所以不要连续赋值！
8. 单参的 print() println() 可以用来打印，而且 printf 也可以用！（碉堡了）
9. readLine(String) readInt() readDouble() 等函数可以用来读取用户输入。但是readLine怎么不回显呢？
10. while循环可用。for循环是另一种形式 for (x <- 1 to 10) 不用指定x的类型（变量或者常量，其实很明显是变量）如果是off-by-one 那么用until方法吧
11. for (i <- 0 until s.length)
12. for (ch <- "hello") 你懂的
13. 没有break语句，怎么办呢，用函数，return，最好不用循环呀。
14. 笛卡儿积 for (i <-1 to 9; j <- 1 to 9) printf("%dx%d=%d\n", i, j, i * j)
15. 守卫（“你不能通过这里”）for (i <-1 to 3;j<- 1 to 3 if i != j) printf("(%d, %d)\n",i,j)
16. 可以在for里面赋值 for (i<-1 to 3; from=4-i;j<-from to 3 ) {printf("(%d, %d)\n",i,j)}
17. 可以yield，将生成Vector，就是传说中的for推导式（我估计是lazy evaluation的），生成的类型是和第一个生成器的类型是兼容的。for (c<-"abc";i<-1 to 3 ) yield (c+i).toChar 是字符串，而for (i<-1 to 2;c<-"abc" ) yield (c+i).toChar 生成的是Char的Vector。我觉得这个故事教育我们，要将字符串放在前面。这更像是个坑。而不是feature。哈哈。（虽然这肯定是个feature，但我觉得好高冷）
18. for后面的括号可以改成大括号，这样就可以不用分号而用换行了。用我们家乡的话说，这就是烧的不知道怎么好了。
19. 定义函数 def abs(x: Double) = if (x >= 0) x else -x 注意等号，很有haskell的感觉。只要函数不是递归的，就不需要指定返回类型（Haskell似乎使用Hindley-Milner方法推断返回值类型，但在面向对象的语言中，这是不可以的。）`def fact(x:Int):Int=if (x==0)1 else fact(x-1)*x` 指定返回值的类型也很easy
20. 默认值和python中一样 `def kuohao(str:String,left:String="[",right:String="]")` 然后也可以带名字调用，和python一样
21. 变长参数使用 `*` 和python一样，但是位置有不一样的 `def sum(args:Int*)={var s=0;for(i<-args)s+=i;s}` 因为要指定类型呀 args 的类型是 Seq
22. 解开的语法是 `sum(1 to 10: _*)` 那么来看看著名的递归定义吧 `def recurSum(args:Int*):Int=if (args.length==0) 0 else args(0)+recurSum(args.tail: _*)` （吐个槽，这个语法怎么记呢？为什么不能简单的反向呢？一点都不自然，人家python做的就很好，就连java本来用三个小点，也很好，很有语义化）
23. 有个小坑，但是我一点不像记
24. 用lazy声明的值将会是懒求值 lazy val a = 3*3 懒值是线程安全的
25. scala不需要异常声明。撒花！！！
26. 如果if语句有分支是抛异常的，那么类型就是另一个分支的类型（当然）
27. try语句采用模式匹配语法。

答案

1. def signum(a:Int) = if (a > 0) 1 else { if (a < 0) -1 else 0} 如何泛化类型？
2. () Unit
3. var x:Unit=();var y:Int=0;x=y=1
4. for(i<-10.to(0,-1))println(i)
5. def countdown(n:Int) = {for(i<- n.to(0,-1))println(i)}
6. var s=BigInt(1);for (i<- "Hello")s*=BigInt(i)
7. `var s=BigInt(1);"Hello".foreach((i)=> {s*=BigInt(i)});println(s)` 或 `"Hello".foldLeft(BigInt(1))(_*BigInt(_))` 或 `"Hello".aggregate(BigInt(1))(_*BigInt(_),(x,_)=>x)`
8. `def product(s:String) = s.foldLeft(BigInt(1))(_*BigInt(_))`
9. `def productRecur(s:String):BigInt = if (s.length==0) 1 else productRecur(s.tail)*BigInt(s(0))`
10. `def mypow(x:Double,n:Int):Double = if (n == 0) {
                                        1
                                    } else {
                                        if (n < 0) {
                                            1 / mypow(x,-n)
                                        } else {
                                            if (n % 2 == 0) {
                                                val y=mypow(x,n/2)
                                                y*y
                                            } else {
                                                x*mypow(x,n-1)
                                            }
                                        }
                                    }`

