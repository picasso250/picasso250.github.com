---
title: 专为 iPhone 设计 Web 页面的方法
layout: post
---

此文编译自：[http://csswizardry.com/2010/01/iphone-css-tips-for-building-iphone-websites/](http://csswizardry.com/2010/01/iphone-css-tips-for-building-iphone-websites/)


本文论述如何开发 iPhone 也可以访问的网页。更广泛的说，是要讨论如何设计在手持设备中浏览的网页。

# 始

为 iPhone 设计的页面和**超市小票**很像。确保你的页面完全是一行一行的，从头到角都不要有什么版面变动。

下面这一段代码展示了如何判断用户是否在用 iPhone 浏览你的网站。

<pre lang="php">
<?php
$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
if ($browser == true){
    $browser = 'iphone';
}
?>
</pre>

> 有些人认为判断用户浏览器不是什么好习惯。要不要这么做取决于你自己。

> 如果不在后端判断用户的浏览器，前端也是可以判断的。可以用 css 的 @media 查询。

> 你可以还想判断用户的浏览器是否是 mobile 版本的，即不止包括 iPhone ，还包括各种 Android 设备等。您可以自行查找各种设备和浏览器的 user agent 。或者，使用一个成熟的类库也是不错的选择，比如这个[Browser.php – Detecting a user’s browser from PHP](http://chrisschuld.com/projects/browser-php-detecting-a-users-browser-from-php/)的 isMobile() 方法。

现在我们已经知道用户用的是不是 iPhone 了，在之后的代码中，我们就可以这样写

<pre lang="php">
<?php if($browser == 'iphone'){ ?>
这些文字只会出现在iPhone中
<?php } ?>
</pre>

# 在用户主屏上占有一席之地

如果用户将我们的网站放置在主屏上，他会发现标题会特别长。比如我的网站的标题就是：**小池有话说 | 编程、设计、用户体验、**，可算得上又臭又长。我们希望在 iPhone 设备上，Icon 的标题比较短。要达到这个目的，可以这么做

<pre lang="php">
<?php if ($browser == 'iphone'){ ?>
    <title>特别为iPhone设计的短标题</title>
<?php }else{ ?>
    <title>普通的标题</title>
<?php } ?>
</pre>

把这段代码放在<head>里。这样，在用 iPhone 浏览的时候，用户看到的是短名字，收藏或者添加到主屏的时候，用的也是短名字。

# 主屏图标

至于图标啊，相当简单。做一个57×57的图标（现在为了 Retina 屏，需要做成114x114px的图标），命名为**apple-touch-icon.png**， windows 用户注意，不是icon格式的，是png格式的。把这个图标放在网站根目录下，一切 ok 了。

# 不可缩放

要设置视口，否则字会很小。在<head>中添加

<pre lang="php">
<?php if($browser == 'iphone'){ ?>
    <meta name="viewport"
    content="width=device-width,
    minimum-scale=1.0, maximum-scale=1.0" />
<?php } ?>
</pre>

不让用户缩放的意义在于，网页看起来更像是 Native App（土著程序）了。
（顺便说一句，对 viewport 的设置在 Android 下也起作用。）

# 风格

使用 PHP 检测到用户的客户端之后，我们完全可以发送不同的 css 。甚至可以导向一个不同的网站，比如 新浪微博 ，就会将用户导向到 weibo.cn 。

另一个比较简单的方法是使用 css 的媒介查询。而且还有额外的好处：不需要改变 html ，甚至是只需要使用一个 css 文件。

首先需要确认的是引入 css 的 link 元素没有 media 属性。

<pre lang="html">
<link rel="stylesheet" type="text/css" href="/path/to/style.css" />
</pre>

然后你的 css 文件只需要遵循这种结构即可：

<pre lang="css">
/*--- 普通的 CSS 放在这儿 ---*/

/*------------------------------------*\
	IPHONE
\*------------------------------------*/
@media screen and (max-device-width: 480px){
/*--- iPhone only 的 CSS 放在这儿 ---*/
}
</pre>

后面的规则只有在设备宽度小于或等于 480 像素的时候才起作用。

# 黄金建议

* 不要设置绝对宽度，尽量使用百分比。

* 线性排列元素，避免使用浮动。

* 将 Helvetica 设置为第一候选字体。

<pre lang="css">
/*--- 普通的 CSS 放在这儿 ---*/

/*------------------------------------*\
	IPHONE
\*------------------------------------*/
@media screen and (max-device-width: 480px){
body{
  -webkit-text-size-adjust:none;
  font-family:Helvetica, Arial, Verdana, sans-serif;
  padding:5px;
}
}
</pre>

上面的 padding 设置为 5px ，可以确保任何元素不和外框黏在一起。而所有的 wrapper div 都设置为 width:100% 。

# 图片

有些事情，我不得不说一下。在iPhone中，最好不设默认字体。我们之前说过，iPhone就像超市小票，为了让所有的div可以超市化，我们需要这样。div{    clear:both;    width:100%;    float:none;}看，我们每个div的左右都清了，爽了。图片呢，图片可能很大，我们这样

img{    max-width:100%;    height:auto;}

图片也正常了，可以自动充满视图。


结束语

小学的时候，我不明白为什么要写结束语，不是自己找累吗，现在才知道，原来这个是中年之后特有的碎碎念现象。正经点。这个教程，九牛一毛。大家接着自己找资料哈。有什么意见直接在下面留言就可以了。转载中文无需留名，此文已是公有领域。但转载代码请保留原英文地址。

