---
title: 坏到好处
layout: post
---

[The Rise of Worse is Better](http://www.dreamsongs.com/RiseOfWorseIsBetter.html)

首先介绍一下 MIT/Stanford 的设计风格，这是一种优雅的、学院派的设计风格，一个字概括：好。一个好的设计，必须具有如下特质：

- **简洁** ——设计必须简洁，不论是实现还是接口。接口简洁比实现简洁更重要。

- **正确** ——在所有可预见的方面都必须正确。绝不容忍不正确。

- **一致** ——设计必须一致。为了避免不一致，可以牺牲一些简洁。一致性和正确性同等重要。

- **完全** ——设计必须覆盖尽可能多的情况。所有能够预料到的合理情况必须被满足。简洁性也比不上完全性。

大多数人都会同意上述四点。

然而，**坏到好处** 的理念有点不同。

- **简单** ——设计必须简单，不论是实现还是接口。实现简单比接口简单更加重要。简单性是整个设计过程中最重要的考虑因素。

- **正确** ——在所有可预见的方面都必须正确。简单点要比正确稍好一点。

- **一致** ——设计不能太不一致。在某些情况下，为了简单，一致性可以被牺牲一点。如果一个部分会带来实现上的复杂或者不一致，只为了满足一些不常见的情形，这种部分可以被简单丢弃。

- **完全** ——设计必须覆盖尽可能多的情况。所有能够预料到的合理情况应该被满足。和其他三者比起来，完全性没有那么重要。实际上，一旦威胁到简单性，完全性就不足道了。如能在保持简单的前提下，为了满足完全性，可以牺牲一致性（尤其是接口的一致性）。

早期的 Unix 和 C 就是这种设计风格，我称之为 **新泽西流**。上面我有意黑了这种风格一下，你会觉得这种理念是 **坏** 的。

然而，我觉得这种 **坏** 更有生命力。

我讲一个故事，你们感受一下这两种哲学的区别。

两个人在一起讨论操作系统的问题。一个来自 MIT，一个来自伯克利（但是在 Unix 上工作）。MIT 的人通读了 Unix 的源码，他对 Unix 是如何解决 PC loser-ing 问题的很感兴趣。这个问题是这样的：在用户程序发起一个长时间的带有状态的系统调用的时候（如 IO 操作），如果此时一个中断进来了，用户程序的状态（上下文）应该被保存。系统调用通常只是一条指令，用户程序的 PC 不能代表此时的状态。系统要么后退，要么完成这个调用。正确的做法应该是后退，将用户程序的 PC 保存在此次系统调用中，以便最后重入此次系统调用。

MIT 人在源码里没有看到任何处理此种情况的代码。他问新泽西人这是怎么回事。新泽西人回答道：Unix 的作者们知道这个问题，这个问题的处理手段就是系统调用立刻结束，但是会返回一个错误码，表示此次操作失败。一个正确的用户程序，应该检测错误码，自行决定是否重新发起系统调用。MIT 人不喜欢这个解决方案，因为这个解决方案 **不负责**。

新泽西人则说，Unix 的解决方案是正确的，因为 Unix 的设计哲学就是简洁，就这件事情来讲，正确的方案太复杂了。而且，程序员额外写个 test and loop 很简单的。MIT 人指出，实现确实很简洁，但是这个功能的接口太复杂了。新泽西人说，在鱼和熊掌之间，Unix 选择了实现的简单，而非接口的简单。

MIT 人听了这句话，说，有时候，柔软的肌肉要一个强壮的厨子去做，你必须很努力，才能看起来毫不费力。但是新泽西人没文化，理解不了这句话。（我也不知道我理解吗）

现在，我想说，**坏才是好**。C 是为了写 Unix 设计出来的编程语言，使用的就是新泽西流。所以，给 C 语言写个编译器就很简单。有些人称 C 为汇编语言的精选集。早期的 Unix 和 C 都具有简单的接口，容易移植，占有资源少。提供了大约 50%-80% 的功能。

有一半的电脑都在平均水平以下（更慢或者更小）。但 Unix 和 C 在它们上面也能很好的工作。 坏到好处 的哲学的本质就是实现的简单高于一切，也就意味着 Unix 和 C 便于移植到这些机器上面。因此，如果 Unix 和 C 的 50% 的功能可以让一个人满足，那么它们就会遍地发芽。历史已经证明。

Unix 和 C 是终极的电脑病毒。

**坏到好处** 哲学的进一步的好处是程序员可以酌情牺牲安全性、便利性，不自找麻烦，来获得更好的性能、占用更少的资源。用新泽西流写成的程序在大机器和小机器上都能运行的很好，也有很好的移植性，毕竟是写在病毒上的。

最初的病毒得是好的。只要这样才能保证迁移的顺畅。一旦病毒传播开来，就会有变得更好的压力，或许是将功能提升到 90%，但此时用户已经习惯这个坏到好处的东西。因此，坏到好处的软件首先会得到认可，其次会让它的用户习惯简陋，再次它会进化到一个基本正确的东西。一个具体的例子是：1987 年的时候，Lisp 编译器已经和 C 编译器一样好了，但是有更多的编译器高手想要优化 C 编译器。

好消息是到 1995 年，我们就能拥有一个优秀的操作系统和一个优秀的变成语言，坏消息是，它们会是 Unix 和 C++。

坏到好处的最后一个好处是：因为新泽西流的语言和系统不足以建造一个复杂而庞大的软件，大型系统必须被设计为可以重复使用的组件。就这样，一个集成的传统出现了。


How does the right thing stack up? There are two basic scenarios: the big complex system scenario and the diamond-like jewel scenario.

The big complex system scenario goes like this:

First, the right thing needs to be designed. Then its implementation needs to be designed. Finally it is implemented. Because it is the right thing, it has nearly 100% of desired functionality, and implementation simplicity was never a concern so it takes a long time to implement. It is large and complex. It requires complex tools to use properly. The last 20% takes 80% of the effort, and so the right thing takes a long time to get out, and it only runs satisfactorily on the most sophisticated hardware.

The diamond-like jewel scenario goes like this:

The right thing takes forever to design, but it is quite small at every point along the way. To implement it to run fast is either impossible or beyond the capabilities of most implementors.

The two scenarios correspond to Common Lisp and Scheme.

The first scenario is also the scenario for classic artificial intelligence software.

The right thing is frequently a monolithic piece of software, but for no reason other than that the right thing is often designed monolithically. That is, this characteristic is a happenstance.

The lesson to be learned from this is that it is often undesirable to go for the right thing first. It is better to get half of the right thing available so that it spreads like a virus. Once people are hooked on it, take the time to improve it to 90% of the right thing.

A wrong lesson is to take the parable literally and to conclude that C is the right vehicle for AI software. The 50% solution has to be basically right, and in this case it isn’t.

But, one can conclude only that the Lisp community needs to seriously rethink its position on Lisp design. I will say more about this later....