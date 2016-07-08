<?php if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); }  if (ROLE != 'admin') { msg('权限不足！'); }
global $m,$i;

if (isset($_GET['ok'])) {
    echo '<div class="alert alert-success">更新成功</div>';
}
doAction('admin_update_1');
if (isset($i['mode'][2])) {
	?>
<ul class="nav nav-tabs" role="tablist">
  <li><a href="index.php?mod=admin:update">自动更新</a></li>
  <li class="active"><a href="index.php?mod=admin:update:pro">高级更新</a></li>
</ul>
<br/>
<form action="ajax.php?mod=admin:update:updnow" method="post">
<div class="input-group">
  <span class="input-group-addon">下载版本</span>
  <input type="text" class="form-control" name="id" placeholder="可以为分支名、标签号或SHA-1值">
  <span class="input-group-btn"><input type="submit" class="btn btn-primary" value="更新"></span>
</div>
</form>
<br/><br/><b>如果你不了解git是什么，停止操作！请使用自动更新</b><br/><br/>
本项目的提交记录位于<a href="https://github.com/yunhuan2060/OpenShift-Tieba-Cloud-Sign/commits/" target="_blank">Github</a><br/>
警告：更新到1.4以前版本将失去此更新功能，更新到1.2以前版本将不可逆转地损坏云签到程序
<?php } else { ?>
<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a href="index.php?mod=admin:update">自动更新</a></li>
  <li><a href="index.php?mod=admin:update:pro">高级更新</a></li>
</ul>
<br/>
<div id="comsys2">
	<div class="alert alert-info"><span id="upd_info">正在检查更新......</span><br/><br/>
	<div class="progress progress-striped active">
	<div class="progress-bar progress-bar-success" id="upd_prog" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
	<span class="sr-only">正在检查更新</span></div></div></div>
</div>
<div id="comsys"></div>
<div id="comsys2"></div>
<div id="comsys3"></div>
<script type="text/javascript">
	$.ajax({
	  async:true,
	  url: 'ajax.php?mod=admin:update',
	  type: "GET",
	  data : {},
	  dataType: 'html',
	  timeout: 90000,
	  success: function(data){
	    $("#upd_prog").css({'width':'70%'});
		$("#comsys3").html(data);
	    $("#upd_info").html('完毕');
	    $("#upd_prog").css({'width':'100%'});
	    $("#comsys2").delay(1000).slideUp(500);
	 },
	  error: function(error){
	  	console.log(error);
	  	 $("#upd_info").html('检查更新失败！');
	     $("#upd_prog").css({'width':'0%'});
	     $("#comsys").html('<div class="alert alert-danger">检查更新失败：无法连接到Github<br/>可以尝试 <a href="http://www.yunhuan.tk/2016/02/08/%E7%99%BE%E5%BA%A6%E8%B4%B4%E5%90%A7%E4%BA%91%E7%AD%BE%E5%88%B0openshift%E4%B8%93%E7%89%88/" target="_blank">手动更新</a></div><br/>');
	  }
	});
</script>
<?php } doAction('admin_update_2');
