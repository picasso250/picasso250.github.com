---
title: 在 Debian 上编译 nginx 1.8
layout: post
---

`/usr/sbin/nginx -V` 可以看到编译的参数

首先，要安装一些依赖（或许可以再精简）

sudo apt-get install libpcre3 openssl libpcre3 libpcre3-dev libssl-dev libxml2 libxslt1-dev

然后就是编译安装了。

    ./configure --prefix=/etc/nginx --conf-path=/etc/nginx/nginx.conf --error-log-path=/var/log/nginx/error.log --http-client-body-temp-path=/var/lib/nginx/body --http-fastcgi-temp-path=/var/lib/nginx/fastcgi --http-log-path=/var/log/nginx/access.log --http-proxy-temp-path=/var/lib/nginx/proxy --http-scgi-temp-path=/var/lib/nginx/scgi --http-uwsgi-temp-path=/var/lib/nginx/uwsgi --lock-path=/var/lock/nginx.lock --pid-path=/var/run/nginx.pid --with-pcre-jit --with-debug --with-http_addition_module --with-http_dav_module --with-http_gzip_static_module  --with-http_realip_module --with-http_stub_status_module --with-http_ssl_module --with-http_sub_module --with-http_xslt_module --with-ipv6 --with-sha1=/usr/include/openssl --with-md5=/usr/include/openssl --with-mail --with-mail_ssl_module --with-http_mp4_module

最后生成的可执行文件在 `/etc/nginx/sbin/nginx`
