---
title: Python 和 Ajax
layout: post
---

朋友问了我一个问题：如何用Python实现页面部分刷新。

其实这是一个不太“正确”的问题，页面的部分刷新使用的技术是Ajax，与Python无关。但用Python做网页后端也是一项值得深入的技术。

假设我们需要做一个网页，这个网页可以实时的显示（大致的）访问次数。并会实时更新这个数字。该如何做呢？

下面我就来分两个问题讲述：

1. 如何用Python做网页。
2. 如何用Ajax实现页面的部分刷新。

### 如何用Python 做网页

首先新建一个文件夹作为我们的工作目录。

然后，我们需要开启一个WebServer，实现这个的最简单的方式是（Python3）：

    python -m http.server --cgi 80

如果你使用的是Python2，事情会相对麻烦一些。请参见：
https://docs.python.org/2.7/library/cgihttpserver.html

如果你使用的是Windows，则会更加麻烦一些。请参见：
https://docs.python.org/3.3/library/http.server.html

这样就开启了一个http的服务器，现在在浏览器中输入网址就能访问了。

如果是在自己机器上开发，就可以用网站 `localhost` 访问。

如果你看到 `Directory listing` 字样，就说明你成功了。

现在，让我们写一个示例页面 `index.html`，内容如下

    <p>hello world</p>

然后再访问页面，会发现页面内容变成了 `hello world`

### Ajax 初步

在页面上如何向服务器发送请求呢？有很多种方法，但这里为了简便，我们使用jQuery。

首先下载jQuery，下载地址是
http://lib.sinaapp.com/js/jquery/2.0.3/jquery-2.0.3.js

将之放入工作根目录。

在页面后面加入一行

    <script src="/jquery-2.0.3.js"></script>

这一行的作用是告诉浏览器使用jQuery（也就是运行jQuery的构造函数）。

下面我们继续写

    <script>
        $.get('/uri')
    </script>

中间一行就是脚本了，这一行的作用是向服务器发送一个访问为GET的ajax请求，路径为 `/uri`。关于这一函数的详细用法，请看
http://api.jquery.com/jQuery.get/

现在**按F12打开控制台**，应该可以看到红色的一行提示。点开这个网址，你可以看到具体的报错信息：404。这是很正常的，因为我们还没写这个脚本呢。

### 动态请求

现在在根目录下新建一个文件夹 `htbin` 然后在里面新建一个脚本，这个脚本的作用就是输出访问次数。因此，我们将之命名为 `count.py` 内容如下：

```
#!python3

import os

print('Content-Type: text/html')
print()

def write_count(n):
    with open(filename, "w") as fh:
        fh.write(n)

c = 0
filename = 'count.txt'
if os.path.exists(filename):
    with open(filename) as f:
        c = int(f.read())
next = str(c+1)
write_count(next)

print(next)
```

现在访问 `/htbin/count.py` 你会发现数字1，如果你不断刷新，数字会不断增长。这种会不断改变内容的请求，我们称之为动态请求，淘宝的订单，百度的搜索结果，都是动态请求。

现在目录文件的结构如下：

    -
    |-- index.html
    |-- jquery-2.0.3.js
    |-- htbin
        |-- count.py

### 集成

最后的步骤就是更改网页内的脚本

    $.get('/htbin/count.py', function(ret) {
        console.log(ret)
    })

可以看到，和之前的版本相比，我们不仅更改了请求地址为 `/htbin/count.py` 还增加了一个函数作为参数，这个函数就是在请求完成时会被调用的。

现在刷新首页，打开控制台，会看见一个数字。就是 `console.log(ret)` 打印出来的。

接下来，文件的最上方增加我们的重头戏

    Pager View Count: <span id="count"></span>

这个`<span id="count"></span>`就声明了一个位置，其id为count，我们接下来继续修改脚本：

    $.get('/htbin/count.py', function(ret) {
        $('#count').text(ret)
    })

`$` 是选择器，`#count`是选择符，意思是选择id为count的标签，`text()`方法的作用是设置内部文本。

好了，现在刷新看一下，你就会发现一个动态的网页。
