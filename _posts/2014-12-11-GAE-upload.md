---
title: GAE 上传
layout: post
---

首先, 你要能翻墙.

以下基于 windows 环境.

不要使用GUI. 使用命令行.

    gcloud auth login
    gcloud config set project your-project
    "\Google\Cloud SDK\google-cloud-sdk\bin\appcfg.py" update your-project
