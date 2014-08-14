---
title: PHP strtotime() mouth 月份不正常的问题
layout: post
---

`strtotime('- 1 month', strtotime('2014-05-31 23:59:59'))`
返回的是5月份的时间，这不是一个Bug（为什么？），但是很不直观。

解决方案

```php
function n_month($n, $now = null)
{
    if ($now === null) {
        $now = time();
    }
    return strtotime("$n month", strtotime(date('Y-m-01 00:00:01', $now)));
}
```
