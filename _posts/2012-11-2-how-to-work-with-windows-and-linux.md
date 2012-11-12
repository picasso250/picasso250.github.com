---
title: 如何使用 Windows 和 linux 工作
layout: post
---

副标题：告别 ubuntu 的图形界面

近半年时间，我一直用 ubuntu 办公，我充分体会到了 linux 的好处

## linux 的好处

- sh

 ！！！！！！多少个感叹号都不够我表达对 sh 的爱

- 默认编码，是utf8

 我是个前端，不解释

- 行末的换行符
 
 不解释

- 用作服务器

 不解释

## Windows 的优点

- 输入法

 用搜狗输入法，词库实时更新，而且可以输入古诗词

- 图形界面的速度

 各种浏览器的启动速度都比 Windows 要快一点点。

 而且，如果你用 linux 图形界面，不折腾是不可能的。如果我还是 18 岁，我愿意折腾。现在，我就想好好写代码。

- 玩游戏

 这个要说一下，这个真是无敌了，linux 拍马难及，话说多少人都只是为了买个 Windows 牌的游戏机啊

- 各种不喜欢又不得不用的软件如 QQ

 Fuck，但是，我没有办法

（优点的数量不代表质量）

## 中西结合疗效好

如果能共享两者的优点该有多好。事实上，我尝试过一些方法。之前的半年，我一直在 ubuntu 里面使用 Virtual Box 模拟 Windows。但显卡驱动的问题让我很恼火。而且发热量高。（我不知道是否能折腾好，但我已经不愿意折腾了。）

终于，我想到了一个方案：使用 Windows 宿主机 和 linux 虚拟机。经试验（30天），良好。下面是一些经验。

## sh

使用虚拟机安装 Debian，以便使用 bash 和 apache 服务器。不使用图形界面，分配的内存 512M，硬盘 8G。

虚拟机访问 Windows 文件的方法：自动挂载即可。

详细步骤如下：

1. 在宿主 Windows 中某（任意）目录新建一个文件夹，起名为 `share_dir_name` （可任意）。
2. 在 VirtualBox 中设置虚拟机的共享文件夹为固定分配，指向这个文件夹。

3. 在寄生 Linux 中挂载共享文件夹的命令如下

`sudo mount -t vboxsf share_dir_name /home/user_name/wwww`

当然，开机自动挂载，还是需要配置一下的。详细步骤如下：

1. 在 `/etc/init.d/` 目录下新建一个文件，文件名 `mount_win_fs_as_share` （可任取），注释的写法照抄 `/etc/init.d/apache2` 的注释，但 Provides 后面填的是 `mount_win_fs_as_share` 。

注释下面的命令就一句话：

`mount -t vboxsf share_dir_name /home/user_name/wwww`

2. 因为我们需要 apache2 启动之前挂载文件，所以在 `/etc/init.d/apache2` 的 Provides 域新添加我们刚才新加的服务：`mount_win_fs_as_share`。

3. 更新启动项

`sudo update-rc.d mount_win_fs_as_share defaults`

`sudo update-rc.d apache2 defaults`

## 换行符和编码

如何解决行末的换行符和默认编码的问题。设置编辑器。比如 Sublime 就可以。

在 Preferences - Settings - User 里面添加

```
"ensure_newline_at_eof_on_save": true,
"default_line_ending": "unix"
```

总结
------

我现在生活轻松，气色爽。
