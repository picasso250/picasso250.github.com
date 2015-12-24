---
title:  《快学Scala》笔记（第四章 映射和元组）
layout: post
---

1. 构造映射 `val f = Map("foo" -> 1, "bar" -> 2)`
2. 可变映射 `val f=scala.collection.mutable.Map("foo"->2,"bar"->3)`
3. 空的映射需要指定映射实现和类型 `val f= new scala.collection.mutable.HashMap[String,Int]`
4. 映射是对偶的集合，对偶是二元组。 `->` 是用来创建对偶的。`"fooo"->3` 的值是 `(String, Int) = (fooo,3)`
5. 获取值用小括号 `val a = f("foo")` 如果不存在键就抛异常
6. 检查是否存在某个键，用 `contains` 方法： `val a=if (f.contains("baz")) f("baz") else 0` 或者用糖 `val a=f.getOrElse("baz",0)`
7. `get` 方法返回 `Option` 对象
8. 赋值 `f("bar") = 10` 或者用 `+=` 符号更新 `f += ("a"->1,"ab"->2)`
9. 用 `-=` 移除某个键 `f -= "a"`
10. `+` `-` 符号如你所愿
11. `for ((k,v)<-映射)` 遍历
12. `keySet` 和 `values` 方法可以获得键和值
13. `for ((k,v)<-map) yield(v,k)` 翻转，吊吊的
14. `scala.collection.immutable.SortedMap` 的实现是树形映射，有序，但是不可变。如果想要可变的树形映射，请用Java中的 `TreeMap`
15. 如果想要按插入顺序访问所有键，请使用 `scala.collection.mutable.LinkedHashMap`
16. `import scala.collection.JavaConversions.mapAsScalaMap` 转换 `import scala.collection.JavaConversions.propertiesAsScalaMap` 可以将 `java.util.properties` 转到 `Map[String,String]` 反之亦有相关类库
17. 元组 `val t = (1,3.14,"abc")` 创建元组
18. 可以使用 `_1` `_2` `_3` 方法访问元组的元素。但通常使用模式匹配来赋值 `val (first, second, third) = t`
19. `val symbols = Array("<","-",">"); val counts = Array(2, 10, 2); val pairs = symbols.zip(counts)` 然后就可以对齐进行一起操作了 `for ((s,n)<-pairs) print(s*n)`
20. `toMap` 方法可以将对偶的集合转成映射 `pairs.toMap`

练习

