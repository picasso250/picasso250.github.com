---
title: 译.C++的5个迷思
layout: post
comments: true
---

C++的亲爸爸写了一系列博客:

- [Five Popular Myths about C++, Part 1](http://isocpp.org/blog/2014/12/myths-1)
- [Five Popular Myths about C++, Part 2](http://isocpp.org/blog/2014/12/myths-2)
- [Five Popular Myths about C++, Part 3](http://isocpp.org/blog/2014/12/myths-3)

在这些博客里, 他讲了关于C++的5个众所周知的 **误解**, 看了之后, 我受益良多. 我深深反省, 以往我不喜欢C++, 是因为我不懂 C++, 又自以为很懂C, 而且盲目迷信 Linus. 现在用 C++ 做 leetcode, 很顺手.

选译如下

1. 要懂C++, 先得懂C
2. C++是面向对象的(翻译略过)
3. 可靠的软件都用GC
4. 只有汇编才是真效率
5. C++只适合开发大而复杂的系统

# 迷思一 要懂C++, 先得懂C #

不是这样的. 基本的C++编程可比C简单多了.

C是C++子集. 但并不是最易学的子集. C缺少 _运算符重载_ 和 _类型安全_, 标准库比较原始. 而 C++ 的标准库可以让简单的工作保持简单. 考虑一个小函数 _构造一个电子邮件地址_:

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

如果你是一个老师, 你愿意教学生哪种版本? 如果你是个学生, 你愿意使用哪个? 上面的C版本真的正确吗? 为什么?

最后, 你猜那个版本性能好? 当然, 是C++版的. 因为不需要计算字符串的长度, 也不需要在堆上分配空间.

## 一,1 学习 C++ ##

上面的例子不是孤例, 而是很典型的例子. 那为什么很多老师还在先教C呢?

- 因为这是传统
- 因为大环境是这样的
- 因为当年的老师就是这么教的
- 因为 C 比 C++ 小, 所以应该更易用
- 因为学生们迟早要学 C

然而, C并不是C++易学易用的部分. 在学通了 C++ 之后, C子集也就好学了. 在 C++ 之前学C就意味着要再次忍受C的缺陷, 而这些都是C++中已经避免了的.

有了 C++11 之后, C++ 变得更易入门. 举个例子, 这是标准库 `vector` 的初始化方式.

    vector<int> v = {1,2,3,5,8,13};

C++98 时代, 我们只能用列表初始化数组, 而 C++11 时代, 我们可以定义一个constructor, 接受 `{}` initializer.

我们也可以用 `range-for` 遍历 `vector`.

    for(int x : v) test(x);

这行代码将对 `v` 里的每个元素都调用 `test()`.

range-for 可以遍历任何序列, 所以我们可以直接遍历 initializer list:

    for (int x : {1,2,3,5,8,13}) test(x);

C++11 的目标之一, 就是让简单的归于简单, 同时不引起性能过载.

# 迷思二 C++是面向对象的 #

不. C++支持面向对象, 和其他一些编程范式, 不仅仅局限于某一个范式.

翻译略.

# 迷思三 可靠的软件都用GC #

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

在有垃圾收集机制的语言中, 没 `delete` 关键字, 也没有解构函数. 对内存的回收, 在这个例子中, GC 可以做到完美, 但是文件就不能自动回收了. 需要用户写代码, 手工管理资源容易产生bug.

传统的C++代码使用解构函数来确保资源被正确回收. 一般说来, 这种资源在构造函数中申请, 这种方式被称为 RAII. 在 `user()` 中, `flt` 的解构函数会隐式调用 `is` 和 `os` 的解构函数, 这些解构函数依序关闭文件, 释放相关资源. delete 操作符也释放 *p 的相关资源.

C++老手会发现 `user()` 还是罗嗦易错的, 下面的会更好一些:

    void user2()
    {
      Filter flt {“books”,”authors”};
      unique_ptr<Filter> p {new Filter{“novels”,”favorites”}};
      // use flt and *p
    }

现在 `*p` 会在 `user2()` 结束的时候自动释放资源. `unique_ptr` 是标准库的一个类, 被设计为确保资源不泄露, 同时不会引起时间和空间的过载, 所以不要再使用裸指针了.

然而, 我们还是看得到 new, 这个方案还是有点繁复(Filter类型声明重复了), 而且智能指针阻止了性能优化. 使用C++14的仆人函数 `make_unique`, 这个函数构造一个对象, 并且返回这个对象的 `unique_ptr`.

    void user3()
    {
      Filter flt {“books”,”authors”};
      auto p = make_unique<Filter>(“novels”,”favorites”);
      // use flt and *p
    }

除非我们真的需要第二个Filter的指针形式(最好不需要), 我们可以这样写:

    void user3()
    {
      Filter flt {“books”,”authors”};
      Filter flt2 {“novels”,”favorites”};
      // use flt and flt2
    }

最后一个版本更短, 更简单, 更清晰, 更快.

那么, 还有最后一个问题: Filter的解构器做了什么? 它释放Filter占用的所有资源, 也就是说, 关闭文件(通过调用他们的解构器). 实际上, 这些都是(隐式)应当的, 所以除非Filter还有资源要释放, 我们可以移除解构器的声明, 编译器会替我们做好. 所以, 刚才我写下来的只是:

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

### 三,3 类型安全

垃圾收集机制还关乎类型安全. 显式的删除操作经常被误用. 比如:

    X* p = new X;
    X* q = p;
    delete p;
    // ...
    q->do_something();  // the memory that held *p may have been re-used

裸 `delete` 非常危险且不必要. 让资源管理类(如 string, ostream, thread, unique_ptr, 和 shared_ptr) 使用 delete 操作符吧, 在那里, 它们和new 被小心的匹配, 因此无害.

### 三,4 总结:理想的资源管理

在资源管理这个问题上, 垃圾收集是我最后相到的解决方案, 它根本没资格称之为解决方案, 更理想的解决方案如下:

1. 使用适当的抽象, 使得资源被有层次的, 隐式的处理. 让这些类成为作用域变量, 随作用域生, 随作用域死.
2. 当需要指针或者引用时, 使用智能指针, 比如 `unique_ptr` 或 `shared_ptr`, 来表示所有权.
3. 如果失败了(比如你的代码从属于一个傻逼程序, 这个程序使用了大量的指针, 也没有依赖于语言的资源管理机制), 那么就手动管理非内存资源, 然后使用一个传统的垃圾收集器来处理那几乎不可避免的内存泄露.

这个策略完美吗? 不, 但通用而且简单. 传统的垃圾收集机制也不完美, 而且也不能管理非内存的资源.

# 迷思四 想要获得性能, 只能写底层代码 #

大多数人都认为高性能的代码一定得是底层的. 甚至有人认为底层代码就代表高性能(如果一段代码很丑, 就一定跑得快! 因为人家花了大量的时间和精力来写这段丑得不常规的代码). 你当然可以只用底层代码写高效的程序, 而且有些机器资源确实得用底层代码访问. 但是, 时刻记得要测量一下, 看看你的努力是否值得; 现代的 C++ 编译器十分高效, 而且现代的机器架构非常神奇(tricky). 如果有必要, 底层代码最好隐藏在一个良好设计的介面背后, 以方便使用. 通常情况下, 将底层封装后, 也有利于更好的优化性能(比如, 通过防止滥用特性). 总是尝试先向高层语言要性能, 不要一开始就陷入位操作和指针中.

## 四,1 C 的 qsort()

考虑一个简单的例子. 如果你想降序排序一些浮点数, 你可以直接写一段代码. 然而, 除非有特殊需求(比如内存中装不下这些浮点数), 那么再写一遍代码真的是太天真了. 古老的代码已经被先贤写就, 性能可接受. 我最不喜欢的就是C标准库的 `qsort()`:

    int greater(const void* p, const void* q)  // three-way compare
    {
      double x = *(double*)p;  // get the double value stored at the address p
      double y = *(double*)q;
      if (x>y) return 1;
      if (x<y) return -1;
      return 0;
    }

    void do_my_sort(double* p, unsigned int n)
    {
      qsort(p,n,sizeof(*p),greater);
    }

    int main()
    {
      double a[500000];
      // ... fill a ...
      do_my_sort(a,sizeof(a)/sizeof(*a));  // pass pointer and number of elements
      // ...
    }

如果你不是一个C程序员或者你最近没用过`qsort`, 可能需要一些解释; `qsort` 需要4个参数

- 一个指针, 指向二进制序列
- 元素的个数
- 元素的大小
- 一个比较函数, 参数是两个指针

注意, 这个接口丢失了信息. 我不是在排序普通数据. 我们是在排序 `double`, 但是 `qsort` 不知道这个, 所以, 我们必须提供比较`double`的方法, 也必须提供`double`数据大小. 当然, 编译器其实已经完全知道这些信息了. 然而, `qsort` 的底层接口使得编译器利用这些类型信息变得不可能. 必须显示声明简单信息也给错误打开了大门. 那两个整数参数就容易搞混位置, 要是真的搞混了位置, 编译器又不可能提醒你. `compare()`函数有没有遵循C的三路比较的传统? 这个问题你也得关注一下.

如果你去看看一个工业级的 `qsort`的实现(一定要去看), 你会发现为了补偿信息缺失, 库函数的作者们真的很努力. 比如说, 交换两个元素的通用算法和交换两个`double`的复杂度不可同日而语, 效率也大大降低. 比较函数的开销也只能在编译器对函数指针做了常量增值之后消失.

### 五,二 `C++` 的 sort()

比较C++的相同功能的函数 `sort()`:

    void do_my_sort(vector<double>& v)
    {
      sort(v,[](double x, double y) { return x>y; });  // sort v in decreasing order
    }
     
    int main()
    {
      vector<double> vd;
      // ... fill vd ...
      do_my_sort(v);
      // ...
    }

这段代码就更加不言自明. `vector`知道自己的尺寸, 所以, 没要必要明说元素的个数. 我们从未丢失过 元素的类型, 也就不需要处理元素的尺寸. 默认情况下, `sort()` 使用升序排列, 所以 我必须指定排序方式, 就如同我在 `qsort()`中做的那样. 这里, 我使用一个 lambda, 它使用 `>` 比较两个 double. 就我所知, 所有的C++编译器都会在这里内联这个函数, 所以也就是一个大于比较的机器指令, 不再需要低效的函数调用.

我使用了一个容器版本的 `sort()` 可以不必显示指定迭代器. 也就是说, 我不要这样:

    std::sort(v.begin(),v.end(),[](double x, double y) { return x>y; });

而要这样:

    sort(v,greater<>()); // sort v in decreasing order

那个版本更快? 你可以将 `qsort()` 作为c或者c++编译一下, 你会发现没有性能差别, 所以, 这是编程风格的比较, 不是 编程语言的比较. `sort`和`qsort`的实现算法似乎总是一样的, 所以, 这是编程风格的比较, 不是算法的比较. 不同的编译器和不同的库, 给出的结果当然不一样, 但是总是一个合理的反应.

我最近运行了例子, 发现 `sort()` 版本的速度是 `qsort()`版本的2.5倍. 具体数值可能因编译器而异, 但我从未见过 `qsort()` 打败了 `sort()`. 我曾经见过 `sort()` 比 `qsort()` 快10倍的. 怎么会这样? C++标准库的 `sort` 明显更高层, 更通用, 更柔软. 它是类型安全的, 可以对存储类型, 元素类型 和 排序方法偏特化. 没有指针, 强制类型转换, 和byte. C++标准库真的很努力, 没有丢失什么信息. 使得内联和优化非常方便.

通用且高层的代码可以打败底层代码. 当然, 这并不总是对的, 但 `sort`/`qsort`的比较不是孤例. 开始解决问题是总是首选高层, 精确, 类型安全的版本. (只有在)必要时才优化.

# 迷思五 C++ 只能搞大项目

C++博大精深. 它的定义和C# Java 非常相像. 但这并不意味着你必须知道所有的细节, 或者在每个项目中都使用所有的特性. 考虑一个只使用标准库的基础的组件的程序:

    set<string> get_addresses(istream& is)
    {
      set<string> addr;
      regex pat { R"((\w+([.-]\w+)*)@(\w+([.-]\w+)*))"}; // email address pattern
      smatch m;
      for (string s; getline(is,s); )                    // read a line
        if (regex_search(s, m, pat))                     // look for the pattern
          addr.insert(m[0]);                             // save address in set
      return addr;
    }

在此, 我假设你了解正则表达式. 如果你不了解, 劝你现在学一下. 注意我使用了移动语义来简洁而高效的返回可能很大的数据. 所有的标准库容器都支持移动语义, 所有, 不用再关心new了.

为了让这个正常运行, 我们需要包含合适的标准库组件.

    #include<string>
    #include<set>
    #include<iostream>
    #include<sstream>
    #include<regex>
    using namespace std;

让我们来做测试:

    istringstream test {  // a stream initialized to a sting containing some addresses
      "asasasa\n"
      "bs@foo.com\n"
      "ms@foo.bar.com$aaa\n"
      "ms@foo.bar.com aaa\n"
      "asdf bs.ms@x\n"
      "$$bs.ms@x$$goo\n"
      "cft foo-bar.ff@ss-tt.vv@yy asas"
      "qwert\n"
    };
     
    int main()
    {
      auto addr = get_addresses(test);  // get the email addresses
      for (auto& s : addr)              // write out the addresses
        cout << s << '\n';
    }

这只是一个例子, 很容易就可以修改 `get_addresses()` 让它接受 `regex` 正则表达式作为参数, 这样就可以寻找 URL或者其他什么东西了. 很容易修改 `get_addresses()` 让它辨认某个模式在一行中更多出现的更多次数. 毕竟, C++是为了柔性和通用性设计的, 不是所有的程序必须成为一个完整的库或者框架. 然而, 我要指出的重点是一个简单的任务, 如在输入流中抽取email地址是非常容易被表达和测试的.

## 五.1 库

在任何语言中, 只是用语言的内建特征写程序会是一件非常冗长的事情. 按照惯例, 给出合适的库, 比如图形库, 数据库库, 可以用合理的成本写出实干的程序.

ISO C++标准库相对而言很小(和一些商业库比), 但是颇有一些开源或者商业的库可以用. 比如, Boost, POCO, AMP, TBB, Cinder, vxWidgets和CGAL. 许多通用或者特殊的工作可以变得简单. 我来举个例子. 让我们修改上面的程序变成从网页读取URL的程序. 首先, 我们应该通用化 `get_address()` 来寻找任何string匹配模式的:

    set<string> get_strings(istream& is, regex pat)
    {
      set<string> res;
      smatch m;
      for (string s; getline(is,s); )  // read a line
      if (regex_search(s, m, pat))
        res.insert(m[0]);              // save match in set
      return res;
    }

这只是一个简化, 现在, 我们得指出如何读取一个网络上的文件. Boost 有一个库, `asio`, 可以和网络交互.

    #include “boost/asio.hpp” // get boost.asio

和网络服务器交互有点复杂:

    int main()
    try {
      string server = "www.stroustrup.com";
      boost::asio::ip::tcp::iostream s {server,"http"};  // make a connection
      connect_to_file(s,server,"C++.html");    // check and open file
     
      regex pat {R"((http://)?www([./#\+-]\w*)+)"}; // URL
      for (auto x : get_strings(s,pat))    // look for URLs
        cout << x << '\n';
    }
    catch (std::exception& e) {
      std::cout << "Exception: " << e.what() << "\n";
      return 1;
    }

看看 `www.stroustrup.com` 的文件 `C++.html`, 这个有:

    http://www-h.eng.cam.ac.uk/help/tpl/languages/C++.html
    http://www.accu.org
    http://www.artima.co/cppsource
    http://www.boost.org
    ...

我使用了set, 所以, URL是按照字典序的.

我偷摸的, 但并非不切实际的将HTTP连接的细节隐藏在一个函数(`connect_to_file()`)中.

    void connect_to_file(iostream& s, const string& server, const string& file)
      // open a connection to server and open an attach file to s
      // skip headers
    {
      if (!s)
        throw runtime_error{"can't connect\n"};
     
      // Request to read the file from the server:
      s << "GET " << "http://"+server+"/"+file << " HTTP/1.0\r\n";
      s << "Host: " << server << "\r\n";
      s << "Accept: */*\r\n";
      s << "Connection: close\r\n\r\n";
     
      // Check that the response is OK:
      string http_version;
      unsigned int status_code;
      s >> http_version >> status_code;
     
      string status_message;
      getline(s,status_message);
      if (!s || http_version.substr(0, 5) != "HTTP/")
        throw runtime_error{ "Invalid response\n" };
     
      if (status_code!=200)
        throw runtime_error{ "Response returned with status code" };
     
      // Discard the response headers, which are terminated by a blank line:
      string header;
      while (getline(s,header) && header!="\r")
        ;
    }

如同普通人做的那样, 我并不是从无到有的建立了一个程序.

## 5.2 你好, 世界!

C++是一门编译型语言, 其主要设计目的是为了发布好的, 可维护的代码, 同时不失性能和可靠性. 它不是为了直接和解释型语言或者最小化编译的脚本语言的小程序竞争的. 确实, 这些语言 如 JavaScript 或者 Java 经常用C++实现. 然而, 有很多有用的C++程序只不过几百行.

C++库作者们可以在这一点上帮助我们. 希望他们不只是聚焦于写出更聪明或者更高级的库, 也提供一个易于尝试的 "Hello, World" 例子. 提供一个细致的安装指南, 以及一个最多一页的 "Hello, World" 例子展示一下你的库可以做什么. 有时, 我们都是初学者. (译者: 吐槽一下, C++社区总是以为自己更聪明, 多学学人家ruby社区. 就算是天才聚集的haskell社区也没这么不友好. 亲爹还是看到了这一点的.) 顺便一提, 我的C++版的"Hello, World"是:

    #include<iostream>
     
    int main()
    {
      std::cout << "Hello, World\n";
    }

我发现代码越长, 展示时那种震惊的美妙感觉反而越少. 这就是我对ISO C++和它的标准库的一个勾勒.
