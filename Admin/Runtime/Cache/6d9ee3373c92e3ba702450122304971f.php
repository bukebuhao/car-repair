<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    $(document).ready(function() {
        $("#mainFrom").validate({
            rules: {
                title: "required",
                content: "fckeditor",
                sale_price: {
                    required: true,
                    number: true
                },
                market_price: {
                    required: true,
                    number: true
                },
                shop_price: {
                    required: true,
                    number: true
                },
                view_count: {
                    required: true,
                    number: true
                },
                display_order: {
                    required: true,
                    number: true
                }
            },
            messages: {
                title: "产品名称必须填写",
                content: "详细描述必须填写",
                sale_price: {
                    required: "销售价必须填写",
                    number: '销售价必须为数字'
                },
                market_price: {
                    required: "市场价必须填写",
                    number: '市场价必须为数字'
                },
                shop_price: {
                    required: "商场价必须填写",
                    number: '市场价必须为数字'
                },
                view_count: {
                    required: "浏览数必须填写",
                    number: '浏览数必须为数字'
                },
                display_order: {
                    required: "排序必须填写",
                    number: '排序必须为数字'
                }
            }
        });
        colorPicker();
    });
</script>
<div class="mainarea">
<div class="maininner">
<form method="post" action="<?php echo U("Product/doModify");?>" enctype="multipart/form-data" id="mainFrom">
	<div class="body_content">
		<div class="top_action"><a href="__URL__">返回列表</a> | <a href="<?php echo U("Product/insert");?>">录入产品</a> | <a href="<?php echo U("Category/index");?>">类别管理</a></div>
		<table cellspacing="0" cellpadding="0" id="maintable" class="formtable">
		<tr>
		  <th style="width:8em;"><label for="title">产品名称</label></th>
		<td><input name="title" id="title" value="<?php echo ($vo["title"]); ?>" size="40"></td></tr>
 <tr>
		  <th style="width:8em;">标题样式</th>
		<td><div style=" display:block; float:left" title="点击取颜色" class="color_picker" id="color_picker" onclick="colorPicker()">&nbsp;</div>颜色<input class="input" type="hidden" value="<?php echo (styleselected($vo["title_style_serialize"],'color')); ?>" name="style_color" id="style_color" size="10"/>&nbsp;
             <input name="style_bold" type="checkbox" id="style_bold" value="bold" <?php echo (styleselected($vo["title_style_serialize"],'bold')); ?>/>
             加粗<input name="style_underline" type="checkbox" id="style_underline" value="underline" <?php echo (styleselected($vo["title_style_serialize"],'underline')); ?>/>
           下划线</td></tr>
        <tr>
          <th style="width:8em;"><label for="category_id">类别名称</label></th>
          <td><select name="category_id"  id="select"  >
             <?php echo (buildselect($category,$parentId,$vo['category_id'])); ?>
            
                </select></td></tr>
        
          <tr>
		  <th style="width:8em;"><label for="standard">产品规格</label></th>
		<td><input name="standard" id="standard" value="<?php echo ($vo["standard"]); ?>">
		 <span style=" font-weight:bold"><label for="number">产品型号</label></span>
		  <input name="number" id="number" value="<?php echo ($vo["number"]); ?>" />
		  </td></tr>
		
          <th style="width:8em;"><label for="sale_price">销 售 价</label></th>
  <td><input name="sale_price" id="sale_price" value="<?php echo (($vo["sale_price"])?($vo["sale_price"]):0); ?>" size="10">
    <span style=" font-weight:bold"><label for="market_price">市 场 价</label></span>
      <input name="market_price" id="market_price" value="<?php echo (($vo["market_price"])?($vo["market_price"]):0); ?>" size="10" />
      <span style=" font-weight:bold"><label for="shop_price">商 场 价</label></span>
      <input name="shop_price" id="shop_price" value="<?php echo (($vo["shop_price"])?($vo["shop_price"]):0); ?>" size="10" />
      只能填写数字(单位:元)</td></tr>
        
        
        <tr>
          <th style="width:8em;"><label for="attach_file">产品图片</label></th>
          <td> <input name="attach_file" type="file" id="attach_file" /><?php if(($vo['attach']) == "1"): ?>不更换请不要选择新图片　<a href="__UPLOAD__/<?php echo ($vo["attach_image"]); ?>" target="_blank"><img src="__PUBLIC__/Admin/images/image.gif" border="0" align="absmiddle" /></a><?php endif; ?></td></tr>
         <tr>
		  <th style="width:8em;"><label for="description">简单描述</label></th>
		<td><textarea name="description" cols="60" rows="5"  id="description"><?php echo ($vo["description"]); ?></textarea></td></tr>
        <tr>
		  <th style="width:8em;"><label for="content">详细描述</label></th>
		<td> </td></tr>
        
        <tr>
		  <th colspan="2" >
		   <textarea name="content" cols="50" rows="4" id="content"><?php echo ($vo["content"]); ?></textarea>
