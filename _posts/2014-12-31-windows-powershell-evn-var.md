---
title: Windows Power Shell 环境变量
layout: post
---
获取环境变量

Get-ChildItem Env:os

设置环境变量

$Env:os = "hello"

问题是 `getenv()` 依然不管用啊?