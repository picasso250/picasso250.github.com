---
title: 在中国使用 Composer
layout: post
---

如果你是一个普通的PHP程序员，那么你想必听过[Composer](https://getcomposer.org/)。但如果你试图使用这个工具，经过42次尝试之后，你意识到，你需要帮助。

本来，我写的是如何下载composer，可是这么多年之后，官方终于可以比较轻松下载并安装composer了：
[https://getcomposer.org/download/](https://getcomposer.org/download/)

也有了一个比较靠谱的中文镜像：
[https://www.phpcomposer.com/](https://www.phpcomposer.com/)

---

我们继续尝试一个小命令

    composer create-project --prefer-dist --stability=dev yiisoft/yii2-app-basic basic

然后我们惊讶的发现：

    Could not fetch https://api.github.com/repos/jquery/jquery-dist, please create a GitHub OAuth token to go over the API rate limit

这可不是小事。这是因为GitHub想要知道是谁在下载东西，[因此需要token](https://github.com/composer/composer/issues/3542)，composer官方对此[有些解释](https://getcomposer.org/doc/06-config.md#gitlab-token)。
此时你需要[新创建一个token](https://github.com/settings/tokens)给它。然后[配置token](https://github.com/composer/composer/blob/master/doc/articles/troubleshooting.md#api-rate-limit-and-oauth-tokens)

    composer config -g github-oauth.github.com <oauthtoken>

啊……终于可以用啦。

---

我们可以 **下载[composer.phar](http://packagist.cn/composer/composer.phar)**，很好，你将它放在某个目录里。比如 `d:\software\composer`，然后你发现它只能这么执行

    php d:\software\composer\composer.phar

这真是太坑爹了。于是你写了一个bat文件 composer.bat 放在了同级目录下面，文件内容如下：

    php %~dp0composer.phar %*

[`%0`是当前文件名，`%*` 是所有参数](http://stackoverflow.com/questions/14286457/using-parameters-in-batch-files-at-dos-command-line)，[`%~dp0`是当前目录名](http://stackoverflow.com/questions/3827567/how-to-get-the-path-of-the-batch-script-in-windows)。不得不说bat的语法比bash的复杂多了。

然后你在Path中加入这个目录，就可以了。

试试执行

    composer

你尝试运行

    composer self-update -vvv

可是它竟然提示你：

    Downloading https://getcomposer.org/version

啊西吧，这还是需要先有梯子！即使使用http://packagist.cn/ 提供的神秘代码也没用！不如不用。
没事，我们假设你已经有了一个梯子，那么你只要 **[在 PowerShell 中执行命令去设置环境变量](http://picasso250.github.io/2014/12/31/windows-powershell-evn-var.html)就行了**

    $Env:http_proxy="http://梯子/"

然后执行

    composer self-update -vvv

天哪！终于可以啦。


