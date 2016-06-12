<?php
/**
 * 贴吧云签到
 * Copyright (c) 2012~2016 StusGame All Rights Reserved.
 * 
 * 获取开发文档：https://git.oschina.net/kenvix/Tieba-Cloud-Sign/wikis/
 */
/**
 * 加载核心
 * HELLO GAY!
 */
define('SYSTEM_FN','百度贴吧云签到');
define('SYSTEM_VER','4.5');
define('SYSTEM_VER_NOTE','X');
define('SYSTEM_ROOT',dirname(__FILE__));
define('PLUGIN_ROOT',dirname(__FILE__) . '/plugins/');
define('SYSTEM_ISCONSOLE' , (isset($argv) ? true : false));
define('SYSTEM_PAGE',isset($_REQUEST['mod']) ? strip_tags($_REQUEST['mod']) : 'default');
define('SUPPORT_URL', 'http://s.stus8.com/tcs/');
if(SYSTEM_ISCONSOLE)  {
    function console_htmltag_delete($v) {
        $v = str_ireplace(array('</td>','</th>') , ' | ', $v);
        $v = str_ireplace(array('<br/>','</p>','</tr>','</thead>','</tbody>') , PHP_EOL, $v);
        $v = str_ireplace(array('&nbsp;') , ' ', $v);
        return SYSTEM_FN . ' Ver.' . SYSTEM_VER . ' ' . SYSTEM_VER_NOTE . ' - 控制台模式' . PHP_EOL . '==========================================================' . PHP_EOL . strip_tags($v);
    }
    ob_start('console_htmltag_delete');
}
require SYSTEM_ROOT.'/lib/msg.php';
//如需停止站点运行，请解除注释，即删除开头的 //
//msg('站点已关闭！请稍后再试，如有疑问请联系站长解决！');
if (!file_exists(SYSTEM_ROOT.'/config.php')) {
	msg('<h2>检测到无配置文件</h2><font size="4">您尚未安装本程序，请<a href="./setup/install.php">前往安装</a></font>',false,true,false);	
}
header("content-type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
if(file_exists(SYSTEM_ROOT . '/key.php')) {
    include SYSTEM_ROOT . '/key.php';
}
require SYSTEM_ROOT.'/lib/class.E.php';
require SYSTEM_ROOT.'/lib/class.P.php';
require SYSTEM_ROOT.'/config.php';
require SYSTEM_ROOT.'/lib/mysql_autoload.php';
require SYSTEM_ROOT.'/lib/class.former.php';
require SYSTEM_ROOT.'/lib/class.smtp.php';
require SYSTEM_ROOT.'/lib/class.zip.php';
require SYSTEM_ROOT.'/lib/class.phpmailer.php';
require SYSTEM_ROOT.'/lib/reg.php';
define('SYSTEM_URL',option::get('system_url'));
define('SYSTEM_NAME', option::get('system_name'));
//版本修订号
define('SYSTEM_REV',option::get('core_revision'));
//压缩包链接
define('UPDATE_SERVER_OSCGIT','https://git.oschina.net/kenvix/Tieba-Cloud-Sign/repository/archive?ref=master');
define('UPDATE_SERVER_GITHUB','https://github.com/kenvix/Tieba-Cloud-Sign/archive/master.zip');
define('UPDATE_SERVER_CODING','https://coding.net/u/kenvix/p/Tieba-Cloud-Sign/git/archive/master');
define('UPDATE_SERVER_GITCAFE','https://gitcafe.com/kenvix/Tieba-Cloud-Sign/archiveball/master/zip');
//压缩包内文件夹名
define('UPDATE_FNAME_OSCGIT','Tieba-Cloud-Sign');
define('UPDATE_FNAME_GITHUB','Tieba-Cloud-Sign-master');
define('UPDATE_FNAME_CODING','');
define('UPDATE_FNAME_GITCAFE','Tieba-Cloud-Sign');
//压缩包解压路径
define('UPDATE_CACHE',SYSTEM_ROOT.'/setup/update_cache/');
require SYSTEM_ROOT.'/lib/sfc.functions.php';
require SYSTEM_ROOT.'/lib/ui.php';
if (!defined('SYSTEM_NO_PLUGIN')) require SYSTEM_ROOT.'/lib/plugins.php';
if (!defined('SYSTEM_NO_CHECK_LOGIN')) require SYSTEM_ROOT.'/lib/globals.php';
