---
title: 算法导论答案
layout: post
---

**2.3-1**

    |     3  9  26  38  41  49  52  57    |
    |    3 26 41 52    |    9  38 49 57   |
    |  3 41  |  26 52  |  38 57  |  9  49 |
    | 3 | 41 | 52 | 26 | 38 | 57 | 9 | 49 |

**2.3-2**

    MERGE(A, p, q, r)
    1  n_1 = q - p + 1
    2  n_2 = r - q
    3  let L[1..n_1] and R[1..n_2] be new arrays
    4  for i = 1 to n_1
    5      L[i] = A[p + i - 1]
    6  for j = 1 to n_2
    7      R[j] = A[q + j]
    8  i = 1
    9  j = 1
    10 for k = p to r
    11     if i > n_1
    12         A[k] = R[j]
    13         j = j + 1
    14     elseif j > n_2
    15         A[k] = L[i]
    16         i = i + 1
    17     elseif L[i] <= R[j]
    18         A[k] = L[i]
    19         i = i + 1
    20     else
    21         A[k] = R[j]
    22         j = j + 1

