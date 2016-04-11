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

## **练习 2.4.1**
为下列文法构造递归下降语法分析器：

这个很好构造，只要照抄原来的代码就行了

1) `S -> + S S | - S S | a`

    void S() {
        switch ( lookahead ) {
        case '+':
            match('+'); S(); S(); break;
        case '-':
            match('-'); S(); S(); break;
        case 'a':
            match('a'); break;
        default:
            report("syntax error");
        }
    }

2) `S -> S ( S ) S |ϵ`

这个语法其实就是我们常见的括号配对。

但这会造成“左递归”。我们首先进行改写

    S → ε R
    R → ( S ) S R | ε

然后就可以这样写：

    void S() {
        if (lookahead == '(') R();
    }
    void R() {
        switch ( lookahead ) {
        case '(':
            match('('); S(); match(')'); S(); R();
            break;
        case EOF:
            break;
        default:
            report("syntax error");
        }
    }

3) `S -> 0 S 1 | 0 1`

S的两个分支都是0开头（FIRST集合相交），所以这个对递归下降语法分析器不适用

这个规则可以这样修改

    S → 0 R 1
    R → S | ε

其实这样修改过之后，依然存在FIRST集合相交的问题，但是，你懂的，我们的代码就可以了

    void S() {
        match('0'); R(); match('1');
    }
    void R() {
        if (lookahead == '0') S();
    }

## **练习 2.6.1**
扩展2.6.5 节中的词法分析器以消除注释。注释的定义如下：

1. 以 `//` 开始的注释，包括从它开始到这一行的结尾的所有字符
2. 以 `/*` 开始的注释，包括从它到后面第一次出现的字符序列 `*/` 之间的所有字符

---
难点有两个：

第一点，不能放弃 / 号（除号）；

第二点，懂的何时结束。
首先，将peek的类型改为int（这一点是为了标识文件结束，与本题无关）。然后在处理空白处加入如下代码：

    for ( ;; peek = read()) {
        if (peek == ' ' || peek == '\t' || peek == '\r') continue;
        else if (peek == '\n') line++;
        else if (peek == '/') {
            peek = in.read();
            if (peek == -1) {
                throw new Error("syntax error, div can't at file end");
            }
            if (peek == '*') {
                multiComment();
            } else if (peek == '/') {
                singleComment();
            } else {
                System.out.print(new Token('/'));
                return new Token('/');
            }
        }
        else break;
    }

这个程序结构可以清楚的看出我们是如何处理 `/` 号的，重点在于预读一个字符。
处理单行注释比较简单，就是遇到换行符就停下来：

    private void singleComment() throws IOException {
        for (; ; peek = read()) {
            if (peek == '\n') {
                line++; break;
            }
        }
    }

而多行注释要复杂一些，首先遇到 `*`，然后再预读一个字符做决定

    private void multiComment() throws IOException, Error {
        peek = in.read();
        if (peek == -1) {
            throw new Error("syntax error, comment not finished");
        }
        while(true) {
            if (peek == '*') {
                peek = in.read();
                if (peek == -1) {
                    throw new Error("syntax error, comment not finished");
                }
                if (peek == '/') break;
                else continue;
            }
            if (peek == '\n') line++;
            peek = in.read();
            if (peek == -1) {
                throw new Error("syntax error, comment not finished");
            }
        }
    }

## **练习 2.6.2**

> 扩展 2.6.5 节中的词法分析器，使它能够识别关系运算符 <、<=、==、!=、>=、>。

这个难点也在双符号上，怎么在识别 <= 的同时识别<。

同样也是用预读字符处理。

首先扩展符号表

    public class Tag {
        public final static int
            NUM = 256, ID = 257, TURE = 258, FALSE = 259,
            LT = '<', LE = 261, EQ = 263, NE = 264, GE = 265, GT = '>';
    }

然后，写出<和 <= 的处理

    if (peek == '<') {
        peek = in.read();
        if (peek == -1)
            throw new Error("syntax error, '<' can't at file end");
        if (peek == '=') {
            peek = ' ';
            return new Token(Tag.LE);
        } else
            return new Token(Tag.LT);
    }

注意，如果遇到双符号，一定要把peek变成空白符，因为你已经处理了双符号的第二个符号！

其余的符号做类似处理即可。

多说一句，这个将peek置为空白符的行为，看起来不显山不露水，可实际上真的是非常巧妙的简化代码的行为！

