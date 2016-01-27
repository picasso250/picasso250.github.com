---
title:  给PHP使用者的Lua教程
layout: post
---

更详细的教程请去看：
http://www.lua.org/pil/2.5.html

**一、出发**

打印语句

    print("Hello World")

函数定义

    -- defines a factorial function
    function fact (n)
      if n == 0 then
        return 1
      else
        return n * fact(n-1)
      end
    end
    
    print("enter a number:")
    a = io.read("*number")        -- read a number
    print(fact(a))

**二、值和类型**

全局变量未赋值之前是nil。

    print(b)  --> nil
    b = 10
    print(b)  --> 10

单行注释用 `--` 开头，多行注释是 `--[[` 和 `]]`

函数名也是值，而非字符串

    a = print        -- yes, this is valid!

here doc 是这样的

    page = [[
    <HTML>
    <HEAD>
    <TITLE>An HTML Page</TITLE>
    </HEAD>
    <BODY>
     <A HREF="http://www.lua.org">Lua</A>
     [[a text between double brackets]]
    </BODY>
    </HTML>
    ]]
    
    write(page)

和PHP一样，lua是数字亲和的，所以加法可以直接：

    print("10" + 1)           --> 11
    print("10 + 1")           --> 10 + 1
    print("-5.3e-10"*"2")     --> -1.06e-09
    print("hello" + 1)        -- ERROR (cannot convert "hello")

除了一个例外，就是比较相等（以及比较大小）的时候，lua 中的 `==` 相当于 PHP 中的 `===`，所以你要

    print(tostring(10) == "10")   --> true
    print(10 .. "" == "10")       --> true

字符串连接是 `..`

    print(10 .. 20)        --> 1020

和PHP一样，lua只有一种复杂数据类型：表

但和PHP不一样，表是传引用的，和Java相似

    a = {}
    a["x"] = 10
    b = a      -- `b' refers to the same table as `a'
    print(b["x"])  --> 10
    b["x"] = 20
    print(a["x"])  --> 20
    a = nil    -- now only `b' still refers to the table
    b = nil    -- now there are no references left to the table

如果对应的key没有值，返回nil

    a = {}     -- empty table
    -- create 1000 new entries
    for i=1,1000 do a[i] = i*2 end
    print(a[9])    --> 18
    a["x"] = 10
    print(a["x"])  --> 10
    print(a["y"])  --> nil

`a.name` 是 `a["name"]` 的语法糖。欢迎进入面向对象的世界。

遍历整个表

    -- print the lines
    for i,line in ipairs(a) do
      print(line)
    end

有个惯例，Lua的数组的起始索引是1而非0

注意！有个情况和PHP不同。在数组索引的时候，字符串并不会自动转为整数！

    i = 10; j = "10"; k = "+10"
    a = {}
    a[i] = "one value"
    a[j] = "another value"
    a[k] = "yet another value"
    print(a[j])            --> another value
    print(a[k])            --> yet another value
    print(a[tonumber(j)])  --> one value
    print(a[tonumber(k)])  --> one value

**三、表达式**

不等号是 `~=`

逻辑符号是 `and` `or` `not` 和JS的逻辑一样，是短路而且返回操作数的值。

看看表的构造器

    days = {"Sunday", "Monday", "Tuesday", "Wednesday",
            "Thursday", "Friday", "Saturday"}

现在 `days[1] == "Sunday"` 

    a = {x=0, y=0}

**四、语句**

使用 `local` 关键字来声明一个本地的变量

    x = 10
    local i = 1        -- local to the chunk
    
    while i<=x do
      local x = i*2    -- local to the while body
      print(x)         --> 2, 4, 6, 8, ...
      i = i + 1
    end
    
    if i > 20 then
      local x          -- local to the "then" body
      x = 20
      print(x + 2)
    else
      print(x)         --> 10  (the global one)
    end
    
    print(x)           --> 10  (the global one)

while循环

    local i = 1
    while a[i] do
      print(a[i])
      i = i + 1
    end

for 循环

    for var=start,end,step do
      something
    end

for in 循环

    -- print all values of array `a'
    for i,v in ipairs(a) do print(v) end

**五、函数**

函数可以有多个返回值

函数参数可以是不定数目的

    function fwrite (fmt, ...)
      return io.write(string.format(fmt, unpack(arg)))
    end

有名字的函数参数可以这样实现

    rename{old="temp.lua", new="temp1.lua"}

这本质上是构造器作为参数的语法糖

**六、函数拾遗**

匿名函数

    foo = function (x) return 2*x end

Lua有尾递归。当然实质是只要是可以尾调用的都会优化，不一定要递归。尾调用本质是一种goto。

**七、迭代**

**八、执行**

`dofile` 相当于 `include`

`require` 相当于 `require_once`

错误处理

    print "enter a number:"
    n = io.read("*number")
    if not n then error("invalid input") end

这种情况很常见，所以有个

    print "enter a number:"
    n = assert(io.read("*number"), "invalid input")

`debug_backtrac()` 对应的代码是：

    print(debug.traceback())

**九、协程**

终于讲到PHP中没有的东西了

    co = coroutine.create(function ()
           print("hi")
         end)
    
    print(co)   --> thread: 0x8071d98

然后，执行之

    coroutine.resume(co)   --> hi

作为协程间传递消息的手段

    co = coroutine.create(function (a,b)
           coroutine.yield(a + b, a - b)
         end)
    print(coroutine.resume(co, 20, 10))  --> true  30  10

resume会返回yield的参数

**待续**
