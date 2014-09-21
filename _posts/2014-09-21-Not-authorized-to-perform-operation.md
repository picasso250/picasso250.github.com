---
title: Not authorized to perform operation.
layout: post
---

(Not authorized to perform operation.)[http://intijk.com/notes/linux-%E5%AD%A6%E4%B9%A0%E7%AC%94%E8%AE%B0/not-authorized-to-perform-operation.html]

当你在图形界面里点击硬盘的盘符，希望挂载一个分区的时候，可能会遇到
‘Not authorized to perform operation.’ 的问题，权限不够，无法mount该分区。解决方法是下面几步

检查 /etc/group 查看自己是否在storage用户组里。
创建文件/etc/polkit-1/localauthority/50-local.d/10-storage-group-mount-override.pkla写入内容

```
[storage group mount override]
Identity=unix-group:storage
Action=org.freedesktop.udisks2.filesystem-mount;org.freedesktop.udisks2.filesystem-mount-system
ResultAny=yes
ResultInactive=yes
ResultActive=yes
```

检查文件/usr/share/polkit-1/actions/org.freedesktop.udisks2.policy, 确保你拥有：

```
Mount a filesystem
Authentication is required to mount the filesystem

auth_admin
auth_admin
yes
```

其中最关键的是这行
yes

窗口管理器用polkit来管理权限，当你请求挂载分区的时候，polkit会搜索step 2中的配置文件，根据Action参数来查找应该采用什么样的动作，然后在step 3的配置文件中找到改动作和对应的权限配置，如果权限无误则执行改动作.