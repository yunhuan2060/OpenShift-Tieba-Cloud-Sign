# Tieba-Cloud-Sign on OpenShift #

Automatic check-ins in Baidu Tieba


## about Tieba-Cloud-Sign ##

Products center：http://s.stus8.com/

Plug-Mall: Temporarily No

All Tutorials：http://www.stus8.com/category/3/


## Manual Installation ##

Create a php-5.4 application (you can call your application whatever you want)

    rhc app create qiandao php-5.4 mysql-5.5 cron --from-code=https://github.com/vvb2060/OpenShift-Tieba-Cloud-Sign.git

That's it, you can now checkout your application at:

    https://qiandao-$yournamespace.rhcloud.com

You'll be prompted to set an admin password and install your Tieba-Cloud-Sign site the first time you visit this
page.
