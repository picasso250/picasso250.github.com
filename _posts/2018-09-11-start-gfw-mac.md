---
title: Mac 翻墙
layout: post
---

安装brew

    /usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
    
安装 pip

    $ brew update
    $ brew install openssl
    $ brew install python3

安装 ss

    pip3 install shadowsocks
    
并启动

    sslocal -s 172.96.192.15 -p 8989 -k "x4stcfuheSS1A\!Jazzf"
