---
title: 龙书习题 第二章
layout: post
---

**练习 2.2.1** 考虑下面的上下文无关文法

$$
S\rightarrow S S + | S S * | a
$$

1) 试说明如何使用该文法生成串 aa+a*

$$
\begin{align} 
S & =S_1 S_2 * \\
S_1 & =S_3 S_4 +\\
S_2 & =S_3=S_4=a\\
\end{align}
$$

2) 略

3) 该文法生成的语言是什么? 证明你的答案.

加法和乘法的后缀表达式, 证明不会.

**练习 2.2.2** 下面的各个文法生成什么语言，并证明你的每一个答案。

1) $S \rightarrow 0 \ S \ 1 \ \| \ 0 \ 1$

左0右1并且0和1的个数相等的串

2) $S \rightarrow + \ S \ S \ \| \ - \ S \ S \ \|a$

操作数都为a的加减法前缀表示

3) $S \rightarrow S \ ( \ S \ ) \ S \  \|  \epsilon$

左右匹配的括号

4) $S \rightarrow a \ S \ b \ S \ \| \ b \ S \ a \ S \ \|\epsilon$

由 a 和 b 构成的串，其中a和b的顺序任意，但个数相等

5) S -> a \| S + S \| S S \| S * \| ( S )

操作数为a的表达式，其中有加法和乘法，还有*运算符，可以用括号改变结合性。但此文法有二义性。

**练习 2.2.3：** 练习 2.2.2 中哪些文法具有二义性？

1 2 不具有二义性，3 4 5 具有二义性。

对于 3，考虑 ()()

既可以是

S (S) e

又可以是

e (S) S

对于4，考虑 abab，既可以是

a S b S => a e b ( ab ) 

也可以是

a S b S => a (ba) b e

对于5

a+aa

既可以是

(a+a)a

又可以是

a+(aa)

**练习 2.2.4：**
为下面的各个语言构建无二义性的上下文无关文法。证明你的文法都是正确的。

1) 用后缀表示的算数表达式

    S -> + S S | - S S | * S S | / S S
       | digits

2) 由逗号分开的左结合的标识符列表

    S -> S , token
       | token

3) 由逗号分开的右结合的标识符列表

    S -> token , S
       | token

4) 由整数、标识符、四个二目运算符 +、-、*、/构成的算数表达式。

    S -> S + term | S - term | term
    term -> term * factor | term / factor | factor
    factor -> token | digit | ( S )

!5) 在4)的运算符中增加单目+和单目-构成的算数表达式。

    S -> S + term | S - term | sign_term
    sign_term -> sign_term * factor | sign_term / factor | sign_factor
    term -> term * factor | term / factor | sign_factor
    sign_factor -> factor | sign factor
    factor -> token | digit | (sign factor) | ( S )
    sign = + | -

**练习 2.2.5：**

1) 证明：用下面的文法生成的所有二进制串的值都能被3整除。

$$
num \rightarrow 11 \ | \ 1001 \ | \ num \ 0 \ | \ num \ num
$$

证明：

若num是3的倍数，则num 0 是6的倍数，能被3整除

若num是3 的倍数，则num num 是 $num(2^n)+num$，两个加数都是3的倍数，最终的和也是3的倍数。

11是3的倍数，1001是三的倍数。

由归纳法知，题设成立。

2) 不能，如 $11001_2=3\times 7$

**练习 2.2.6：**
为罗马数字构建一个上下文无关文法。

