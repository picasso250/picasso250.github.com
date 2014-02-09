#!/bin/bash

if [[ $# -lt 1 ]]; then
    echo Usage: new name
    exit
fi

name=_posts/`date "+%Y-%m-%d"`-$1.md
echo -e "---\ntitle: $1\nlayout: post\n---\n" > $name
gedit $name &
