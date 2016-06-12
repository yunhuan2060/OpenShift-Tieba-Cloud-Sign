#!/bin/bash
export TZ='Asia/Shanghai'
curl -I ${OPENSHIFT_APP_DNS} 2> /dev/null | head -1 | grep -q '200\|301\|302'
s=$?
if [ $s != 0 ];
	then
		echo "`date +"%Y-%m-%d %H:%M:%S"` down" >> ${OPENSHIFT_LOG_DIR}web_error.log
		echo "`date +"%Y-%m-%d %H:%M:%S"` restarting..." >> ${OPENSHIFT_LOG_DIR}web_error.log
		/usr/bin/gear stop 2>&1 /dev/null
		/usr/bin/gear start 2>&1 /dev/null
		echo "`date +"%Y-%m-%d %H:%M:%S"` restarted!!!" >> ${OPENSHIFT_LOG_DIR}web_error.log		
else
	echo "`date +"%Y-%m-%d %H:%M:%S"` is ok" > ${OPENSHIFT_LOG_DIR}web_run.log
fi
