---
title: Python 和 Ajax
layout: post
---

朋友问了我一个问题：如何用Python实现页面部分刷新。

其实这是一个不太“正确”的问题，页面的部分刷新使用的技术是Ajax，与Python无关。但用Python做网页后端也是一项值得深入的技术。

下面我就来分两个问题讲述：

1. 如何用Python做网页。
2. 如何用Ajax实现页面的部分刷新。

### 如何用Python 做网页

首先，我们需要开启一个WebServer，实现这个的最简单的方式是：

```
 python -m http.server --cgi 80
```

