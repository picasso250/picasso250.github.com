---
title: Nginx HTTP 2 的编译和配置
layout: post
---

准备开HTTP2

据[官方博客](https://www.nginx.com/blog/nginx-1-9-5/)所说，http_v2_module已经不是实验状态了。

那么首先要打开 [ngx_http_ssl_module](http://nginx.org/en/docs/http/ngx_http_ssl_module.html)
其次要打开 [](http://nginx.org/en/docs/http/ngx_http_v2_module.html)

打开这两个模块的方式就是在编译的时候带上：

    --with-http_ssl_module --with-http_v2_module

但注意，如模块的官方页面所提到的

> Note that accepting HTTP/2 connections over TLS requires the “Application-Layer Protocol Negotiation” (ALPN) TLS extension support, which is available only since OpenSSL version 1.0.2. Using the “Next Protocol Negotiation” (NPN) TLS extension for this purpose (available since OpenSSL version 1.0.1) is not guaranteed.

因为使用了ALPN，所以只是需要OpenSSL 1.0.2。

你需要检测一下你的OpenSSL版本，方法如下：

    openssl version

如果版本不够高，请[从官网下载](https://www.openssl.org/source/openssl-1.0.2d.tar.gz)，然后在编译nginx的时候，加上

    ----with-openssl=<open ssl source directory>

注意上面是源码文件夹。

配置是很简单的，只要

    listen       443 ssl http2;

就可以支持HTTP2了。

但是注意，正如模块官页所说：

> Also note that if the ssl_prefer_server_ciphers directive is set to the value on, the ciphers should be configured to comply with RFC 7540, Appendix A black list and supported by clients.

如果你开了

    ssl_prefer_server_ciphers  on;

那就要注意你的ssl_ciphers配置不要在HTTP2的黑名单里，不然你可能会遇到Chrome浏览器报错ERR_SPDY_INADEQUATE_TRANSPORT_SECURITY 。
[SE上的一个问题](http://serverfault.com/questions/712808/chrome-reports-err-spdy-inadequate-transport-security-connecting-to-local-web-se)也详细解释了这个现象。
