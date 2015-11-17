---
title: Mysql 和 缓存
layout: post
---

今天上线，数据库被打死，导致线上的大部分服务都无法访问，经过分析，是两条SQL太慢引起的，其中一条SQL如下：

    SELECT r.*, u.user_name FROM r join u using(user_id)
                WHERE r.user_id != 0
                ORDER BY r.create_time DESC LIMIT 11

这条 sql expalin 起来是这样的

| id |	select_type |	table	| type |	possible_keys |	key |	key_len |	ref | rows | Extra |
|----|--------------|-----------|------|------------------|-----|-----------|-------|------|-------|
| 1	| SIMPLE |	r |	index |	|	idx_time | 5 |	|	11	| Using where	|
| 1 |	SIMPLE |	u	| eq_ref |	PRIMARY	| PRIMARY	| 8	| r.user_id |	1 |	Using where |

可以看到，这条SQL的type都是simple，并且r表应用索引，u表使用了主键，一共也只有11行，应该不会慢。

可是现实狠狠打了我一巴掌。这条SQL执行起来要1秒多。

这条SQL为什么会慢？

很明显，如采取从`create_time`的index扫的话，就需要判定`user_id`是否为0，将会是一个全表扫描。

count(r)=35000, count(u)=649577，全表扫描当然会很慢。但是天真的我看到type时，就以为这两条SQL会很快。这是一个深刻的教训，公共的数据（全表性质的）一定要加缓存！

在SQL层面，这个并没有什么好的解决方案。所以使用缓存。

但是如果每个函数都写一遍缓存那就太不lazy了，有没有什么简便的方法呢？

    class Cachable
    {
    	function __call($name, $args)
    	{
    		if (preg_match('/(\w+)Cachable$/', $name, $m)) {
    			$method = $m[1];
    			if (method_exists($this, $method)) {
    				$key = $name.implode(',', $args);
    				if ($data = $cache->get($key)) {
    					return $data;
    				}
    				$data = $this->$method(...$args);
    				$cache->set($key, $data, 5); // 5 s
    				return $data;
    			} else {
    				throw new Exception("$method is not Cachable", 1);
    			}
    		} else {
    			throw new Exception("bad method call", 1);
    		}
    	}
    }

只要继承这个类，就可以调用对应方法的缓存方法。
