<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo ($seoinfo["seotitle"]); ?></title>
<meta name="description" content="<?php echo ($seoinfo["seodescription"]); ?>"/> 
<meta name="keywords" content="<?php echo ($seoinfo["seokeywords"]); ?>" />
<link href="__PUBLIC__/Front/style/default.css" rel="stylesheet" type="text/css" />
<link type="image/x-icon" href="__PUBLIC__/Images/company.ico" rel="shortcut icon">
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/Jquery/jquery.js"></script>
</head>

<body>
<div id="frame">
<!-- topstart -->
<div class="page_top_title"><img src="__PUBLIC__/Front/images/logo.jpg" title="上海藤陟网络软件有限公司"/></div>
<!--nav start -->
<div class="nav_bg">
<ul>
<?php if(MODULE_NAME != 'Index'): ?><li class="center font14px fontbold"><a href="<?php echo U('Index/index');?>"><span>网站首页</span></a></li><?php endif; ?>

<?php if(MODULE_NAME == 'News'): ?><div class="center font14px fontbold en1">新闻资讯</div>
<?php else: ?>
<li class="center font14px fontbold"><a href="<?php echo U('News/index');?>"><span>新闻资讯</span></a></li><?php endif; ?>

<?php if(MODULE_NAME == 'Product'): ?><div class="center font14px fontbold en1">产品中心</div>
<?php else: ?>
<li class="center font14px fontbold"><a href="<?php echo U('Product/index');?>"><span>产品中心</span></a></li><?php endif; ?>

<?php if(MODULE_NAME == 'Company'): ?><div class="center font14px fontbold en1">企业介绍</div>
<?php else: ?>
<li class="center font14px fontbold"><a href="<?php echo U('Company/index');?>"><span>企业介绍</span></a></li><?php endif; ?>

<?php if(MODULE_NAME == 'Job'): ?><div class="center font14px fontbold en1">招贤纳士</div>
<?php else: ?>
<li class="center font14px fontbold"><a href="<?php echo U('Job/index');?>"><span>招贤纳士</span></a></li><?php endif; ?>

<?php if(MODULE_NAME == 'Feedback'): ?><div class="center font14px fontbold en1">意见反馈</div>
<?php else: ?>
<li class="center font14px fontbold"><a href="<?php echo U('Feedback/index');?>"><span>意见反馈</span></a></li><?php endif; ?>

</ul>
</div>
<!--nav end -->
<!-- topend -->
<!-- contenstart -->
<div id="layout_content">


<link href="__PUBLIC__/Js/coin-slider/coin-slider-styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="__PUBLIC__/Js/coin-slider/coin-slider.min.js"></script>
<script type="text/javascript" language="javascript" src="__PUBLIC__/Front/js/index.js"></script>
<div id="coin-slider" class="page_img">
		<?php if(is_array($adFirstList)): foreach($adFirstList as $key=>$vo): ?><a target="_blank" href="<?php echo ($vo["link_url"]); ?>" title="<?php echo ($vo["title"]); ?>"><img alt="<?php echo ($vo["attach_alt"]); ?>" src="__UPLOAD__/<?php echo ($vo["attach_file"]); ?>">
		<span><?php echo ($vo["description"]); ?></span>
		</a><?php endforeach; endif; ?>
</div>  
  
  
  
  
  <!-- newsstart -->
  <div class="page_news">
    <ul class="page_news_bt font14px fontbold">新闻资讯</ul>
    <ul>
     <?php if(is_array($newList)): foreach($newList as $key=>$vo): ?><li><a href="<?php echo U('News/detail', array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>" target="_blank" style="<?php echo ($vo["title_style"]); ?>"><?php echo mb_substr($vo['title'], 0, 16);?></a></li><?php endforeach; endif; ?>
    </ul>
  </div>
  <!-- newsend -->
  <!-- prostart -->
  <div class="page_pro">
    <div class="page_news_bt font14px fontbold">最新产品</div>
    <!-- oneprostart -->
     <?php if(is_array($productList)): foreach($productList as $key=>$vo): ?><ul>
	      <li><a href="<?php echo U('Product/detail', array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><img src="__UPLOAD__/<?php echo ($vo["attach_thumb"]); ?>" title="<?php echo ($vo["title"]); ?>" width="100px" height="100px"/></a></li>
	      <li class="font14px fontbold"><a href="<?php echo U('Product/detail', array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>" target="_blank" style="<?php echo ($vo["title_style"]); ?>"><?php echo mb_substr($vo['title'], 0, 9);?></a></li>
	      <li class="font14px fontred fontbold"><?php echo ($vo["sale_price"]); ?>元</li>
	    </ul><?php endforeach; endif; ?>
      <!-- oneproend -->
  </div>
  <!-- proend -->
  <!-- telstart -->
  <div class="page_tel">
    <ul class="page_tel_en1">
      <li class="font14px fontred fontbold">0279-85188888</li>
      <li class="font14px fontbold"><a href="<?php echo U('Company/detail',array('itemId'=>'contact'));?>">联系我们</a></li>
    </ul>
    <ul class="page_tel_en2">
      <li class="font14px fontred fontbold">&nbsp;</li>
      <li class="font14px fontbold"><a href="<?php echo U('Feedback/index');?>">客户反馈</a></li>
    </ul>
    <ul class="page_tel_en3">
      <li class="font14px fontred fontbold">&nbsp;</li>
      <li class="font14px fontbold"><a href="<?php echo U('News/index');?>">媒体报道</a></li>
    </ul>
  </div>
  <!-- telend -->
  <!-- footstart -->
  <div id="layout_bottom"><a href="#">某某某公司</a>版权所有 <a href="#">XXX</a>技术支持</div>
  <!-- footend -->
</div>
<!-- contentend -->
</div>
</body>
</html>