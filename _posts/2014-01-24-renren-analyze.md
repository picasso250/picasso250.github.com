---
title: 人人上的数据分析
layout: post
---

我手里有一份人人网的数据，样本量大概两万。

姓
----

{% highlight sql %}
SELECT
left(name,1) as `姓`, 
count(*) as `数量` , 
concat(truncate(count(*)/(select count(*) from renren_data.student) * 100,1),'%') as `百分比`
FROM renren_data.student group by left(name,1) order by count(*) desc
limit 20
{% endhighlight %}

<table class="table">
    <thead>
<tr>
<th>排名</th>
<th>姓</th>
<th>数量</th>
<th>百分比</th>
</tr>
    </thead>
<tr>
    <td>1</td>
<td>王</td>
<td>1955</td>
<td>8.4%</td>
</tr>

<tr>
    <td>2</td>
<td>李</td>
<td>1662</td>
<td>7.1%</td>
</tr>

<tr>
    <td>3</td>
<td>张</td>
<td>1659</td>
<td>7.1%</td>
</tr>

<tr>
    <td>4</td>
<td>刘</td>
<td>1219</td>
<td>5.2%</td>
</tr>

<tr>
    <td>5</td>
<td>陈</td>
<td>838</td>
<td>3.6%</td>
</tr>

<tr>
    <td>6</td>
<td>杨</td>
<td>636</td>
<td>2.7%</td>
</tr>

<tr>
    <td>7</td>
<td>赵</td>
<td>511</td>
<td>2.2%</td>
</tr>

<tr>
    <td>8</td>
<td>周</td>
<td>451</td>
<td>1.9%</td>
</tr>

<tr>
    <td>9</td>
<td>吴</td>
<td>418</td>
<td>1.8%</td>
</tr>

<tr>
    <td>10</td>
<td>孙</td>
<td>398</td>
<td>1.7%</td>
</tr>

<tr>
    <td>11</td>
<td>徐</td>
<td>391</td>
<td>1.6%</td>
</tr>

<tr>
    <td>12</td>
<td>黄</td>
<td>373</td>
<td>1.6%</td>
</tr>

<tr>
    <td>13</td>
<td>朱</td>
<td>294</td>
<td>1.2%</td>
</tr>

<tr>
    <td>14</td>
<td>马</td>
<td>275</td>
<td>1.1%</td>
</tr>

<tr>
    <td>15</td>
<td>郭</td>
<td>272</td>
<td>1.1%</td>
</tr>

<tr>
    <td>16</td>
<td>胡</td>
<td>270</td>
<td>1.1%</td>
</tr>

<tr>
    <td>17</td>
<td>林</td>
<td>209</td>
<td>0.9%</td>
</tr>

<tr>
    <td>18</td>
<td>高</td>
<td>208</td>
<td>0.9%</td>
</tr>

<tr>
    <td>19</td>
<td>郑</td>
<td>196</td>
<td>0.8%</td>
</tr>

<tr>
    <td>20</td>
<td>何</td>
<td>189</td>
<td>0.8%</td>
</tr>
</table>

{% highlight sql %}
SELECT 
left(uid, 1) as `uid的第一位`, 
count(*) as `数量`, 
concat(truncate(count(*)/24591*100 ,1),'%') as `百分比`
FROM renren_data.student
group by left(uid, 1)
order by count(*) desc
limit 10
{% endhighlight %}


<table class="table">
<tr>
<th>uid的第一位</th>
<th>数量</th>
<th>百分比</th>
</tr>

<tr>
<td>2</td>
<td>16203</td>
<td>65.8%</td>
</tr>

<tr>
<td>3</td>
<td>2948</td>
<td>11.9%</td>
</tr>

<tr>
<td>1</td>
<td>2452</td>
<td>9.9%</td>
</tr>

<tr>
<td>4</td>
<td>1270</td>
<td>5.1%</td>
</tr>

<tr>
<td>5</td>
<td>781</td>
<td>3.1%</td>
</tr>

<tr>
<td>7</td>
<td>733</td>
<td>2.9%</td>
</tr>

<tr>
<td>6</td>
<td>674</td>
<td>2.7%</td>
</tr>

<tr>
<td>8</td>
<td>512</td>
<td>2.0%</td>
</tr>

<tr>
<td>9</td>
<td>450</td>
<td>1.8%</td>
</tr>
</table>

看来不符合那个什么定理啊。
