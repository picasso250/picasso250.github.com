---
title: PHP strtotime() mouth 月份不正常的问题
layout: post
---

`strtotime('- 1 month', strtotime('2014-05-31 23:59:59'))`
返回的是5月份的时间，**这不是个Bug**，但是很不直观。

为什么不是一个Bug呢，你们可以感受一下：

```php
<?php
$now = strtotime('2014-05-31 23:59:59');
echo date('Y-m-d', $now), "\n";
foreach (range(-12, 12) as $n) {
    echo "$n\t-->\t", date('Y-m-d', n_month($n, strtotime('2014-05-31 23:59:59'))),"\n";
    echo "$n\t-->\t", date('Y-m-d', strtotime("$n month", $now)),"\n";
}
```

5月31日往前一个月是什么时候呢？自然是4月31号。可是4月没有31号，只有30号，那么我们就把4月31号映射进5月份，那不就是5月1号吗。哇，就这么愉快的决定了。

当然，我们还有另外一种解释，5月31号是5月的最后一天，所以往前一个月，应该也是4月的最后一天（即4月30日）。到现在为止没有什么问题，但在另外的情况下就会出问题。比如：5月1号往前一个月是几月几号呢？5月1号是5月的倒第31天，因此我们应该取4月份的倒第31天，可是4月份只有30天，于是我们来个映射……哈哈，看出来了么？这不是一个bug。

万能的SO也记录了这个问题：
[PHP: Adding months to a date, while not exceeding the last day of the month](http://stackoverflow.com/questions/5760262/php-adding-months-to-a-date-while-not-exceeding-the-last-day-of-the-month)

解决方案

```php
<?php
function n_month($n, $now = null)
{
    if ($now === null) {
        $now = time();
    }
    return strtotime("$n month", strtotime(date('Y-m-01 00:00:01', $now)));
}
```

或者更特殊的讲，我们只是想让年月份按照我们希望改变，于是，我们可以写出另一个函数：

```php
<?php

// test
list($year, $month) = n_month(-1, '2014', '05');

function n_month($n, $year = null, $month = null)
{
    if ($year === null) {
        list($year, $month) = explode(',', date('Y,m'));
    }
    $year = intval($year) + intval(floor(($month-1) / 12));
    $month = intval($month) + $n;
    if ($month > 0) {
        $month = ($month - 1) % 12 + 1;
    } else {
        $month = 12 - ((12 - $month) % 12)
    }
    return array(strval($year), strval($month));
}
```
