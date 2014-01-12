---
title: ibus-rime 1.0 中洲韵输入法 debian 7 安装手记
layout: post
---

你可以执行这个命令，一键安装

    curl https://gist.github.com/picasso250/8056427/raw/77367f5fa9e440d62d2ba164995910ee172d4c73/install_rime1.0.sh | bash

或者，你可以詳細的看一下安裝過程。

    # 安裝編譯工具
    sudo apt-get install build-essential cmake

    # 安裝程序庫
    sudo apt-get install libopencc-dev libz-dev libibus-1.0-dev libnotify-dev

    sudo apt-get install libboost-dev libboost-filesystem-dev libboost-regex-dev libboost-signals-dev libboost-system-dev libboost-thread-dev
    # 如果不嫌多，也可以安裝整套Boost開發包（敲字少：）
    # sudo apt-get install libboost-all-dev


    mkdir ~/rimeime
    cd ~/rimeime

    # 安装 glog
    wget http://google-glog.googlecode.com/files/glog-0.3.2.tar.gz
    tar xzf glog-0.3.2.tar.gz
    cd glog-0.3.2
    ./configure
    make
    sudo make install

    cd ~/rimeime

    # 安装 kyotocabinet
    wget http://fallabs.com/kyotocabinet/pkg/kyotocabinet-1.2.76.tar.gz
    tar xzf kyotocabinet-1.2.76.tar.gz
    cd kyotocabinet-1.2.76
    ./configure
    make
    sudo make install

    cd ~/rimeime

    # 安装 yaml-cpp
    wget http://yaml-cpp.googlecode.com/files/yaml-cpp-0.5.1.tar.gz
    tar xvf yaml-cpp-0.5.1.tar.gz
    cd yaml-cpp-0.5.1
    mkdir build
    cd build
    cmake -DBUILD_SHARED_LIBS=ON ..
    make
    sudo make install

    # 库链接更新
    sudo ldconfig

    # 安装输入法
    cd ~/rimeime

    wget http://rimeime.googlecode.com/files/brise-0.30.tar.gz
    wget http://rimeime.googlecode.com/files/librime-1.0.tar.gz
    wget http://rimeime.googlecode.com/files/ibus-rime-1.0.tar.gz
    tar xzf brise-0.30.tar.gz
    tar xzf librime-1.0.tar.gz
    tar xzf ibus-rime-1.0.tar.gz
    cd ibus-rime
    ./install.sh


其實和在ubuntu上安裝是一樣的。只是將yaml-cpp的版本提高到0.5.1，否則安裝不成功。

参考：[ibus-rime on Ubuntu 12.04 安裝手記](https://code.google.com/p/rimeime/wiki/RimeWithIBus)
