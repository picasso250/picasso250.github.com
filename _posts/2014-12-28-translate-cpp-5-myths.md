---
title: 译.C++的5个迷思.草稿
layout: post
---

C++的亲爸爸写了这篇博客:
[Five Popular Myths about C++, Part 1](http://isocpp.org/blog/2014/12/myths-1)
[Five Popular Myths about C++, Part 2](http://isocpp.org/blog/2014/12/myths-2)
[Five Popular Myths about C++, Part 3](http://isocpp.org/blog/2014/12/myths-3)

在这篇博客里, 他讲了关于C++的5个众所周知的 **误解**, 看了之后, 我受益良多. 我深深反省, 以往我不喜欢C++, 是因为我不懂 C++, 又自以为很懂C, 而且盲目迷信 Linus. 现在用 C++ 做 leetcode, 很顺手.

简译如下

1. 要懂C++, 先得懂C
2. C++是面向对象的
3. GC 才可靠
4. 要效率, 整汇编
5. C++只适合开发大而复杂的系统

迷思一 要懂C++, 先得懂C
----------------------

不是这样的. 基本的C++编程可比C简单多了.

C是C++子集. 但并不是最容易学习的那部分. 它缺少运算符重载, 类型安全, 标准库也不像C++一样可以大大简化简单的工作. 考虑一个小小的函数, 构造一个电子邮件地址.

    string compose(const string& name, const string& domain)
    {
      return name+'@'+domain;
    }

这个函数的使用方式是这样的:

    string addr = compose("gre","research.att.com");

C版本的需要显式操作字符串和内存.

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

有了 C++11 之后, C++ 变得更易入门. 举个例子, 这是标准库 `vector` 的初始化方式.

    vector<int> v = {1,2,3,5,8,13};

C++98 时代, 我们只能用列表初始化数组, 而 C++11 时代, 我们可以定义一个constructor, 接受 {} initializer.

我们也可以用 range-for 遍历 vector.

    for(int x : v) test(x);

这行代码将对v 里的每个元素都调用 test().

range-for 可以遍历任何序列, 所以我们可以直接遍历 initializer list:

    for (int x : {1,2,3,5,8,13}) test(x);

C++11 的目标之一, 就是让简单的归于简单. 同时, 不引起性能过载.

迷思二 C++是面向对象的
------------------------

不. C++支持面向对象, 和其他一些编程范式, 不仅仅局限于某一个范式.

...

迷思三 要可靠, 还得靠GC
----------------------

垃圾收集机制运行的不错, 但远远谈不上完美. 内存可能有残留, 并且资源也不全是内存. 考虑如下情况:

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

在有垃圾收集机制的语言中, 我们不太会有 `delete` 关键字, 也没有解构函数(这个在逻辑上就不太稳定, 并且损害性能). 对内存的回收, 在这个例子中, GC 可以做到完美, 但是文件就不能自动回收了. 需要用户操作(代码), 手工管理资源容易产生bug.

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

### 三,一 转移所有权: move

来让我们先看看在作用域之间转移对象实例的问题. 这个问题的实质是如何从一个作用域中获得很多信息, 但同时不引起严重的性能过载, 也不使用易错的指针赋值. 传统的方案是使用指针:

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

等等, 这个 `*` 是怎么回事? 为了得到对象, 而非指针, 我需要这个符号. 大多数情况下, 这个比较简单. 但对于小对象, 我不会想动用指针这种大杀器.

    double sqrt(double); // a square root function
    double s2 = sqrt(2); // get the square root of 2

另一方面, 大数据对象一般是句柄对象. 比如 `istream`, `string`, `vector`, `list`, 和 `thread`. 他们只是几个信息字节, 以确保对大量数据的正确访问. 再次考虑矩阵的加法, 我们想要的效果是:

    Matrix operator+(const Matrix& a, const Matrix& b); // return the sum of a and b
    Matrix r = x+y;

很容易想到实现:

    Matrix operator+(const Matrix& a, const Matrix& b)
    {
      Matrix res;
      // ... fill res with element sums ...
      return res;
    }

默认情况下, 这种做法导致res复制到`r`, 但既然 `res` 只是即将被删除, 那么我们没必要复制, 我们只要换一下指针的指向就可以了, 就像将 `res` 偷出来一样. 这是个众所周知的技巧, 很多人也是这么做的, 但这个技巧总不是那么光明正大, 而且难于理解. C++11 直接支持句柄对象被转移所有权时"偷内存". 考虑2维的矩阵.

    class Matrix {
      double* elem; // pointer to elements
      int nrow;     // number of rows
      int ncol;     // number of columns
    public:
      Matrix(int nr, int nc)                  // constructor: allocate elements
        :elem{new double[nr*nc]}, nrow{nr}, ncol{nc}
      {
        for(int i=0; i<nr*nc; ++i) elem[i]=0; // initialize elements
      }

      Matrix(const Matrix&);                  // copy constructor
      Matrix operator=(const Matrix&);        // copy assignment

      Matrix(Matrix&&);                       // move constructor
      Matrix operator=(Matrix&&);             // move assignment

      ~Matrix() { delete[] elem; }            // destructor: free the elements

    // …
    };

我们用引用符号 `&` 表示复制操作, 用右值引用符号 `&&` 表示**移权**(所有权转移)操作. 移权操作应该"偷"到数据的表述, 然后留下一个空对象(源对象). 也就是:

    Matrix::Matrix(Matrix&& a)                   // move constructor
      :nrow{a.nrow}, ncol{a.ncol}, elem{a.elem}  // “steal” the representation
    {
      a.elem = nullptr;                          // leave “nothing” behind
    }

就是如此, 当编译器看到 `return res;`, 它意识到 `res` 即将被摧毁, 也就是说, 在return 之后, `res` 不会再被使用. 这意味着要使用<u>移权构造器</u>, 而不是<u>复制构造器</u>. 对于

    Matrix r = a+b;

`operator+()` 里的 `res` 变空了, `res` 里的元素变成了 `r`的元素, 我们成功的将结果的元素(或许成G大小的)移出了 `operator+()`, 变成调用者的变量. 同时, 代价很小(或许4个字长的赋值).

专家级的C++玩家会说, 优秀的编译器可以节省 return 时的复制代价(在这个case中, 就是省下了4个字长的拷贝, 和一个解构器的调用). 然而, 这依赖于实现, 我不喜欢我的程序的性能依赖于编译器的聪明程度(译者: 这是在黑JIT吗?). 不止如此, 一个可以消除复制的编译器, 也可以容易的消除移权操作. 我们上面讲的是一个简单可靠而且通用的方法, 当在作用域之间转移大量信息的时候, 它可以消除复杂度和代价,

再一次, 我们不需要定义这些复制和移动的操作, 如果一个类的成员变量已经有了正确的行为, 我们可以依赖于默认的行为. 考虑:

class Matrix {
    vector<double> elem; // elements
    int nrow;            // number of rows
    int ncol;            // number of columns
public:
    Matrix(int nr, int nc)    // constructor: allocate elements
      :elem(nr*nc), nrow{nr}, ncol{nc}
    { }

    // ...
};

这个版本的 `Matrix` 和上一个版本的行为一致, 除了他的表述更占空间一点(一个 `vector` 通常占用3个字长).

那么非句柄对象怎么办呢? 如果是小对象, 就像 `int` 或者 `complex<double>` 一样对待它, 不用操心. 否则就为它造一个句柄对象, 或者return的时候使用智能指针. 比如 `unique_ptr` 和 `shared_ptr`. 珍爱生命, 不使用裸指针, 远离 `new` 和 `delete`.

### 三,二 共享所有权: shared_ptr

懂垃圾收集机制的人都知道, 不是所有的对象都只有一个所有者. 这就意味着, 一旦最后一个指向它的引用被摧毁, 这个对象也应该被回收. 因此, 我们需要一个共享所有权的机制. 比如说, 两个任务之间互通信息的时候, 需要有一个同步队列 `sync_queue`. 一个消费者和一个生产者都有一个指向 `sync_queue` 的指针.

    void startup()
    {
      sync_queue* p  = new sync_queue{200};  // trouble ahead!
      thread t1 {task1,iqueue,p};  // task1 reads from *iqueue and writes to *p
      thread t2 {task2,p,oqueue};  // task2 reads from *p and writes to *oqueue
      t1.detach();
      t2.detach();
    }

我只关心一个问题: 谁应该删除 `sync_queue`? 经典的正确答案只有一个: 最后使用 `sync_queue` 的.垃圾收集的原始机制就是引用计数: 维护一个整数, 这个整数代表指向每个对象的指针数量, 一旦这个数量减少到0, 就删除这个对象. 许多语言都使用这个创意的变体, C++也支持这种机制, 也就是前面提到的 shared_ptr. 上面的例子就变成了:

    void startup()
    {
      auto p = make_shared<sync_queue>(200);  // make a sync_queue and return a stared_ptr to it
      thread t1 {task1,iqueue,p};  // task1 reads from *iqueue and writes to *p
      thread t2 {task2,p,oqueue};  // task2 reads from *p and writes to *oqueue
      t1.detach();
      t2.detach();
    }

现在 task1 和 task2 的分解器会摧毁他们拥有的所有 shared_ptr(大部分而言, 不需显式指明), 最后的任务会摧毁 sync_queue.

这样, 事情变得简单, 同时还相当高效. 没有什么复杂的运行时垃圾收集器. 最重要的是, 被共享的不止 sync_queue 占用的内存, 还有其他的同步结构(互斥体, 锁, 还有其他东西). 再一次, 我们拥有的不只是内存模型, 而是一种通用的资源模型. 我们处理背后的同步结构体的方式,就像之前我们处理文件流一样.

当然, 如果在某个作用域中我们引入一个变量, 让他和任务们同生死, shared_ptr 就变得不重要了. 但这并非总是容易的事. 所以 C++11 提供了 unique_ptr (用于独占所有权), 也提供了 shared_ptr (用于共享所有权).

### 三,三 类型安全

垃圾收集机制还关乎类型安全. 显式的删除操作经常被误用. 比如:

    X* p = new X;
    X* q = p;
    delete p;
    // ...
    q->do_something();  // the memory that held *p may have been re-used

裸 `delete` 非常危险且不必要. 让资源管理类(如 string, ostream, thread, unique_ptr, 和 shared_ptr) 使用 delete 操作符吧, 在那里, 它们和new 被小心的匹配, 因此无害.

### 三,四 总结:理想的资源管理

在资源管理这个问题上, 垃圾收集是我最后相到的解决方案, 它根本没资格称之为解决方案, 更理想的解决方案如下:

1. 使用适当的抽象, 使得资源被有层次的, 隐式的处理. 让这些类成为作用域变量, 让作用域控制它们的生死.
2. 当需要指针或者引用时, 使用智能指针, 比如 unique_ptr 或 shared_ptr, 来表示所有权.
3. 如果失败了(比如你的代码从属于一个傻逼程序, 这个程序使用了大量的指针, 也没有依赖于语言的资源管理机制), 那么就手动管理非内存资源, 然后使用一个传统的垃圾收集器来处理那几乎不可避免的内存泄露.

这个策略完美吗? 不, 但通用而且简单. 传统的垃圾收集机制也不完美, 而且也不能管理非内存的资源.