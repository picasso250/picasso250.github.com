---
title: PHP 过滤和验证
layout: post
---

如果你要验证email, 不需要到网上找正则, 因为 PHP 提供这个东西

    var_dump(filter_var('bob@example.com', FILTER_VALIDATE_EMAIL));
    var_dump(filter_var('http://example.com', FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED));

一般大家会这么写.(用框架的除外)

    if (empty($_GET['name']) {
        // ... report to user
    } else {
        $name = $_GET['name'];
    }

这样写是可以的, 但如果是一个整数类型的参数, 你就得这么

    if (!isset($_GET['val']) || $_GET['val'] === '') {
        // ... report to user
    } elseif (!preg_match('/^[+-]?\d+$/', $_GET['val']) {
        // ... report to user
    }

当然, 你还是有其他(更容易的)选择的

    $options = array(
        'options' => array(
            'default' => 3, // value to return if the filter fails
            // other options here
            'min_range' => 0
        ),
        'flags' => FILTER_FLAG_ALLOW_OCTAL,
    );
    $val = filter_input(INPUT_GET, 'val', FILTER_VALIDATE_INT, $options);
    if ($val === null) {
        // not set
    } elseif ($val === false) {
        // not valid
    } else {
        // ok
    }

下面是其他的一些类型

    FILTER_VALIDATE_BOOLEAN // 1 true on yes / 0 false off no ''
    FILTER_VALIDATE_EMAIL
    FILTER_VALIDATE_FLOAT
    FILTER_VALIDATE_IP     // v4 v6 都支持
    FILTER_VALIDATE_REGEXP // 使用正则来判断
    FILTER_VALIDATE_URL

也是够用了.

不要提前过滤

`magic_quotes()` 之殇

html_special_chars()

$pdo->prepare();

在属性中的如何过滤?

---

专辑:

- [PHP 错误处理](/2014/12/03/PHP-error.html)
- [PHP 数组](/2014/12/25/PHP-array.html)
- [PHP 语法糖, 闭包和反射](/2014/12/26/PHP-sugar.html)
- [PHP 过滤和验证](/2014/12/27/PHP-filter.html)
- [PHP 类, 邪恶的继承](/2014/12/29/PHP-class.html)
