---
title: C语言如果没有Struct
layout: post
---

如果C语言没有Struct会怎么样？当然不会怎样。Struct只是语法糖，让我们写起来方便而已。

在C4中，就没有Struct。

举个例子：

  struct student {
  	char * name;
  	int age;
  };
  int main(int argc, char *argv[])
  {
  	struct student s;
  	s.name = "John";
  	s.age = 18;
  	printf("student name %s, age %d.\n", s.name, s.age);
  	return 0;
  }

这里的 struct student 就是语法糖，你完全可以这么写：

  int main(int argc, char *argv[])
  {
    char *student_name = "John";
  	int student_age = 18;
  	printf("student name %s, age %d.\n", student_name, student_age);
  	return 0;
  }

当然，这样挺麻烦的。我们可以这样：

  enum {Name, Age};
  int main(int argc, char *argv[])
  {
  	int * s;
  	s = malloc(sizeof(int)*2);
  	s[Name] = (int)"John";
  	s[Age] = 18;
  	printf("student name %s, age %d.\n", s[Name], s[Age]);
  	free(s);
  	return 0;
  }

这样，我们也获取到了甜甜的滋味（写起来比较方便，看起来比较简单）。

当然，这里有一个前提就是 `sizeof(int) == sizeof(char*)`

	printf("sizeof(char*)=%d, sizeof(int)=%d\n", sizeof(char*), sizeof(int));

你可以在你的机器上试试。

如果是在64位的机器上，就没那么简单了，struct要做对齐的。我们假定 `sizeof(char*)=8` 而 `sizeof(int)=4`

	char **s;
	s = malloc(sizeof(char**)*2);
	s[Name] = "John";
	s[Age] = (char*)18;
	printf("student name %s, age %d.\n", s[Name], (int)s[Age]);
	free(s);
	return 0;
