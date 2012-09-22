<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>companyCMS 企业网站管理中心</title>
<link id="mastercss" rel="stylesheet" href="__PUBLIC__/Admin/style/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="__PUBLIC__/Admin/js/colorpicker/colorpicker.css" type="text/css">
<link type="image/x-icon" href="__PUBLIC__/Images/company.ico" rel="shortcut icon">
<script type="text/javascript">
<!--
//指定当前组模块URL地址
var URL = '__URL__';
var APP	 =	 '__APP__';
var PUBLIC = '__PUBLIC__';
var ROOT = '__ROOT__';
//-->
</script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/Jquery/jquery.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/Jquery/jquery.validate.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Admin/js/script_common.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Admin/js/colorpicker/colorpicker.js"/></script>

</head>
<body>
<div id='loader' style='color:#ffffff;font-size:12px;background-color: #0099CC; width:140px; padding:2px 4px; height:20px; position: fixed;right:0px;top:2px; display:none'>提交中，请稍后...</div>
	<div id="wrap">
		<div id="header">
			<h2><a href="__APP__" title="companyCMS"><img src="__PUBLIC__/Admin/images/logo.gif" alt="companyCMS" /></a></h2>
			
			<div id="topmenu" class="gray">
			<span style="font-weight:bold">当前用户：<img src="__PUBLIC__/Admin/images/user.gif" alt="companyCMS" align="absmiddle"/><?php echo ($username); ?></span> 　 
				<a href="<?php echo U('Admin/modify',array('id'=>$adminId, 'jumpUri'=>'run' ));?>"><img src="__PUBLIC__/Admin/images/user_modify.gif" alt="companyCMS" align="absmiddle"/>我的帐户</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo U('Public/logout');?>"><img src="__PUBLIC__/Admin/images/logout.gif" alt="companyCMS" align="absmiddle"/>退出系统</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo ($frontUrl); ?>" target="_blank"><img src="__PUBLIC__/Admin/images/home_25.gif" alt="companyCMS" align="absmiddle"/>前台首页</a>
			</div>
			<ul id="menu" style="display:none" >
				<li><a href="Admin.php">管理平台</a></li>
				<li><a href="Admin.php?ac=$value"><?php echo ($_TPL[menunames][$value]); ?></a></li>
			</ul><div id="later" style="position:fixed"></div>
		</div>
		<div id="content">

<div class="mainarea">
<div class="maininner">
<div class="body_content">
<div class="title">
<h3>管理备忘录(最多2000字)</h3>
</div>
<div><textarea name="notepad" id="notepad"
	style="width: 500px; height: 100px"><?php echo ($notepad["notepad"]); ?></textarea>
<div><a href="javascript:updateNotepad()" class="text_bg">更新备忘录</a></div>
<div id="msg"></div>
</div>
<br />
<div class="title">
<h3>版本信息</h3>
</div>
<div>当前系统版本：<?php echo ($sys_version); ?></div>
<br />

<div class="title">
<h3>系统信息</h3>
</div>
<ul class="listcol list2col">
	<li>主机域名: <?php echo ($serverUri); ?></li>
	<li>操作系统: <?php echo ($serverSoft); ?></li>
	<li>数据库版本: <?php echo ($mysqlVersion); ?></li>
	<li>PHP版本: <?php echo ($phpVersion); ?></li>
	<li>全局变量: <?php echo ($phpGpc); ?></li>
	<li>上传许可: <?php echo ($phpUploadSize); ?></li>
	<li>最大执行时间: <?php echo ($maxExcuteTime); ?></li>
	<li>最大使用内存: <?php echo ($maxExcuteMemory); ?></li>
	<li>当前使用内存: <?php echo ($excuteUseMemory); ?></li>
