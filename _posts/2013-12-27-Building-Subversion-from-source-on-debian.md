---
title: Building Subversion 1.7 from source on Debian 7 wheezy
layout: post
---

首先下载1.7的源码

于此同时，可以安装依赖

    apt-get build-dep subversion libsvn1 libsvn-dev libapache2-svn

然后就是安装编译老三句了：

    ./configure
    make
    make install

参考：
[Building Subversion from source on debian](http://evertpot.com/76/)
