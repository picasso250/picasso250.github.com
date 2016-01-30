---
title: Fix Deprecation Warning for VersionParser::parseLinks()
layout: post
---

I am learning `Yii 2.0`. It mentions to

    composer global require "fxp/composer-asset-plugin:~1.1.1"

But it throws an Error to my face:
 
    Call to undefined method Composer\Package\Loader\ArrayLoader::parseLinks()

I googled, and it turned out that [many people come through this](https://github.com/composer/composer/issues/4260).

But it's not Composer's mistake. It is because [composer's version is old but `composer-asset-plugin`'s version is newer](https://github.com/francoispluchino/composer-asset-plugin/issues).

You just need to

    composer self-update

Done.

但是我们在中国，没办法只得使用一些特殊手段。
We [need to set system var `http_proxy`](https://getcomposer.org/doc/03-cli.md).

[How to set sys var in PowerShell](http://picasso250.github.io/2014/12/31/windows-powershell-evn-var.html)

Done.
