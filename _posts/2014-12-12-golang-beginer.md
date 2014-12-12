---
title: Go 语言初学者可能会不知道的事情
layout: post
---

以下语句用于查看编译器有没有bug

    fmt.Println("start")

我就是因为这个换了1.4的版本.

字符串可以用加号连接(给那些PHP党)

    fmt.Println("ab"+"cd") // "abcd"

slice是可以为空的(应当的)

    a := []int{1}
    fmt.Println(a)
    b := a[:len(a)-1]
    fmt.Println(b) // []

Split函数会产生空数组

    a:= bytes.Split([]byte("\r\n"),[]byte("\r\n"))
    fmt.Println(a) // [[],[]]

strcpy 对应的函数是 copy, 而不是你想的那样

    buf := []byte("abcdefg")
    // buf[0:1] = buf[5:6] // not even possible
    copy(buf[0:], buf[5:6])
    fmt.Println(string(buf)) // "fbcdefg"


