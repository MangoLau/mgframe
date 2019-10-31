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

#每30分钟执行的任务
if [ "0" -eq "$(($minute % 30))" ] ; then
    #php -f ${pathApp}public/index.php 'request_uri=/crontab/settlePrediction' >/dev/null 2>&1 & #比赛结束后预测结算，半个钟跑一次
fi

#每天0点执行点任务
if [ "$hour" -eq "0" ] && [ "$minute" -eq "0" ]; then
    #php -f ${pathApp}public/index.php 'request_uri=/crontab/updateRankWithPrediction' >/dev/null 2>&1 & #更新专家大神榜单
fi