## **练习 2.6.3**

扩展 2.6.5 节中的词法分析器，使它能够识别浮点数，比如2.、3.14、.5 等。

首先建立两个新类型 Integer 和 Double

    public class Integer extends Num {
        public final int value;
        public Integer(int v) {
            super(Tag.DOUBLE); value = v;
        }
        @Override
        public String toString() {
            return "<Integer " + value + ">";
        }

    }
    public class Double extends Num {
        public final double value;
        public Double(double v) {
            super(Tag.DOUBLE); value = v;
        }
        @Override
        public String toString() {
            return "<Double " + value + "> ";
        }

    }

然后修改分析数字的方法。
同理，将点重新处理即可。

    if (Character.isDigit(peek)) {
        int v = 0;
        Integer n = null;
        do {
            v = v * 10 + Character.digit(peek, 10);
            peek = in.read();
        } while (peek != -1 && Character.isDigit((char)peek));
        n = new Integer(v);
        if (peek == -1) {
            System.out.print(n);
            return n;
        }
        if (peek == '.') {
            double s = 0, dp = 0.1;
            while (true) {
                peek = in.read();
                if (!Character.isDigit((char)peek)) {
                    Double d = new Double(v+s);
                    System.out.print(d);
                    return d;
                }
                s += Character.digit(peek, 10) * dp;
                dp *= 0.1;
            }
        } else {
            System.out.print(n);
            return n;
        }
    }
    if (peek == '.') {
        peek = in.read();
        if (Character.isDigit(peek)) {
            double s = 0, dp = 0.1;
            do {
                s += Character.digit(peek, 10) * dp;
                dp *= 0.1;
                peek = in.read();
            } while (peek != -1 && Character.isDigit((char)peek));
            Double d = new Double(s);
            System.out.print(d);
            return d;
        } else {
            Token t = new Token('.');
            System.out.print(t);
            return t;
        }
    }

---

或者，也可以使用另一种思维方式

    public class Num extends Word {

        public Num(int tag, String lexeme) {
            super(tag, lexeme);
        }
        public static Num fromString(String lexeme) {
            int i = 0;
            float f = 0.0f;
            int base = 10;
            boolean after = false;
            for (char c : lexeme.toCharArray()) {
                if (after) {
                    f += Character.digit(c, 10) * 1.0 / base;
                    base = base * 10;
                } else {
                    if (c == '.') {
                        after = true;
                        continue;
                    }
                    i = 10 * i + Character.digit(c, 10);
                }
            }
            if (after) {
                return new Float(lexeme, i+f);
            } else {
                return new Int(lexeme, i);
            }
        }

最后将 Lex 类中 分析数字的代码改掉

        if (isNumChar(peek)) {
            StringBuffer b = new StringBuffer();
            do {
                b.append(peek);
                peek = (char)System.in.read();
            } while (isNumChar(peek));
            String s = b.toString();
            return Num.fromString(s);
        }

当然不要忘记这个 helper 函数

    private boolean isNumChar(char peek) {
        return Character.isDigit(peek) || peek == '.';
    }

## 练习 2.8.1

> C语言和Java 语言中的 for 语句具有如下形式：
>
>    for( $expr_1; expr_2; expr_3$ ) stmt
>
> 为 for 语句定义一个类 For

for 语句相当于 while 语句

$expr_1$; while ( $expr_2$ ) { stmt $expr_3$; }

所以，可以参考 while 语句的写法

    public class For extends Stmt {
        Expr E1; Expr E2; Expr E3; Stmt S;
        public For(Expr e1, Expr e2, Expr e3, Stmt s) {
            super();
            E1 = e1;
            E2 = e2;
            E3 = e3;
            S = s;
        }
        @Override
        public void gen() {
            E1.rvalue();
            String start = newlabel();
            String end = newlabel();
            emit(start+":");
            Expr cond = E2.rvalue();
            emit("ifFalse "+cond+" goto "+end);
            S.gen();
            E3.rvalue();
            emit("goto "+start);
            emit(end+":");
        }
    }

##练习 2.8.2

> 程序设计语言 C 中没有布尔类型。试说明 C 语言的编译器可能使用什么方法将一个 if 语句翻译成为三地址代码。

在 c 语言中，不等于 0 就是真，等于 0 就是假，所以：

    ifFasle => ifEqual0
    ifTrue  => ifNotEqual0
