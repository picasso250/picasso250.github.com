---
title: 《快学Scala》笔记（第三章 数组）
layout: post
---

1. 来个数组 val a=new Array[Int](10) 10表示长度，所有的值都是0，如果是String的话，通通为null。也可以 val ss=Array("hello","world") 类型就可以推断出来
2. 变长数组，Java中的ArrayList，C++中的vector，Scala中的是ArrayBuffer（这个名字起的，就比Java有一致性呀。 import scala.collection.mutable.ArrayBuffer;val b=ArrayBuffer[Int]()
3. b+=1 是append操作，也可以这样 b+=(2,3,4) 其实质是 b.+=(2,3,4) 坑爹呀
3. b++= Array(3,3) 这是正确的操作，merge操作
5. b.trimEnd(3) 在末端删除N个元素 在末端增删元素都是 amortized constant time 估计是和C++原理一样。
6. b.insert(1,3) 这个是在index之前插入元素，不高效。 b.insert(2,3,4,5,6)
7. b.remove(2) 删除一个元素 b.remove(2,3) 指定删除的个数
8. b.toArray
9. for推导 for (x <-b if x % 2 == 1) yield 2*x 维持类型哟
10. 常用算法 sum min max sorted sortWith mkString
11. 这个吊，其实就是升级版join Array(2,3,3).mkString(",") 和 Array(2,3,3).mkString("(",",",")") 这个方法数组也有哦~
12. ArrayBuffer有toString方法，漂亮
13. 去看文档
14. 二维数组实质上是 Array[Array[Double]] （现在才惊觉原来类型重载用的是中括号）Array.ofDim[Double](3,4) 三行四列的二维数组
15. 提供了这么好的类型，但等到调用Java的时候还需要强制转换就吊用没有啦。所以大家要经常 import scala.collection.JavaConversions.bufferAsJavaList 或者 import scala.collection.JavaConversions.asScalaBuffer 这样才好

答案

1. val n=4;var b=ArrayBuffer[Int]();for(i<-0 until n){b+=Random.nextInt(n)};val a=b.toArray
2. val a=Array(1,2,3,4,5);for(i<-0 until a.length){if (i % 2 == 1) {val t=a(i-1);a(i-1)=a(i);a(i)=t }}
3. val a=Array(1,2,3,4,5);for(i<-0 until a.length) yield if (i % 2 == 0) {
        if (i == a.length - 1)  a(i) else a(i+1)
    } else {
        a(i-1)
    }
4. def splitPosNeg(a:Array[Int]) = {val pos=for (x<-a if x > 0) yield x; val neg = for(x<-a if x <= 0 ) yield x; a++b}
5. ds.sum / ds.length
6. (for (i<-0 until a.length) yield a(a.length-i-1)).toArray b.reverse
7. val asd=a.sorted ;for(i<-0 until asd.length if i-1<0 ||(i-1 >= 0 && asd(i) != asd(i-1))) yield asd(i) 或 (for ((i,xs) <- a.groupBy(x=>x)) yield xs(0)).toArray
8. val negIndexes = for (i<-0 until b.length if b(i)<0)yield i;for (i<-negIndexes.tail.sortWith(_>_)) a.remove(i) 效率不高，因为负数之后的正数都要重新移动。但比第一种好，少移动m-1次（m是负数的个数）
9. val a = java.util.TimeZone.getAvailableIDs;val starts="America/";a.filter(_.startsWith(starts)).map(s=>s.drop(starts.length))
10. 什么玩意！回家。
