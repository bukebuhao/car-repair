<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/Jquery/jquery.js"></script>
<title>companyCMS 管理中心</title>
<style type="text/css">
* { margin: 0; padding: 0; }
body { text-align: center; color: #333; }
body, td, th { font: 12px/1.5em Arial; }
.loginbox { margin: 180px auto 60px; text-align: left; }
td { }
.logo { width: 296px; w\idth: 226px; padding: 90px 70px 30px 0; background: url(__PUBLIC__/Admin/images/login_logo.png) no-repeat 100% 50%; text-align: right; }
.logo p { margin: -40px 0 0 0; }
.loginform th, .loginform td { padding: 3px; font-size: 14px; }
.t_input { padding: 3px 2px; border: 1px solid; border-color: #666 #EEE #EEE #666; }
.submit { height: 22px; padding: 0 5px; border: 1px solid; border-color: #EEE #666 #666 #EEE; background: #DDD; font-size: 13px; cursor: pointer; }
.footer { position: absolute; bottom: 10px; left: 50%; width: 500px; margin-left: -250px; color: #999; }
a { color: #2366A8; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
<link type="image/x-icon" href="__PUBLIC__/Images/company.ico" rel="shortcut icon">
<script type="text/javascript" >
$(function(){   
    $("#submit").click(function(){
	var jumpUri = $("#jumpUri").val();
       $.ajax({   
		  type:"POST",   
			  url:"<?php echo U('Public/doLogin');?>",
			  data:{
				  username: $('#username').val(), password: $('#password').val(), verifyCode: $('#verifyCode').val()
				  },   
			  beforeSend:function(){
				  	$("#msg").html('<span style="color:#FF0000"><img src="__PUBLIC__/Admin/images/loading.gif" align="absmiddle">正在提交登录...</span>'); 
				  },                
			  success:function(data){
				switch(data)
				{
					case 'errorVerifyCode':
						//$("#verifyImage").attr('src', "<?php echo U('Public/verify',0,0,0);?>?"+ Math.random());
						resetVerifyCode();
						$("#msg").html('<span style="color:#FF0000">验证码错误</span>'); 
						break
					case 'emptyInfo':
						$("#msg").html('<span style="color:#FF0000">用户名密码必须填写</span>'); 
						break
					case 'usernameFalse':
						$("#msg").html('<span style="color:#FF0000">用户信息不存在，登录失败</span>'); 
						resetVerifyCode();
						break
					case 'passwordFalse':
						$("#msg").html('<span style="color:#FF0000">密码错误</span>'); 
						resetVerifyCode();
						break
					case 'roleFalse':
						$("#msg").html('<span style="color:#FF0000">当前用户被限制登录，请联系管理员</span>');
						resetVerifyCode();
						break
					case 'roleLost':
						$("#msg").html('<span style="color:#FF0000">不存在的用户组，请联系管理员</span>');
						resetVerifyCode();
						break
					case 'loginSuccess':
						$("#msg").html('<span style="color:#FF0000">登录成功</span>');
						if(jumpUri == ''){
							window.location.href = '<?php echo U("Index/index");?>';
						}else{
							window.location.href = jumpUri;
						}
						//window.location.href = '<?php echo U("Index/index");?>';
						return true;
						break
					default:
						$("#msg").html('<span style="color:#FF0000">'+data+'</span>');
						alert ('未知错误，请联系管理员');
				}
				return false;			
			}               
         });   
		return false;
    });  
	$("#verifyImage").click(function(){
		resetVerifyCode();						
	})
}); 

function resetVerifyCode()
{
	$("#verifyImage").attr('src', "<?php echo U('Public/verify');?>?t="+ Math.random());
}

</script>
</head>
<body>

<div class="mainarea">
<form method="post" action="<?php echo U('Public/doLogin');?>">
<table callspacing="0" cellpadding="0" class="loginbox">
<tr>
<td class="logo"></td>
<td>
<table callspacing="0" cellpadding="0" class="loginform">
<tr>
<th>用户名：</th>
<td><input name="username" type="text" class="t_input" id="username"  style="margin-right:5px;width:150px;" value="" maxlength="20" /></td>
</tr>
<tr>
<th>密　码：</th>
<td><input name="password" type="password" class="t_input" id="password"  style="margin-right:5px;width:150px;" value="" maxlength="20" /></td>
</tr>
<tr>
<th>验证码：</th>
<td><input name="verifyCode" type="text" class="t_input" id="verifyCode" style="margin-right:5px;width:55px;" value="" maxlength="5" /><img src="<?php echo U('Public/verify');?>" align="absmiddle" class="checkcode" title="如果您无法识别验证码，请点图片更换" id="verifyImage"/></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value="登录" class="submit" id="submit" />
  <input type="reset" name="reset" value="重填" class="submit" id="reset" /><input name="jumpUri" type="hidden" id="jumpUri" value="<?php echo ($jumpUri); ?>" />
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
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