---
title: 在 SAE 上的 WordPress 使用邮件
layout: post
---

之前的 SAE 上的 WordPress 是不能使用邮件的，但现在官方升级了之后，已经可以使用了。以后找回密码也可以用邮件的方式找回了，不用再[费那么大的力气了](http://xiaochi2.sinaapp.com/2012/09/forget_password_of_wordpress_on_sae/)。

在实验过程中，我参考了这个：[Set up POP in mail clients](http://support.google.com/mail/bin/answer.py?hl=en&answer=13287)

**教程**如下：

1. 控制板 - 插件，启用 WP-Mail-SMTP 插件。
2. 点击下面的 Settings ，来对其进行设置。（我用的是 Gmail ，如果是其他的请自行研究）

  1. From Email 填你自己的邮箱地址
  2. From Name 随便填
  3. SMTP Host 填 smtp.gmail.com
  4. SMTP Port 填 465
  5. Encryption 选 Use SSL encryption.
  6. Authentication 选 Yes: Use SMTP authentication.
  7. Username 和 Password 填自己的用户名和密码

3. 点击保存更改。

4. （可选）在下面的 Send a Test Email 填上另外一个邮件地址测试下。看看能否收到邮件，收到表示成功了。

