---
title: Tiny Core Linux
layout: post
---

有个Linux发行版很小, 才15M, 重点在于小, 于是好玩.

安装到硬盘的方式在这里:

http://www.tinycorelinux.net/install_manual.html

里面有个错误

hdc其实是sr0

tce-load -wi cfdisk.tcz
tce-load -wi grub-0.97-splash.tcz

mkdir -p boot/grub

sda1


tce-ab 可以非常容易的搜索和安装软件.

---

install vim

    tce-load -wi vim.tcz
    tce-load -wi bash.tcz
    tce-load -wi elinks-nodep.tcz
	tce-load -wi openssh.tcz


install nginx

	tce-load -wi ngnix.tcz

maybe we can install php now

tce-load -wi compiletc.tcz
tce-load -wi libxml2-dev.tcz
tce-load -wi perl5.tcz