</ul>
<br />
<div class="title">
<h3>藤陟网络软件有限公司</h3>
</div>
<table id="maintable" class="formtable">
	<tr>
		<td style="width: 100px">版权所有</td>
		<td>藤陟网络软件有限公司</td>
	</tr>
	<tr>
		<td>程序开发</td>
		<td>tianren</td>
	</tr>
	<tr>
		<td>模板设计</td>
		<td>YXH</td>
	</tr>
	<tr>
		<th colspan="2">联系方式</th>
	</tr>
	<tr>
		<td>MAIL</td>
		<td>ayay-990@163.com</td>
	</tr>
	<tr>
		<td>QQ</td>
		<td>396774497</td>
	</tr>
	<tr>
		<td>产品网站</td>
		<td><a href="http://www.tengzhiinfo.cn" target="_blank">http://www.tengzhiinfo.cn</a></td>
	</tr>
</table>


</div>

</div>
</div>

<div class="side">	<div class="block style1">
		<h2>常规设置</h2>
		<ul class="folder">
        <li class="Index"><a href="<?php echo U("Index/index");?>">后台首页</a></li>
		<li class="Config"><a href="<?php echo U("Config/index");?>">系统配置</a></li>
        <li class="Module"><a href="<?php echo U("Module/index");?>">系统模块</a></li>
        <li class="Theme"><a href="<?php echo U("Theme/index");?>">风格模板</a></li>
        <li class="Admin"><a href="<?php echo U("Admin/index");?>">管理员管理</a></li>
        <li class="AdminRole"><a href="<?php echo U("AdminRole/index");?>">角色管理</a></li>
		</ul>
	</div>

	<div class="block style1">
		<h2>模块管理</h2>
		<ul class="folder" style="overflow-y:auto;height:280px;">
            <?php if(is_array($modualSider)): $i = 0; $__LIST__ = $modualSider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lb): $mod = ($i % 2 );++$i;?><li class="<?php echo ($lb['module_name']); ?>"><a href='<?php echo U($lb['module_name']."/index");?>'><?php echo ($lb["module_title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
	

	<div class="block style1">
		<h2>高级应用</h2>
		<ul class="folder">
        <li class="Tools"><a href="<?php echo U("Tools/index");?>">工具箱</a></li>
        <li class="AdminLog"><a href="<?php echo U("AdminLog/index");?>">操作日志</a></li>
        <!--  <li class="Label"><a href="<?php echo U("Label/index");?>">数据调用</a></li>
		<li class="Database"><a href="<?php echo U("Database/index");?>">数据库管理</a></li>
        <li><a href="http://www.tengzhiinfo.com" target="_blank">帮助中心</a></li>-->
		</ul>
	</div>
</div>
</div>
<div id="footer">
	<p>Powered by 藤陟网络软件有限公司  Copyright 2011-2013 藤陟网络软件有限公司
</p>
</div>
</div>
<script type="text/javascript">
$(function(){ 
    var moduleName = "<?php echo (MODULE_NAME); ?>";
    $("." + moduleName).addClass("active");
    $(".confirmSubmit").click(function() {
        return confirm('本操作不可恢复，确定继续？');
    });
}); 
</script>
</body>
</html>


<script language="javascript"> 
<!-- 
function killerrors() { 
	return true; 
} 
window.onerror = killerrors; 

function updateNotepad()
{
   $.ajax({   
		  type:"POST",   
			  url:"<?php echo U('Index/updateNotepad');?>",
			  data:{
				  notepad: $('#notepad').val()
				  },   
			  beforeSend:function(){
				  	$("#msg").html('<span style="color:#FF0000"><img src="__PUBLIC__/Admin/images/loading.gif" align="absmiddle">正在提交数据...</span>'); 
				  },                
			  success:function(data){
				switch(data)
				{
					case 'ok':
						$("#msg").html('<span style="color:#FF0000">更新完成</span>'); 
						break
					default:
						$("#msg").html('<span style="color:#FF0000">'+data+'</span>');
				}
				return false;			
			}               
      });   
}

//--> 
</script>