---
title: GitHub Pages jekyll 的 highlighter
layout: post
---

**更新：**

[GitHub Pages now faster and simpler with Jekyll 3.0](https://github.com/blog/2100-github-pages-now-faster-and-simpler-with-jekyll-3-0) Says that

> GitHub Pages now only supports [Rouge](https://github.com/jneen/rouge)

我之前曾经写过一篇文章，讲述 [Windows上安装Jekyll 的血泪史](/2014/11/08/cygwin-ruby-gem.html)，现在 3.0 的 Jekyll 就不用那么麻烦了。只要安装 ruby，然后

    gem install jekyll

就可以了。（当然你可能需要梯子）

**原文**

GitHub Pages 使用的 jekyll 版本是 2.4.0. 不能使用

    highlighter: rouge
