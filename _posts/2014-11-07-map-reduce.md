---
title: 一句话解释 MapReduce(草稿)
layout: post
---

假设我们要统计世界各国人口的数量。再假设联合国人口组织有个数据库，那么这件事情真是太简单不过了。

    select count(*)
    from all_people group by country

可是世界人口数量有70亿，太大了，MySQL 放不下。我们只能用 MapReduce 来做了。

熟悉python的都知道

    tmp = map(
    reduce(lambda x, s: return s+x, [1 for x in all_people], 0)

