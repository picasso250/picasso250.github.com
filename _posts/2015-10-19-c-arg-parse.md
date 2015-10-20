---
title: C 语言参数解释
layout: post
---

最近在看 [C in four functions](https://github.com/rswier/c4)
它用4个函数（部分）实现了C解释器，其代码非常紧凑，值得一读。

我会在接下来的几篇文章里分析它的代码（如果我的拖延症碰巧不发作）。

首先看看它的用法：

    usage: c4 [-s] [-d] file ...

那么它是怎么处理参数的呢？

    int
        src,      // print source and assembly flag
        debug;    // print executed instructions
    int main(int argc, char **argv)
    {
      --argc; ++argv; // 处理了一个参数
      if (argc > 0 && **argv == '-' && (*argv)[1] == 's') { src = 1; --argc; ++argv; }
      if (argc > 0 && **argv == '-' && (*argv)[1] == 'd') { debug = 1; --argc; ++argv; }
      if (argc < 1) { printf("usage: c4 [-s] [-d] file ...\n"); return -1; }
      if ((fd = open(*argv, 0)) < 0) { printf("could not open(%s)\n", *argv); return -1; }
    }


这个简单的处理机制使得我们可以省略 -s 或者 -d 中的任意多个参数。
但美中不足的是不能调整 -s 和 -d 的顺序。当然，我们也可以使其成立。

    bool
        src   = 0,    // print source and assembly flag
        debug = 1;    // print executed instructions
    int main(int argc, char **argv)
    {
      --argc; ++argv; // 处理了一个参数
      while (argc > 0) {
        if (**argv == '-') {
          break;
        }
        if ((*argv)[1] == 's') { src = 1; }
        if ((*argv)[1] == 'd') { debug = 1; }
        --argc; ++argv;
      }
      if (argc < 1) { printf("usage: c4 [-s] [-d] file ...\n"); return -1; }
      if ((fd = open(*argv, 0)) < 0) { printf("could not open(%s)\n", *argv); return -1; }
    }

