<?php if (!defined('THINK_PATH')) exit();?><!--头部部分--><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Blog By ThinkPHP <?php echo (THINK_VERSION); ?></title><link href="../Public/css/style.css" rel="stylesheet" type="text/css" /><script language="javascript" src="../Public/js/common.js" /></script><script type="text/javascript" src="__PUBLIC__/Js/Base.js"></script><script type="text/javascript" src="__PUBLIC__/Js/prototype.js"></script><script type="text/javascript" src="__PUBLIC__/Js/mootools.js"></script><script type="text/javascript" src="__PUBLIC__/Js/Ajax/ThinkAjax.js"></script><script type="text/javascript" src="__PUBLIC__/Js/UbbEditor.js"></script><script type="text/javascript" src="__PUBLIC__/Js/Form/CheckForm.js"></script><script language="JavaScript"><!--
            //指定当前组模块URL地址
            var URL = '__URL__';
            var APP	 =	 '__APP__';
            var PUBLIC = '__PUBLIC__';
            ThinkAjax.updateTip = '<IMG SRC="../Public/images/loading2.gif" WIDTH="16" HEIGHT="16" BORDER="0" ALT="loading..." align="absmiddle"> 数据处理中...';
            //--></script></head><body><div id="header"><div id="innerHeader"><div id="blogLogo"></div><div class="blog-header"><div class="blog-desc">ThinkPHP  [ Blog示例程序]</div></div><div id="menu"><ul><li><a href="__APP__">日志首页</a></li><li><a href="__APP__/Blog/add">撰写日志</a></li><li><a href="http://thinkphp.cn">官方网站</a></li></ul></div></div></div><!-- 编辑器调用开始 --><script src="__PUBLIC__/Js/thinkeditor/jquery-1.6.2.min.js"></script><script src="__PUBLIC__/Js/thinkeditor/ThinkEditor.js"></script><script>var _APP = "__APP__";
jQuery.noConflict();
(function($) { 
    $(function(){
        var app_len=_APP.lastIndexOf("/index.php")
        if(app_len!= -1){
            _APP = _APP.substr(0,app_len);
        }
        $("#textContent").ThinkEditor({"width":"600px","uploadURL":"/4s/examples/Blog/index.php?s=/Public/editor_up"
        });
    });
})(jQuery);

