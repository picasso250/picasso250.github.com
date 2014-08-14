---
title: PHP strtotime() mouth 月份不正常的问题
layout: post
---

`strtotime('- 1 month', strtotime('2014-05-31 23:59:59'))`
返回的是5月份的时间，这不是一个Bug，但是很不直观。

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
