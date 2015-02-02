---
title: BAE url rewrite
layout: post
---

[在此人的帮助下](http://www.thinkphp.cn/topic/951.html), [在BAE 应用配置 app.conf 官方文档的帮助下](http://developer.baidu.com/wiki/index.php?title=docs/cplat/rt/manage/conf), 我终于试出来了:

    handlers:
      - regex_url: (.+\.(html|css|htm|js|png|jpg|gif))
        script : $1
      - regex_url: (.*)\?(.*)
        script : index.php?$2
      - regex_url: (.*)
        script : index.php

BAE的文档真让人蛋疼, 还有没有在用心做呀!