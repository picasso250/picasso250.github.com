---
title: 关于linux的小技巧
layout: post
---

1. 在 linux 下查找文件内容

  [list all files in directory tree that contain a search string](http://www.linuxplanet.com/linuxplanet/tips/1119/1)

  find . -type f -iname "*.php" | xargs grep -li "find me"

2. 我终于知道升级的不好的地方了——好多软件所需要的依赖库都没有升级，而你的库已经升级了。。。

3. 颜色区别的 diff

  [Linux: 加上顏色區別的 diff - colordiff](http://blog.longwin.com.tw/2008/02/linux_diff_colordiff_2008/)

  sudo apt-get install colordiff

  diff -u file1 file2 | colordiff | less -R

4. 将 Windows 下制作的 .txt 文件转成 linux 格式的

  [How do I convert between Unix and Windows text files](http://kb.iu.edu/data/acux.html)

  tr -d '\15\32' < winfile.txt > unixfile.txt
