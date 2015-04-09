---
title: Windows Power Shell 环境变量
layout: post
---
获取环境变量

Get-ChildItem Env:os

设置环境变量

$Env:os = "hello"

PHP `getenv()` 因此可用

---

Run the app (on MacOS or Linux):

    $ DEBUG=myapp ./bin/www

On Windows, use this command:

    > set DEBUG=myapp & node .\bin\www

    set foo=bbb & php -r "echo getenv('foo');"
