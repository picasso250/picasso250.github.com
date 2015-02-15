---
title: Fast Cygwin Mirror
layout: post
---

If you find slow when install Cygwin packages, use this script to select a fast mirror site.

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

---

Related

- [如何使用 Windows 和 Linux 工作](/2012/11/02/how-to-work-with-windows-and-linux.html)
