---
title: python CGI script is not executable
layout: post
---

当你使用 python `http.server`
做 Web 服务器的时候，可能会报以下错误。

`Message: CGI script is not executable ('/cgi-bin/a.py')`

这是因为没有执行权限所致。

解决方案：
```
chmod +x a.py
```
