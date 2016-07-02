<?php
define('SYSTEM_FN','百度贴吧云签到');
define('SYSTEM_VER','4.0');
define('SYSTEM_ROOT2',dirname(__FILE__));
define('SYSTEM_ROOT',dirname(__FILE__).'/..');
define('SYSTEM_PAGE',isset($_REQUEST['mod']) ? strip_tags($_REQUEST['mod']) : 'default');
header("content-type:text/html; charset=utf-8");
require SYSTEM_ROOT.'/lib/msg.php';
include SYSTEM_ROOT.'/lib/class.wcurl.php';

if (file_exists(SYSTEM_ROOT.'/config.php') xor $_GET['step']=='4') {
    msg('错误：config.php文件已存在，不需要安装<br/><br/>警告：删除config.php文件后仅可<font color="red">全新</font>安装', '../');
}

	echo '<!DOCTYPE html><html><head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0">';
	echo '<link href="../favicon.ico" rel="shortcut icon"/>';
	echo '<title>安装向导 - '.SYSTEM_FN.'</title></head><body>';
	echo '<script src="../source/js/jquery.min.js"></script>';
	echo '<link rel="stylesheet" href="../source/css/bootstrap.min.css">';
	echo '<script src="../source/js/bootstrap.min.js"></script>';
	echo '<style type="text/css">body { font-family:"微软雅黑","Microsoft YaHei";background: #eee; }</style>';
?>
<div class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">贴吧云签到</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="install.php">贴吧云签到安装（OpenShift专版）</a>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
          <li><a href="http://moeclub.net/" target="_blank">StusGame GROUP版权所有</a></li>
		  <li><a href="http://www.yunhuan.tk" target="_blank">云幻修改</a></li>
    </ul>
  </div><!-- /.navbar-collapse -->
</div>
<div style="width:90%;margin: 0 auto;overflow: hidden;position: relative;">
<?php
	if (!isset($_GET['step']) || $_GET['step'] == 0) {
		header('Location: install.php?step=1');
	} else {
		switch (strip_tags($_GET['step'])) {
			case '1':
				echo '<h2>准备安装: 功能检查</h2><br/>';
				echo '<div class="progress progress-striped">
			  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
			    <span class="sr-only">10%</span>
			  </div>
			</div>';
		define('DO_NOT_LOAD_UI', TRUE);
		include SYSTEM_ROOT2.'/check.php';
		echo '<input type="button" onclick="location = \'install.php?step=2\'" class="btn btn-success" value="下一步 >>">';
				break;
				case '2':
				echo '<h2>设置所需信息</h2><br/>';
				echo '<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
    <span class="sr-only">30%</span>
  </div>
</div>';
				echo '<h4>站点创始人信息</h4><br/>';
				echo '<form action="install.php?step=3" method="post">';
				echo '<div class="input-group"><span class="input-group-addon">创始人用户名</span><input type="text" required class="form-control" name="user" placeholder=""></div><br/>';
				echo '<div class="input-group"><span class="input-group-addon">创始人邮箱</span><input type="email" required class="form-control" name="mail" placeholder=""></div><br/>';
				echo '<div class="input-group"><span class="input-group-addon">创始人密码</span><input type="password" required class="form-control" name="pw" placeholder=""></div><br/>';
				echo '<div class="input-group"><span class="input-group-addon">文件管理密码</span><input type="password" required class="form-control" name="kodpw" placeholder=""></div><br/>';
				echo '<br/><br/><input type="submit" class="btn btn-success" value="下一步 >>"></form>';
				break;

			case '3':
				if (isset($_SERVER['HTTPS']) == 'on') {
					$http = 'https://';
				} else {
					$http = 'http://';
				}
				if (isset($_POST['isbae'])) {
					$isapp = '1';
				} else {
					$isapp = '0';
				}
				preg_match("/^.*\//", $_SERVER['SCRIPT_NAME'], $sysurl);
				define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST') . ':' . getenv('OPENSHIFT_MYSQL_DB_PORT'));
				define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
				define('DB_PASSWD', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
				define('DB_NAME', getenv('OPENSHIFT_APP_NAME'));
				define('DB_PREFIX','yh_');
				define('USERPW_SALT',base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM)));
				function EncryptPwd($pwd) {$s=base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM));return md5($pwd.USERPW_SALT.$s).$s;}
				$sql  = str_ireplace('{VAR-PREFIX}', DB_PREFIX, file_get_contents(SYSTEM_ROOT2.'/install.template.sql'));
				$sql  = str_ireplace('{VAR-DB}', DB_NAME, $sql);
				$sql  = str_ireplace('{VAR-ISAPP}', $isapp, $sql);
				$sql  = str_ireplace('{VAR-SYSTEM-URL}', $http . $_SERVER['HTTP_HOST'] . str_ireplace('setup/', '', $sysurl[0]), $sql);
				$sql .= "\n"."INSERT INTO `".DB_NAME."`.`".DB_PREFIX."users` (`name`, `pw`, `email`, `role`) VALUES ('{$_POST['user']}', '".EncryptPwd($_POST['pw'])."', '{$_POST['mail']}', 'admin');";
				require SYSTEM_ROOT.'/lib/mysql_autoload.php';
				global $m;
				$m->multi_query($sql);
				$write_data = '<?php if (!defined(\'SYSTEM_ROOT\')) { die(\'Insufficient Permissions\'); }
/*
|--------------------------------------------------------------------------
| 贴吧云签到基础配置文件 - 不要修改！不要修改！不要修改！
|--------------------------------------------------------------------------
|
| 根据系统变量自动设置，切勿修改！
| 带SALT的为盐值，其中USERPW_SALT一旦修改，所有用户无法登陆！
|
*/
define(\'DB_HOST\',\''.DB_HOST.'\');
define(\'DB_USER\',\''.DB_USER.'\');
define(\'DB_PASSWD\',\''.DB_PASSWD.'\');
define(\'DB_NAME\',\''.DB_NAME.'\');
define(\'DB_PREFIX\',\''.DB_PREFIX.'\');
define(\'USERPW_SALT\',\''.USERPW_SALT.'\');
define(\'SYSTEM_SALT\',\''.base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM)).'\');';
				$kod_data = '<?php exit;?>{"admin":{"name":"admin","password":"'.md5($_POST['kodpw']).'","role":"root","status":0}}';
				file_put_contents(SYSTEM_ROOT.'/config.php', $write_data);
				file_put_contents(SYSTEM_ROOT.'/plugins/KODExplorer/data/system/member.php',$kod_data);
				echo '<script src="stat.js?type=tcs&ver='.SYSTEM_VER.'"></script>';
				echo '<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
    <span class="sr-only">60%</span>
  </div>
</div>';
				echo '<meta http-equiv="refresh" content="0;url=install.php?step=4"><h2>请稍候</h2><br/>正在完成安装...';
				
				break;

			case '4':
				echo '<h2>安装完成</h2><br/>';
				echo '<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
    <span class="sr-only">90%</span>
  </div>
</div>';
				echo '恭喜你，安装完成<br/><br/>请确认添加了Cron 1.4，会自动执行计划任务，否则，签到不会自动进行。<br/><br/><input type="button" onclick="location = \'../index.php\'" class="btn btn-success" value="进入我的云签到 >>">';
				break;

			default:
				msg('未定义操作');
				break;
		}
	}
?>
</div>