<script src="__PUBLIC__/Admin/js/FCKeditor/fckeditor.js"></script>
<script>
	var oFCKeditor = new FCKeditor('content') ;
	oFCKeditor.BasePath = '__PUBLIC__/Admin/js/FCKeditor/';
	oFCKeditor.Width = '100%';
	oFCKeditor.Height = '400';
	oFCKeditor.ToolbarSet = 'Default';
	oFCKeditor.ReplaceTextarea();
</script></th>
		</tr>
        
         <tr>
		  <th colspan="2" style="width:8em;">以下为选填内容</th>
		</tr>
<tr>
           <th style="width:8em;"><label for="template">模　　板</label></th>
           <td><input name="template" id="template" value="<?php echo ($vo["template"]); ?>" /></td>
         </tr>
        <tr>
           <th style="width:8em;"><label for="tags">标　　签</label></th>
           <td><input name="tags" id="tags" value="<?php echo ($vo["tags"]); ?>" size="60" />
             标签之间用 , 隔开</td>
         </tr>
         <tr>
		  <th style="width:8em;"><label for="keyword">关 键 字</label></th>
		<td><input name="keyword" id="keyword" value="<?php echo ($vo["keyword"]); ?>" size="60"></td></tr>
        
        
        
         <tr>
		  <th style="width:8em;"><label for="link_url">外链地址</label></th>
		<td><input name="link_url" id="link_url" value="<?php echo ($vo["link_url"]); ?>" size="60"></td></tr>
        
        <tr>
		  <th style="width:8em;"><label for="copy_from">来　　源</label></th>
		<td><input name="copy_from" id="copy_from" value="<?php echo ($vo["copy_from"]); ?>"></td></tr>
        <tr>
		  <th style="width:8em;"><label for="from_link">来源链接</label></th>
		<td><input name="from_link" id="from_link" value="<?php echo ($vo["from_link"]); ?>" size="60"></td></tr>
        
		<th style="width:8em;">其它参数</th>
		  <td><select name="recommend" id="recommend">
					      <option value="0" <?php echo (selected($vo["recommend"],0)); ?>>默认不推荐</option>
					      <option value="1" <?php echo (selected($vo["recommend"],1)); ?>>推荐</option>
				        </select>
<select name="status" id="status">
				          <option value="0" <?php echo (selected($vo["status"],0)); ?>>默认显示</option>
				          <option value="1" <?php echo (selected($vo["status"],1)); ?>>隐藏</option>
                        </select><select name="istop" id="istop">
				          <option value="0" <?php echo (selected($vo["istop"],0)); ?>>默认不置顶</option>
				          <option value="1" <?php echo (selected($vo["istop"],1)); ?>>置顶</option>
              </select><label for="view_count">浏览</label>
<input name="view_count" type="text" id="view_count" value="<?php echo (($vo["view_count"])?($vo["view_count"]):0); ?>" size="5" maxlength="12"  />
<label for="display_order">排序</label>
<input name="display_order" type="text" id="display_order" value="<?php echo (($vo["display_order"])?($vo["display_order"]):0); ?>" size="5" maxlength="12"  /></td>
		</tr>
        <tr>
		  <th style="width:8em;"><label for="create_time">录入时间</label></th>
		  <td><input name="create_time" type="text" class="Wdate" id="create_time" style="width:160px"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true,isShowToday:true})" value="<?php echo (date("Y-m-d",$vo["create_time"])); ?>"/></td>
		  </tr>
		<td></td>
		</tr>
		
		</tbody>
		
		</table>
	</div>
	<div class="foot_action">
		<input type="submit" name="submit" value="提交更新" class="submit">
	  <input type="reset" name="button" id="button" value="还原重填" class="submit"/>
	<input name="id" type="hidden" id="id" value="<?php echo ($vo["id"]); ?>" />
	<input name="old_image" type="hidden" id="old_image" value="<?php echo ($vo["attach_image"]); ?>" />
	<input name="old_thumb" type="hidden" id="old_thumb" value="<?php echo ($vo["attach_thumb"]); ?>" />
	</div>
	</form>
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