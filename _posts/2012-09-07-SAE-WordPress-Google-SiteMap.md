---
title: SAE 下的 WordPress 输出 Google 的 SiteMap
layout: post
---

在 SAE 上的 WordPress 想要输出 Google 的 SiteMap ，看似很容易，只需要启用 WordPress 中的 **Google XML Sitemaps** 插件就可以了。

其实不是。

这个插件确实可以生成 SiteMap ，但因为 SAE 的代码目录不可写， SiteMap 会将 SiteMap 放在 Storage 服务下。网址会类似于 http://appname-wordpress.stor.sinaapp.com/sitemap.xml 这样。但 Google 要求 SiteMap 必须在网站的根目录下。

不过我们可以用 URL 重写来“骗过” Google 。

参考了[新浪sae url rewrite(伪静态、重定向)详解](http://leedd.com/2012/03/sae-url-rewrite/)之后，我发现只要在 config.yaml 文件里现有的重写规则之前新增这么一句话就可以了：

> <pre>- rewrite:if (path ~ "^/sitemap.xml") goto "http://appname-wordpress.stor.sinaapp.com/sitemap.xml"</pre>

地址请你们自行改掉哦。

这样， Google 就会在你的网站根目录下找到 SiteMap 了，网址类似于 http://appname.sinaapp.com/sitemap.xml

然后，去到[ Google 的网站管理](https://www.google.com/webmasters/)那里，新增你刚才的 SiteMap 就可以了。

祝好运～
