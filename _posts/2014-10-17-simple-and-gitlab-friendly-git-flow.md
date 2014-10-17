---
title: 小团队的 Git Flow
layout: post
---

网络上疯传的 Git Flow 是
[A successful Git branching model](http://nvie.com/posts/a-successful-git-branching-model/)
。

但如果你的团队不超过5个人，用这个 Git Flow 就有点高射炮打蚊子的味道了。

我思考很久，得出了一个简单的，适合小团队的，gitlab 友好的 Git Flow。

![simple git flow](http://ww3.sinaimg.cn/bmiddle/632bf9c4gw1ele6se30drj208q0gxq3c.jpg)

## 开发

开发有三种：修复bug，新的功能（满足需求），重构。

不论是种开发，每次开发都对应一个 issue。

每个人从当前的 `master` 分支分出自己的 issue 分支，如
`iss-3`
然后进行开发。

## 代码审查

当开发自测完毕之后，提出merge request。gitlab有个功能，当你push一个分支上去的时候，它会在project的首页有个按钮，
![create merge request](http://ww1.sinaimg.cn/bmiddle/632bf9c4gw1ele6shgyngj204g012jr7.jpg)
。点击这个按钮，就会跳到创建merge request 的页面。这个merge request是从刚push上去的分支到master的。

于是，你的同事可以进行代码审查。

## 测试

现在，你可以招呼QA的同事进行测试了。你在刚刚 merge 的 `master` 分支上，创建一个tag，比如
`v1.2.0`
。测试的同学就拉这个tag进行测试。如果测试通过，那么一切好说。如果不通过，就打回重做。开发继续在 issue 分支上开发，解决问题之后，合并到 `master` 分支。此次再打 tag，就是
`v1.2.1`
了。直到问题解决，可以发布为止。

## 发布

发布就是 pull 一个 tag。

## 其他问题

这套方案的优势是简单。可是如果万一出了问题，也是有相应的手段的。

1. 合并所产生的bug
  
  假设 featureA 和 featureB 都已经开发完毕，即将发布。在合并到 `master` 上时，发生了冲突。此时我们直接在`master` 上解决冲突。
  
  再假设事情不凑巧。解决冲突的时候，产生了bug，需要修复。因为问题不是featureA或者featureB单独产生的，或者界定问题产生的feature比较困难，不能回 featureA 或者 featureB 上修复。此时，我们需要做的就是在master上分裂出一个release分支。解决此次的问题。待问题解决，合并回master即可。
 
2. 发布受阻。

   假设 featureA 和 featureB 都已经开发完毕。合并至master后，打上tag `v1.2.2`，交给QA测试。但在测试的过程中，发现有 featureA 重大缺陷，不能发布，准备只发布 featureB，此时，可以将 `master` 分支 reset 回上一次发布的tag `v1.2.1`，然后将featureB单独合并至 `master` 发布。
