---
title: 给img统一添加描述
layout: post
---

现在有一个需求，需要给文章中的图片的alt和title统一替换成某种利于SEO的字符串。
因为犯病了，所以不想使用dom解析，所以执意要用正则完成。

    $article_img_desc = "某些掉渣天的描述";
    $article['content'] = preg_replace_callback('/<img *([^>]*)\/?>/', function ($m) use($article_img_desc) {
        preg_match_all('/[-\w]+\s*=\s*[\'"][^\'"]*[\'"]\s*/', $m[1],$mm);
        $map = [];
        foreach ($mm[0] as $kv) {
            $pos = strpos($kv, '=');
            $key = substr($kv, 0, $pos);
            $value = substr($kv,$pos+1);
            $map[$key] = $value;
        }
        $map['alt'] = $map['title'] = '"'.htmlentities($article_img_desc).'"'; // 一定有描述
        $arr = [];
        foreach ($map as $k=>$v) {
            $arr[] = "$k=$v";
        }
        $p = implode(" ", $arr);
        return "<img $p />";
    }, $article['content']);

