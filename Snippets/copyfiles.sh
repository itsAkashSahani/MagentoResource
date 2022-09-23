#!/bin/bash

file=$1
dirname=$2
dirpath="/home/adminstrator/Akash/Backup/$dirname"

if [ ! -d "$dirpath" ]
then
    echo "Starting...."
    mkdir $dirpath
    while read line; do
        echo "Coping file $line"
        cp -r $line $dirpath
    done < $file
    echo "Success"
else
    echo "File exists"
fi