罗马数字的specification见
[罗马数字 - 维基百科](http://zh.wikipedia.org/wiki/%E7%BD%97%E9%A9%AC%E6%95%B0%E5%AD%97)

为了方便起见，我使用了正则表达式

$$
\begin{align} 
roman & \rightarrow  s0 \ | \ s1 \ s0 \ | \ s2 \ s1 \ s0 \ | \ s3 \ s2 \ s1 \ s0 \\
s0    & \rightarrow I\{1,3\} \ | \ IV \ | \ VI\{0,3\} \ | \ IX \\
s1    & \rightarrow X\{1,3\} \ | \ XL \ | \ LX\{0,3\} \ | \ XC \\
s2    & \rightarrow C\{1,3\} \ | \ CD \ | \ DC\{0,3\} \ | \ CM \\
s3    & \rightarrow M\{1,3\}
\end{align}
$$

**练习 2.3.1** 构建一个语法制导翻译方案，该方案把算术表达式从中缀表示方式翻译成运算符在运算分量之前的前缀表示方式。例如，-xy是表达式 x-y 的前缀表示法。给出输入 9-5+2和 9-5*2 的注释分析树。

产生式 | 语义规则
expr -> $expr_1$ + term | expr.t = '+' ‖ $expr_1$.t ‖ term.t
expr -> $expr_1$ - term | expr.t = '-' ‖ $expr_1$.t ‖ term.t
expr -> term          | expr.t = term.t
term -> term_1 * factor | term.t = '*' ‖ term_1.t ‖ factor.t
term -> term_1 / factor | term.t = '/' ‖ term_1.t ‖ factor.t
term -> factor          | term.t = factor.t
factor -> (expr) | factor.t = expr.t
factor -> digit  | factor.t = digit.t
digit -> 0 | digit.t = '0'
digit -> 1 | digit.t = '1'
digit -> 2 | digit.t = '2'
  ...      |   ...
digit -> 9 | digit.t = '9'

    9-5+2 => +-952
    9-5*2 => -9*52

注释分析树略

**练习 2.3.2** 构建一个语法制导翻译方案，该方案将算术表达式从后缀表示方式翻译成中缀表示方式。给出输入 95-2* 和 952*- 的注释分析树

---

产生式 | 语义规则
expr -> $expr_1$ $expr_2$ op | expr.t = op.t ‖ $expr_1$.t ‖ $expr_2$.t
op -> +                  | op.t = '+'
op -> -                  | op.t = '-'
op -> *                  | op.t = '*'
op -> /                  | op.t = '/'
expr -> 0                | expr.t = '0'
expr -> 1                | expr.t = '1'
   ...                   |    ...
expr -> 9                | expr.t = '9'

    95- 2 * => * -95 2
    9 52* - => - 9 *52

语法分析树略

后3题略

**练习 2.4.1** 为下列文法构造递归下降语法分析器：

1) `S -> + S S | - S S | a`

    void stmt() {
        switch (lookhead) {
        case '+':
            lookahead = nextTerminal; stmt(); stmt();
            break;
        case '-':
            lookahead = nextTerminal; stmt(); stmt();
            break;
        case 'a':
            lookahead = nextTerminal;
            break;
        default:
            report("syntax error");
        }
    }

2) `S -> S ( S ) S |ϵ`

这个文法是有歧义的，并且也会造成左递归，我们来改造一下：

`S -> () | ( S ) S |ϵ`

    void stmt() {
        if (lookahead == '(') {
            match('(');
            if (nextTerminal == ')') match(')');
            else {
                stmt();
                match(')')
                stmt();
            }
        }
    }
    void match(char c) {
        // in the end of language, `nextTerminal` will be -1(EOF),
        // so do `lookahead`,
        // eventually syntax error reported.
        if (c == lookahead) lookahead = nextTerminal;
        else report("syntax error");
    }

3) `S -> 0 S 1 | 0 1`

    void stmt() {
        match('0');
        if (nextTerminal == '0') stmt();
        match('1');
    }
    void match(char c) {
        if (c == lookahead) lookahead = nextTerminal;
        else report("syntax error");
    }

**练习 2.6.1**
扩展2.6.5 节中的词法分析器以消除注释。注释的定义如下：

1. 以 `//` 开始的注释，包括从它开始到这一行的结尾的所有字符
2. 以 `/*` 开始的注释，包括从它到后面第一次出现的字符序列 `*/` 之间的所有字符

    for ( ; ; lookahead = peek, peek = next input character) {
        if ( peek is a blank or a tab ) do nothing;
        else if ( peek is a new line ) line = line + 1;
        else if (lookahead == '/') {
            if (peek == '/') {
                for ( ; ; lookahead = peek, peek = next input character) {
                    if (peek is a new line) {
                        line = line + 1;
                        break;
                    }
                }
            } else if (peek == '*') {
                for ( ; ; lookahead = peek, peek = next input character) {
                    if ( peek is a new line ) line = line + 1;
                    else if ( lookahead == '*' && peek == '/') {
                        peek = next input character;
                        break;
                    }
                }
            }
        }
        else break;
    }