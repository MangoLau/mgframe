#!/bin/bash
#<?php die(); ?>

umask 002
#切换到当前目录
readonly pathApp=$(cd "$(dirname "$0")/.."; pwd)/
readonly pathPublic=$(cd "$pathApp/public"; pwd)/
readonly minute=$((10#$(date +%M)))
readonly hour=$(date +%k)
readonly day=$((10#$(date +%d)))
readonly month=$(date +%m)
readonly weekday=$(date +%w)
readonly Ymd=$(date +%Y%m%d)
cd $pathApp

echo "${Ymd},${hour}:${minute}" >> ${pathApp}logs/cron.txt

php -f ${pathPublic}/index.php 'request_uri=/async/daemon/p/1' >/dev/null 2>&1 &	#异步处理

#每30分钟执行的任务
if [ "0" -eq "$(($minute % 30))" ] ; then
fi

#每天0点执行点任务
if [ "$hour" -eq "0" ] && [ "$minute" -eq "0" ]; then
fi
