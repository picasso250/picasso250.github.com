---
title: Ruby Gem install Jekyll on Cygwin
layout: post
---

在 Cygwin 中安装 Ruby 和 Gem 是非常容易的，可以直接安装。
但在安装 Jekyll 的过程中遇到了问题。

    gem install jekyll

时报错。加入 debug 参数再运行一下。

    gem install jekyll --debug

看到报错：

    Exception `ArgumentError' at /usr/share/ruby/2.0.0/win32/registry.rb:173 - invalid byte sequence in UTF-8

估计是 Windows 平台的编码问题，将 173 行

    msg = msg[0, len].force_encoding(Encoding.find(Encoding.locale_charmap))

改成

    msg = msg[0, len].force_encoding(Encoding::GBK)

将自动探测的编码（UTF-8）强制设置成 GBK。

如果在安装过程中发现报错，找不到make，就把make安装上就可以了。

安装好 jekyll 后，直接运行 jekyll 提示找不到文件，运行

     ~/.gem/ruby/gems/jekyll-2.5.1/bin/jekyll

就可以了。

如果你使用了代码高亮，可能会有报错：

    Liquid Exception: No such file or directory - C:\Windows\system32\cmd.exe in _                                                                                      posts/2012-09-15-if-in-Java-opcode.md/#excerpt

我安装了 python 和 Pygments 之后，依然出现这个报错，据说是兼容性问题。又听说 [jekyll现在支持一个ruby native 的语法高亮](http://yizeng.me/2013/05/10/setup-jekyll-on-windows/)，于是我就在 _config.yml 中添加了

    highlighter: rouge

然后

    gem install rouge

于是就好了。