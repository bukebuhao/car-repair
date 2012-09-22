<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>ThinkPHP示例：CURD操作</title><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/common.css" /><script type="text/javascript" src="__PUBLIC__/Js/Base.js"></script><script type="text/javascript" src="__PUBLIC__/Js/prototype.js"></script><script type="text/javascript" src="__PUBLIC__/Js/mootools.js"></script><script type="text/javascript" src="__PUBLIC__/Js/Ajax/ThinkAjax.js"></script></head><body><script language="JavaScript"><!--
	function add(){
		window.location.href="__URL__/add";
	}
	function edit(id){
		window.location.href="__URL__/edit/id/"+id;
	}
	function del(id){
		ThinkAjax.send('__URL__/delete','ajax=1&id='+id,complete,'result');
	}
	function complete(data,status){
		if (status==1)
		{
			$('list').removeChild($('div_'+data));
		}
	}
 //--></script><div class="main"><h2>ThinkPHP示例之：CURD操作</h2>方便地完成对单表的CURD操作<P><table cellpadding=2 cellspacing=2><tr><td colspan="2"><input type="button" value="新 增" class="small button" onClick="add()"><div id="result" class="none result" style="font-family:微软雅黑,Tahoma;letter-spacing:2px"></div></td></tr><tr><td></td><td><div id="list" ><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div id="div_<?php echo ($vo["id"]); ?>" class="result" style='font-weight:normal;<?php if(($key%2) == "1"): ?>background:#ECECFF<?php endif; ?>'><div style="border-bottom:1px dotted silver"><?php echo ($vo["title"]); ?>  [<?php echo ($vo["email"]); echo (date('Y-m-d H:i:s',$vo["create_time"])); ?>] <br/><input type="button" value="编辑" class="small button" onClick="edit(<?php echo ($vo["id"]); ?>)"><input type="button" value="删除" class="small button" onClick="del(<?php echo ($vo["id"]); ?>)"></div><div class="content"><?php echo (nl2br($vo["content"])); ?></div></div><?php endforeach; endif; else: echo "" ;endif; ?></div></td></tr><tr><td></td><td><hr> 示例源码<br/>控制器IndexAction类<br/><?php highlight_file(LIB_PATH.'Action/IndexAction.class.php'); ?><br/>模型FormModel类<br/><?php highlight_file(LIB_PATH.'Model/FormModel.class.php'); ?></td></tr></table></div></body></html>