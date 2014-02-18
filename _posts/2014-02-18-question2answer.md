---
title: question2answer 1.6 在SAE上安装
layout: post
---

在SAE上不完美运行。而且增加了中文语言包。

1.5的在SAE的商店，但是版本低。

首先是改config中的db。

其次，是lock table在sae上没权限，全部注释掉。

再次ini_set没权限，会报错，到控制面板把报错关了就可以了。

zh的语言包，搜一下，就安装上了，现在你可以直接选择使用。

error_log() 无作用，改成sae storage的。（不改也可以的其实）

为了避免重复劳动，放在github上。