</script><!-- 编辑器调用结束 --><!--中间部分--><div id="mainWrapper"><div id="content" class="content"><div id="innerContent"><script language="JavaScript"><!--
                function addRow(){
                    curFileNum++;
                    rowsnum++;
                    var row=tbl.insertRow(-1);
                    //var td = arow.insertCell();
                    var cell = document.createElement("td");
                    cell.innerHTML='<div class="impBtn  fLeft" ><input type="file" id="file'+curFileNum+'" name="file'+ curFileNum +'" class="file  huge"></div><div class="fLeft hMargin"><img src="../Public/images/del.gif"  style="cursor:hand" onfocus="javascript:getObject(this)" onclick="deleteRow();" width="20" height="20" border="0" alt="删除" align="absmiddle"></div> ';
                    cell.align="center"
                    row.appendChild(cell);
                    //addFileForm.num.value=rowsnum;
                }
                function deleteRow(){
                    if(tbl.rows.length>0){
                        tbl.deleteRow(rindex); //删除当前行
                        rowsnum--;
                    }else{
                        return;
                    }
                    rindex="";
                }
                function getObject(obj){
                    rindex=obj.parentElement.parentElement.rowIndex;/*当前行对象*/
                }

                function uploading(msg){
                    $('result').style.display = 'block';
                    $('result').innerHTML = '<img src="../Public/images/ajaxloading.gif" width="16" height="16" border="0" alt="" align="absmiddle"> 文件上传中～';
                    return true;
                }
                function save(){
                    if ($('file1').value){
                        $('#form1').uploading();
                        $('upload').submit();
                    }else{
                        //document.getElementById('editor').value = KE.html('editor');
                        ThinkAjax.sendForm('form1','__URL__/insert/',doComplete,'result');
                    }
                }
                function selectCategory(){
                    var result= PopModalWindow('__APP__/Category/treeSelect/',268,360);
                    if(typeof(result) == "undefined") return;
                    $('categoryName').value=result[0][0];
                    $('categoryId').value=result[0][1];
                }
                function uploadComplete(){
                    document.getElementById('editor').value = KE.html('editor');
                    ThinkAjax.sendForm('form1','__URL__/insert/',doComplete,'result');
                    $('upload').reset();
                }
                function doComplete(data,status){
                    if (status==1){
                        window.location.href='__URL__';
                        $('form1').reset();
                        $('upload').reset();
                        fleshVerify();
                    }
                }
                //--></script><div class="commentbox"><table cellpadding=3 cellspacing=3 width="680px" ><tr><td colspan="2"><div class="block-title"><h5><img src="../Public/images/modify.gif" width="20" height="23" border="0" alt="" align="absmiddle"> 发表新的日志</h5></div></td></tr><tr><td colspan="2"><form method=post id="form1" ><table cellpadding=3 cellspacing=3 style="font-size:14px"><tr><td colspan="2"><div id="result" class="result none"></div></td></tr><tr><td class="tRight tTop" width="20%">标题：</td><td class="tLeft"><input type="text" class="huge text" check='Require' warning="标题不能为空" name="title"></td></tr><tr><td class="tRight tTop" >类别：</td><td class="tLeft"><select name="categoryId" class="medium text"><?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate["id"]); ?>"><?php echo ($cate["title"]); endforeach; endif; else: echo "" ;endif; ?></select></td></tr><tr><td class="tRight tTop" >日志：</td><td class="tLeft"><textarea name="content" id="textContent" cols="45" rows="5"></textarea></td></tr><tr><td class="tRight tTop" >标签：</td><td class="tLeft"><input type="text" class="huge text"  name="tags"><span style="color:gray">用空格隔开多个标签</span></td></tr><tr><td ></td><td class="center"><div style="width:85%;color:gray"><input type="hidden" name="ajax" value="1"><div class="fLeft hMargin"><img id="verifyImg" src="__URL__/verify" align="absmiddle"><input type="text" name="verify" class="text small"> 输入验证码 [ <a href="javascript:fleshVerify()">看不清？</a> ] </div><div class="fLeft hMargin"><input type="reset" class="submit small"  value="重 置" ></div><div class="fLeft hMargin"><input type="button" id="submit" value="发表日志" onclick="save()" class="submit small"></div></div></td></tr></table></form><form id="upload" method=post action="__URL__/upload/" onsubmit="return CheckForm(this);" enctype="multipart/form-data" target="iframeUpload"><table cellpadding=3 cellspacing=3 style="font-size:14px"><tr><td class="tRight tTop">附件：</td><td class="tLeft tTop"><input type="hidden" name="ajax" value="1"><iframe name="iframeUpload" src="" width="350" height="35" frameborder=0  scrolling="no" style="display:none;" ></iframe><input type="hidden" name="_uploadFileResult" value="result"><input type="hidden" name="_uploadFormId" value="upload"><input type="hidden" name="_uploadFileSize" value="-1"><input type="hidden" name="_uploadResponse" value="uploadComplete"><input type="hidden" name="_uploadFileVerify" value="<?php echo ($verify); ?>"><input type="hidden" name="_uploadFileType" value="jpeg,jpg,gif,png,doc,rar,zip,mp3,wav,flv,rm,asf"><input type="hidden" name="_uploadSavePath" value="<?php echo APP_PATH.'/../'.'Public/Uploads/';?>" ><input type="button" value="增 加" onclick="addRow();" class="submit small"><input type="submit" value="上 传" onclick="uploading();" class="small submit" /><table id='tbl' style="clear:both"></table></td></tr></table></form></td></tr></table></div></div><script language="JavaScript"><!--
            var curFileNum = 0;
            var rowsnum=0;  //记录行数
            var rindex="";       //列索引
            var tbl	= $('tbl');
            addRow();
            //--></script><!-- 版权信息区域 --><div id="footer" class="footer" ><div id="innerFooter">Powered by ThinkPHP <?php echo (THINK_VERSION); ?> | Template designed by <a target="_blank" href="http://www.topthink.com.cn">TopThink</a></div></div></body></html>