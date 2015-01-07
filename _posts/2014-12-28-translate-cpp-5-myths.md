---
title: 翻译.C++的5个迷思.草稿
layout: post
---

C++的亲爸爸写了这篇博客:
[Five Popular Myths about C++,](http://isocpp.org/blog/2014/12/myths-2)

简译如下

1. 要懂C++, 先得懂C
2. C++是面向对象的
3. 要可靠, 还得靠GC
4. 要效率, 整汇编
5. C++只能用来开发大而复杂的系统

迷思一 要懂C++, 先得懂C
----------------------

不是这样的. 基本的C++编程可比C简单多了.

C基本上是C++的一个子集. 但并不是最容易学习的一个子集. 它缺少运算符重载, 类型安全, 标准库也不像C++一样可以大大简化简单的工作. 考虑一个小小的函数, 构造一个电子邮件地址.

    string compose(const string& name, const string& domain)
    {
      return name+'@'+domain;
    }

这个函数的使用函数是这样的:

    string addr = compose("gre","research.att.com");

C版本的这个函数需要显式操作字符串和内存.

    char* compose(const char* name, const char* domain)
    {
      char* res = malloc(strlen(name)+strlen(domain)+2); // space for strings, '@', and 0
      char* p = strcpy(res,name);
      p += strlen(name);
      *p = '@';
      strcpy(p+1,domain);
      return res;
    }

用起来是这样的:

    char* addr = compose("gre","research.att.com");
    // …
    free(addr); // release memory when done

你愿意教哪种版本? 你愿意用哪个? 上面的C版本真的正确吗? 为什么?

最后, 你猜那个版本性能好? 当然, 是C++版的. 因为不需要计算字符串的长度, 也不需要在堆上分配空间.

### 学习 C++

这不是孤例, 这是很典型的例子. 为什么那么多老师还在先教C呢?

- 因为这是传统
- 因为大家都这么做
- 因为当年他们的老师就是这么教的
- 因为C比C++小, 所以应该更简单吧
- 因为学生们迟早要学C

然而, C并不是C++易学易用的部分. 并且, 学习相当的C++之后, C子集也就好学了. 在C++之前学C就意味着要重新忍受C的那些缺陷, 而这些都是C++中已经避免了的.

有了 C++11 之后, C++ 变得更易入门. 举个例子, 这是标准库 vector 的初始化方式.

    vector<int> v = {1,2,3,5,8,13};

C++98 时代, 我们只能用列表初始化数组, 而 C++11 时代, 我们可以定义一个constructor, 接受 {} initializer.

我们也可以用 range-for 遍历 vector.

    for(int x : v) test(x);

这行代码将对v 里的每个元素都调用 test().

range-for 可以遍历任何序列, 所以我们可以直接遍历 initializer list:

    for (int x : {1,2,3,5,8,13}) test(x);

C++11 的目标之一, 就是让简单的归于简单. 自然, 这个简单不能以性能为代价.

迷思二 C++是面向对象的
------------------------

不. C++支持面向对象, 和其他一些编程范式, 不仅仅局限于某一个范式.

...

迷思三 要可靠, 还得靠GC
----------------------

垃圾收集机制运行的不错, 但远远谈不上完美. 内存可能有残留, 有些资源也全是内存. 考虑如下情况:

    class Filter { // take input from file iname and produce output on file oname
    public:
      Filter(const string& iname, const string& oname); // constructor
      ~Filter();                                        // destructor
      // ...
    private:
      ifstream is;
      ofstream os;
      // ...
    };

Filter 的构造器打开了两个文件. 然后, Filter 读入文件内容, 做一些过滤, 然后输出文件. 过滤机制可能是硬编码在 Filter 里, 也可能是通过回调函数提供, 这不重要, 我们要讨论的是资源管理. 我们可能这么创建 Filter:

    void user()
    {
      Filter flt {“books”,”authors”};
      Filter* p = new Filter{“novels”,”favorites”};
      // use flt and *p
      delete p;
    }

在有垃圾收集机制的语言中, 我们不太会有 `delete` 关键字, 也没有解构函数(这个在逻辑就不太稳定, 并且损害性能). 对内存的回收, 在这个例子中, GC 可以做到完美, 但是文件就不能自动回收了. 需要用户操作(代码), 手工管理资源容易产生bug.

传统的C++代码使用解构函数来确保资源被正确回收. 一般说来, 这种资源在构造函数中申请, 这种方式被称为 RAII. 在 `user()` 中, `flt` 的解构函数会隐式调用 `is` 和 `os` 的解构函数, 这些解构函数依序关闭文件, 释放相关资源. delete 操作符也释放 *p 的相关资源.

C++老手会发现 `user()` 还是罗嗦易错的, 下面的会更好一些:

    void user2()
    {
      Filter flt {“books”,”authors”};
      unique_ptr<Filter> p {new Filter{“novels”,”favorites”}};
      // use flt and *p
    }

现在 `*p` 会在 `user2()` 结束的时候自动释放资源. `unique_ptr` 是标准库的一个类, 被设计为确保资源泄露, 同时不会引起时间和空间的过载, 所以不要再使用裸指针了.

然而, 我们还是看得到 new, 这个方案还是有点繁复(Filter重复了), 而且智能指针阻止了性能优化. 使用C++14的仆人函数 make_unique, 这个函数构造一个对象, 并且返回这个对象的 `unique_ptr`.

    void user3()
    {
      Filter flt {“books”,”authors”};
      auto p = make_unique<Filter>(“novels”,”favorites”);
      // use flt and *p
    }

除非我们真的需要第二个Filter的指针形式(以后就不太需要了), 我们可以这样写:

    void user3()
    {
      Filter flt {“books”,”authors”};
      Filter flt2 {“novels”,”favorites”};
      // use flt and flt2
    }

最后一个版本更短, 更简单, 更清晰, 更快.

那么, 还有最后一个问题: Filter的解构器做了什么? 它释放Filter所使用的所有资源, 也就是说, 关闭文件(通过调用他们的解构器). 实际上, 这些都是(隐式)应当的, 所以除非Filter还有资源要释放, 我们可以移除解构器的声明, 编译器会替我们做好. 所以, 刚才我写下来的只是:

    class Filter { // take input from file iname and produce output on file oname
    public:
      Filter(const string& iname, const string& oname);
      // ...
    private:
      ifstream is;
      ofstream os;
      // ...
    };
     
    void user3()
    {
      Filter flt {“books”,”authors”};
      Filter flt2 {“novels”,”favorites”};
      // use flt and flt2
    }

真巧, 比大部分有垃圾收集机制的语言(Java, C#)都要简洁, 而且关上了所以资源泄露的大门, 妈妈再也不用担心程序员忘记关文件了. 

这是我理想中的资源管理方式. 这里的资源不仅仅是内存, 还包括其他的资源, 比如文件, 线程 和 锁. 但是这种方式真的普适吗? 如果资源作为参数传进函数中去会如何? 如何一个资源的所有者不止一个会如何?

### 转移所有权: move

来让我们先看看在作用域之间转移对象实例的问题. 这个问题的实质是如何从一个作用域中获得很多信息, 但同时不引起严重的过载或者易错的指针赋值. 传统的方案是使用指针:

    X* make_X()
    {
      X* p = new X:
      // ... fill X ..
      return p;
    }

    void user()
    {
      X* q = make_X();
      // ... use *q ...
      delete q;
    }

那么现在谁来负责回收X呢? 在这个简单的例子中, 当然是 make_X() 的调用者(我觉得翻译成上位者也可以)了, 但一般来说, 问题都比这个复杂. 如果 make_X() 中有一个缓存呢? 如果 user() 又把指针传给别的函数呢? 这种形式写程序很容易乱, 也容易出现内存泄露.

我可以使用 shared_ptr 或者 unique_ptr 来说明所有权的转上, 比如:

    unique_ptr<X> make_X();

但是, 为什么要用指针呢? 即使是智能的. 一般来说, 我不喜欢用指针, 因为在使用对象时使用指针会分散我们的注意力. 举个例子, 两个矩阵相加会返回一个新的矩阵对象, 但是返回对象的指针会导致一些非常奇怪的代码.

    unique_ptr<Matrix> operator+(const Matrix& a, const Matrix& b);
    Matrix res = *(a+b);

