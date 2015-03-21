---
title: 龙书笔记
layout: post
---

### 第一章

编译器的结构：

1. 词法分析
2. 语法分析
3. 语义分析
4. 中间代码生成
5. 代码优化
6. 代码生成
7. 符号表管理
8. 合成趟(pass)

静态/动态

动态（如C中的宏）

参数传递的机制

1. 值调用
2. 引用调用
3. 名调用

C全部值调用，C++显式的两种调用。Java和JavaScript、Python对基本类型是值调用，对对象是引用调用。PHP...

### 第二章

编译器前端的模型

抽象语法树 (abstract syntax tree AST)

    do {
        i = i + 1;
    } while (a[i] < v)

中间代码（三地址指令）

    1: i = i + 1
    2: t1 = a [ i ]
    3: if t1 < v goto 1

产生式(production)

    stmt -> if ( expr ) stmt else stmt

终结符号(terminal) if else

推导

    list -> list + digit
    list -> list - digit
    list -> digit
    digit -> 0 |1 |2 |3 |4 |5 |6 |7 |8 |9

语法分析 (parsing)

语法分析树 (parsing tree)

    9 - 5 + 2

二义性

if 悬空

    if (a) foo(); if (b) bar(); else baz(); 

优先级

    9 + 5 * 2
    1 + 2 + 3

左结合/右结合

语法层面/词法层面的解决方案

    term -> term * factor
          | term / factor
          | factor
    expr -> expr + term
          | expr - term
          | term
    factor -> digit | (expr)

练习略去吧

语法制导

考虑如下产生式

    expr -> expr_1 + term

我们自然想到如下的伪码处理

    翻译 expr_1;
    翻译 term;
    处理 +;

树

后缀表示

    E_1 op E_2 => E_1 E_2 op

解释的唯一性

    9-(5+2)

注释(annotated)语法分析树 (这个就不需要讲了)

树的遍历

深度优先遍历

前序遍历
后序遍历

语义动作

    {print v}

为一个语义动作创建一个额外的叶子节点

语法分析

自顶向下

递归下降分析法

预测分析法

    void stmt() {
        switch (lookahead) {
            case if:
            match(if); match('('); match(expr); match(')'); stmt();
            break;
            case for:
            match(for); match('('); optexpr(); match(';'); optexpr(); match(';'); optexpr(); match(')');stmt();
            break;
        }
    }
    void optexpr() {
        if (lookahead == expr) match(expr);
    }
    void match(terminal t) {
        if (lookahead == t) lookahead = nextTerminal;
        else report("syntax error");
    }

习题在此：

http://picasso250.github.io/2015/02/26/compile.html

做了一个[json-parser](https://github.com/picasso250/py3-json-parser/)（不能处理unicode）
