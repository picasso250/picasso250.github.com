---
title: compile pHash link 错误
layout: post
---

    g++ test_audiophash.cpp  -L /home/xiaochi/software/phash/lib -lpHash

-L 表示在这里找lib

-l 表示带上这个lib

默认先从 `lib<name>.so` 开始找

http://stackoverflow.com/questions/10749058/building-and-linking-a-shared-library
