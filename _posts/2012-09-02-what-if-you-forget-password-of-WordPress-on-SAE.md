---
title: 忘记了 SAE 上的 WordPress 密码怎么办？
layout: post
comments: true
---

如果你忘记了 WordPress 密码，有很多种方式可以找回密码。可是如果你是忘记了托管在 SAE 上的WordPress 密码，可就比较麻烦了。

网上有很多文章教你如何找回密码。其方法不外乎使用 WordPress 本身的找回密码功能，或者直接更改数据库的密码。比如[Reset a WordPress Password from phpMyAdmin](http://www.devlounge.net/publishing/reset-a-wordpress-password-from-phpmyadmin)。但很可惜的是，这些方法在SAE上都不管用。

先说使用邮箱找回密码。因为SAE上的 mail 函数是特殊的 SAE API ，需要打开，即使你打开了也没用，还需要特殊的调用方式，虽然 WordPress 是从 SAE 的 Store 里面拿的，它依然不支持发送邮件……

再说直接改数据库。老版本的 WrodPress 的密码加密方法基本上都是 MD5 ，但新版本已经换了加密方法，所以也不行。（有兴趣的童鞋可以研究一下 wp-includes/class-phpass.php 里的加密方法）

**这里是重点：**

[SAE上的wordpress 忘记密码好痛苦](http://lvyaojia.sinaapp.com/2012/02/sae%E4%B8%8A%E7%9A%84wordpress-%E5%BF%98%E8%AE%B0%E5%AF%86%E7%A0%81%E5%A5%BD%E7%97%9B%E8%8B%A6/)。这篇文章有正确的解决方案。（里面提到的那篇奇葩文章千万不要点，点了后悔）

但里面的解决方案是英文的，我给翻译成中文的吧。

1. 首先使用 svn checkout 下来你的代码。（如何找到 SAE 的 SVN 地址？点击“My Apps” - “代码管理”，最下面有“SVN仓库地址”
2. 编辑 wp-content/themes/主题名称/functions.php 文件。在最开始的 &lt;? 后面加一行：

  > <pre>wp_set_password('你的密码',1);</pre>

  其中1是你的用户数据库ID，如果你只有一个用户，那就是1,如果你有多个用户，那么请自行查看数据库中的 wp_user 表。
3. 提交代码。
4. 使用你刚才设置的密码登录一次。（只要登录一次，登录成功就ok了）。
5. 登录成功后，再次编辑 wp-content/themes/主题名称/functions.php 文件，将加的那一行去掉。如果不去掉，每次都会重置密码，你会发现你又登录不上去了。
6. 提交代码。

