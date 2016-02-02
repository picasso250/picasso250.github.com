---
title:  Lua Coroutine 实现 迭代器
layout: post
---

更详细的教程请去看：
http://www.lua.org/pil/9.3.html

现在有个任务是生成某个数组的全排列。很明显，用递归将是一个比较好的解决方案。有一个长度为n的数组， `$a_1,a_2,\dots a_n$` ，如果我们能得出n-1长度的全排列，那么将每个元素都拿出数组，后面跟着n-1数组的全排列，就是我们要的答案。

    function permgen (a, n)
      if n == 0 then
        printResult(a)
      else
        for i=1,n do
    
          -- put i-th element as the last one
          a[n], a[i] = a[i], a[n]
    
          -- generate all permutations of the other elements
          permgen(a, n - 1)
    
          -- restore i-th element
          a[n], a[i] = a[i], a[n]
    
        end
      end
    end

我们可以实地的看一下运行结果

    function printResult (a)
      for i,v in ipairs(a) do
        io.write(v, " ")
      end
      io.write("\n")
    end
    
    permgen ({1,2,3,4}, 4)

当然，我们希望这个函数不止能打印，我们还希望他能做别的事，比如：遍历
那我们只要简单的

    function permgen (a, n)
      if n == 0 then
        coroutine.yield(a)
      else
      ...

当然，要想适合 for in 循环，我们需要简单的包装一下：

    function perm (a)
      local n = table.getn(a)
      local co = coroutine.create(function () permgen(a, n) end)
      return function ()   -- iterator
        local code, res = coroutine.resume(co)
        return res
      end
    end

然后就可以：

    for p in perm{"a", "b", "c"} do
      printResult(p)
    end

这种模式很常见以致于coroutine表提供了一个wrap函数来简化之

    function perm (a)
      local n = table.getn(a)
      return coroutine.wrap(function () permgen(a, n) end)
    end
