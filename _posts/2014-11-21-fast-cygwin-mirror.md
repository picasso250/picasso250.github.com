---
title: Fast Cygwin Mirror
layout: post
---

cygwin is slow, use this script

<% highlight php %>
foreach (file('http://cygwin.com/mirrors.lst') as $line) {
    echo $line;
    if (preg_match('%^\w+://([\w.-]+)/%', $line, $matches)) {
        $host = $matches[1];
        if (!isset($table[$host])) {
            $output = shell_exec("ping $matches[1]");
            echo $output;
            if (preg_match('/(\d+)ms/', $output, $matches)) {
                $table[$host] = $matches[1];
            }
        }
    }
}
asort($table);
print_r($table);
<% endhighlight %>
