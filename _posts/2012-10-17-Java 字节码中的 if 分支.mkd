Java 字节码里面的 if 族指令包含很多条指令，但都是一个套路：

> if 如何 则 跳过 多少条指令

比如 ifeq 指令的作用是：弹出栈顶元素，并将之和0比较，如果相等，就跳到指定的位置。再比如 ifne ，作用是如果不等于 0 ，则跳转。（记住这两个指令，下面将会用到）

先来看一个最简单的情形：
<pre lang="Java">
if (will) {
    doSome();
}
</pre>

Java 的字节码的伪代码如下：

<pre>
0 iload_will;
1 ifeq skip to 3; // 注意这里，是等于 0 ,则跳转到 return 语句处
2 invoke doSome;
3 return;
</pre>

看到了没，在源码里面，我们写的是如果 will 为**真**，则执行下面的语句块。而在 Java 字节码中，就变成了如果 will **不为真**（即等于 0 ），则跳过下面的语句块。

让我们来看看真实的 Java 世界。

源码：

<pre lang="Java">
public static void Main(String [] args) {
    boolean will = true;
    if (will) {
        System.out.println(1);
    } 
}
</pre>

字节码：

<pre>
 0 iconst_1
 1 istore_1
 2 iload_1
 3 ifeq 13 (+10)
 6 getstatic #2 &lt;java/lang/System/out Ljava/io/PrintStream;&gt;
 9 iconst_1
10 invokevirtual #3 &lt;java/io/PrintStream/println(I)V&gt;
13 return
</pre>

指令 3 的意思是，如果栈顶元素（也就是 will ）等于 0 ，则跳到指令 13 ，也就是退出函数。反之，如果 will 不等于 0 ，则什么都不做，那么下面的指令 6 9 10 13 都将有机会执行。

接下来看看带有 else 分支的 if 语句。

<pre lang="Java">
if (will) {
    do_a();
} else {
    do_b();
}
</pre>

那么伪代码应该是这样的：

<pre>
0 iload_will;
1 ifeq skip to 4;
2 invoke do_a;
3 goto 5;
4 invode do_b;
5 return;
</pre>

注意，如果走了 2 这条路，则一定会跳过 4 。

还有……

如果有这种形式，该如何呢？

<pre lang="Java">
if (a && b) {
    doSomething();
}
</pre>

第一反应，是先计算 a && b 的值，这样，我们就把问题转化成我们已经解决的问题了。但在寸土寸金的字节码里面，还有更节省空间的做法。你能想出来吗？



请看：

<pre>
0 iload_a;
1 ifeq jump to 5;
2 iload_b;
3 ifeq jump to 5;
4 invoke doSomething;
5 return;
</pre>

1 3 行有两个测试，重点在于：只有 a 和 b 都是 true ，才能通过这两个测试，否则，就不会执行 代码块。

那么这种形式呢？

<pre lang="Java">
if (a || b) {
    doSomething();
}
</pre>

我们可以依据上面的思路给出答案：

<pre>
0 iload_a;
1 ifne jump to 4;
2 iload_b;
3 ifne jump to 4;
4 goto 6;
5 invoke doSomething;
6 return;
</pre>

除非 a 和 b 都是 false ，否则就执行代码块。

但另一种更节省空间的方式：

<pre>
0 iload_a;
1 ifne jump to 4;
2 iload_b;
3 ifeq jump to 5;
4 invoke doSomething;
5 return;
</pre>

你想出来了吗？反正我是没想到，看了反编译的代码才明白……叹……

上面的伪代码等价于

<pre lang="Java">
if (a) {
    if (!b) goto ifend;
	doSomething();
}
ifend:
return;
</pre>
