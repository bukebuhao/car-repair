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
<script type="text/javascript">
    //获取选择ID
    function getItem() {
        var selectRow = Array();
        var obj = document.getElementsByName('dataArray');
        var result = '';
        var j = 0;
        for (var i = 0; i < obj.length; i++) {
            if (obj[i].checked == true) {
                selectRow[j] = i + 1;
                result += obj[i].value + ",";
                j++;
            }
        }
        return result.substring(0, result.length - 1);
    }

    function doCache() {
        var itemValue = getItem();
        if (itemValue == '') {
            alert('没有选择需要更新的缓存类型');
            return false;
        }
        $.ajax({
            type: "POST",
            url: '<?php echo U("Tools/doCache");?>',
            data: {
                dataArr: itemValue
            },
            beforeSend: function() {
                $("#cache").html('<span style="color:#FF0000"><img src="__PUBLIC__/Admin/images/loading.gif" align="absmiddle">正在执行...</span>');
            },
            success: function(data) {
                switch (data) {
                case 'errorVerifyCode':
                    break
                default:
                    $("#cache").html(data);
                    $('#cache').show();
                    //alert ('未知错误，请联系管理员');
                }

            }
        });
    }
</script>
<div class="mainarea">
<div class="maininner">
	
	<div class="body_content">
	
	<div class="title">
		<h3>更新缓存</h3>
	</div>

	<form action="<?php echo U('Tools/cache');?>" method="post" id="updateCache">
<table id="maintable" class="formtable">
	<tr>
		<th style="width:5em;"></th>
		<td><span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="config" value="config" class="checkbox" />系统配置</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="role" value="role" class="checkbox" />角色</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="category" value="category" class="checkbox" />分类</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="s" value="menu" class="checkbox" />导航</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="link" value="link" class="checkbox" />链接</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="tags" value="tags" class="checkbox" />Tags</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="ad" value="ad" class="checkbox" />广告</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="module" value="module" class="checkbox" />模块</span>
        <span style="float:left; width:25%;"><input name="dataArray" type="checkbox" id="module" value="core" class="checkbox" />核心</span>
        </td>
	</tr>
	<tr>
	  <th>&nbsp;</th>
	  <td><input type="checkbox" id="chkall" name="chkall" onclick="checkAll(this.form, 'dataArray')">全选</td>
	  </tr>
	<tr>
		<th>&nbsp;</th>
		<th>
			<input type="button" name="submit" value="提交更新" class="submit" id="submit" onclick="doCache()">
        <input type="reset" name="button" id="button" value="还原重选" class="submit"/>
		</th>
	</tr>
	<tr>
	  <th>&nbsp;</th>
	  <td><div id="cache"></div></td>
	  </tr>
	
	</table>
</form>
    
<br />
	<div class="title" id="register">
			<h3>重新统计Tags计数</h3>
	</div>
	<form action="<?php echo U('Tools/countTags');?>" method="get" id="updateCache">
<table id="maintable" class="formtable">
	
	<tr>
	  <th style="width:5em;">&nbsp;</th>
	  <td><select name="module" id="module">
	    <option value="News" selected="selected">新闻</option>
	    <option value="Product">产品</option>
	    <option value="Job">人才</option>
	  </select></td>
	  </tr>
	<tr>
	  <th style="width:5em;">&nbsp;</th>
	  <td><label>
	      <input name="pageSize" type="text" id="pageSize" value="50" size="6" />
	      每次更新数量，Tags数量非常之时越小越好，防止程序执行时间超时</label></td>
	  </tr>
	<tr>
	  <th style="width:5em;">&nbsp;</th>
	  <td>此功能能将重新统计Tags数量</td>
	  </tr>
	<tr>
	  <th>&nbsp;</th>
	  <th>
	    <input type="submit" name="submit" value="提交更新" class="submit" id="submit">
	    </th>
	  </tr>
	
	</table>
</form>
	<br />
	
	</div>
</div>
</div>

<div class="side">
		<div class="block style1">
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