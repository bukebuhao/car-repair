<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>ThinkPHP示例：显示运行时间</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'><script language="JavaScript"><!--
            function showTime(){
                document.getElementById('run_time').innerHTML = document.getElementById('think_run_time').innerHTML;
                document.getElementById('think_run_time').innerHTML = '';
            }
            //--></script></head><body onload="showTime()"><div class="main"><h2>ThinkPHP示例之：运行时间显示</h2>            可以设置是否显示页面的运行时间，包括详细运行时间，数据库操作次数，内存开销等，显示效果如下：
            <div class="result" id="run_time">数据加载中~</div><table cellpadding=2 cellspacing=2><tr><td></td><td>首先要在入口里定义APP_DEBUG常量为1或true，开启调试行为。</td></tr><tr><td></td><td> 示例源码<br/>控制器IndexAction类<br/><?php highlight_file(LIB_PATH.'Action/IndexAction.class.php'); ?></td></tr><tr><td></td><td> 改配置文件启用显示的时间参数<br/>配置文件<br/><?php highlight_file(CONFIG_PATH.'/config.php'); ?></td></tr></table></div><div id="think_run_time">{__RUNTIME__} </div></body></html>