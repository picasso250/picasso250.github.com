---
title: c 语言参数解释
layout: post
----

最近在看 [C in four functions](https://github.com/rswier/c4)
它用4个函数（部分）实现了C解释器，其代码非常紧凑，值得一读。

我会在接下来的几篇文章里分析它的代码（如果我的拖延症碰巧不发作）。

首先看看它的用法：

```
usage: c4 [-s] [-d] file ...
```

那么它是怎么处理参数的呢？

```c
int
    src,      // print source and assembly flag
    debug;    // print executed instructions
int main(int argc, char **argv)
{
  int fd, bt, ty, poolsz, *idmain;
  int *pc, *sp, *bp, a, cycle; // vm registers
  int i, *t; // temps

  --argc; ++argv;
  if (argc > 0 && **argv == '-' && (*argv)[1] == 's') { src = 1; --argc; ++argv; }
  if (argc > 0 && **argv == '-' && (*argv)[1] == 'd') { debug = 1; --argc; ++argv; }
  if (argc < 1) { printf("usage: c4 [-s] [-d] file ...\n"); return -1; }
}
```

