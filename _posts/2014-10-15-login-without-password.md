---
title: [译]无密码登录
layout: post
---

WebMaker 作出了一个不依赖密码的登录过程，这里是介绍：

[One Less Password](http://notebook.ideapublic.org/2014/one-less-password/)

简译如下：


## 少一次密码

我在 Mozilla 工作，开发了一个不依赖密码的登录流程，也不依赖脸书等社交媒体。
这是一种很有力的替代品，希望广大设计师和程序员能够进一步推进发展。
如果我们不再需要密码，密码就不会被遗忘、被泄露和在网站间的重复。

![Ricardo Vazquez 设计](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/join-sign-in-buttons-300x66.png)

> 此文描述了这个系统的设计和用户体验。
> 如果你想了解更多，请阅读 [Webmaker](https://blog.webmaker.org/one-less-password/) 的博客。

## 加入——从未有过的便捷

![立刻开始使用Webmaker，账户确认是之后的事情。](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/join1.png)

我们大大简化了Webmaker的注册流程。
以前，用户需要先注册，才能使用Webmaker，很麻烦。
现在，新用户只需要输入邮箱和用户名就好了。
他们可以立刻开始使用Webmaker。
在用户第一次登录的时候，需要确认邮箱。（以后会加入短信的支持。）

## 登录——无需密码

登录过程也很有意思。
和许多人讨论之后，我决定将找回密码的典型体验作为登录的主要形式。
用户可以使用用户名或者电邮地址登录。Webmaker会发一封邮件给他们。

![email](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/login-email-2.png)

这个邮件包括一个登录按钮，还有一个“记住我”的链接。
点两个中的任何一个都会登录成功，不需要更多操作。

![使用邮箱或者短信登录，当使用私人电脑的时候，可以选中“记住我”。当使用别人的设备时，拷贝令牌。](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/sign-in.png) 

## 设备间登录

有人会在学校或者图书馆的公用电脑上使用 Webmaker。
他们可能会在手机上收到登录邮件。
为了解决这个问题，邮件中会包含一个短语令牌，他们可以在设备间拷贝（上面邮件中的黄字）。

![设备间登录，非常适用于使用公用电脑和私人手机的时候](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/sign-in-across-devices.png)

上图描述了这个流程。令牌是暂时的。
使用之后会过期，30分钟后也会过期。
如果被暴力攻击，也会在固定的猜测次数之后过期。
暂时令牌要比密码安全许多。

## 密码可选

![密码可选，用密码就可以跳过其他的流程。](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/sign-in-password.png)

对于那些在图书馆的公用电脑上工作的人来说，密码可能是必须的。
所以，我们将密码设置成可选的。
实际上，用户可以在旅行期间使用密码，回家之后再切换回邮件登录，他们可以在两者之间自由转换。
我认为，如果用户有权利抛弃密码，大多数人都不会使用容易忘记的密码。
密码可以在用户设置页面添加。
登录邮件中，也有这个链接。
这个链接会让用户登录，然后引导用户设置密码。

## 服务器流程

![从服务器的视角看整个流程](http://notebook.ideapublic.org/wp-content/uploads/sites/5/2014/09/login-server-flow.png)

用户可以在邮件链接、令牌和密码之间选择一个适合他们处境的方式登录。
即使对那些忘记了自己曾经在这个网站注册过的用户来说，体验也很流畅。
