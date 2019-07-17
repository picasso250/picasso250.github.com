---
title: Ruby Gem install Jekyll on Windows using Docker
layout: post
---

警告：不要在Windows下直接安装Ruby！

警告：不要在Windows下直接安装Ruby！

警告：不要在Windows下直接安装Ruby！

首先安装docker

然后安装这个image：
docker hub image name `jekyll/jekyll`
[https://hub.docker.com/r/jekyll/jekyll](https://hub.docker.com/r/jekyll/jekyll)

然后启动之：
```
docker run -it -v /D/picasso250/picasso250.github.com:/data -p 127.0.0.1:4000:4000  jekyll/jekyll bash
```

接下来你可以看到你可能遇到的[问题](https://help.github.com/en/articles/setting-up-your-github-pages-site-locally-with-jekyll)

把以下内容写入Gemfile

```
source 'https://rubygems.org'
gem 'github-pages', group: :jekyll_plugins
```

```
bundle install
```

然后保存

```
docker commit e0f43f3089b picasso250 #改成你自己的container id和名字
```

以后直接运行那个container，在里面serve就可以了

```
jekyll s -I -H 0.0.0.0 # if you in docker
```

思考问题：如何watch？