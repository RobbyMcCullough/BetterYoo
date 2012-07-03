<!doctype html>  
<html lang="en">
<head>
	
	<? if ($tmpl['funnel'] == 1) echo $tmpl['variation_js']; ?>
	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?=$meta['meta_title']?></title>
    
	<meta name="description" content="<?=$meta['meta_desc']?>">
	<meta name="keywords" content="<?=$meta['meta_keywords']?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <link href='http://fonts.googleapis.com/css?family=puritan' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
	
    <link rel="stylesheet" type="text/css" href="<?=WEBROOT?>css/custom-theme/jquery-ui-1.8.12.custom.css">
    <link rel="stylesheet" type="text/css" href="<?=WEBROOT?>css/betteryoo.css?ver=1.11">
    
    <link rel="apple-touch-icon-precomposed" href="http://betteryoo.com/images/yoo-60.png"> 
    <link rel="shortcut icon" href="http://betteryoo.com/favicon.ico" />

	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-3891044-9']);
	  _gaq.push(['_setDomainName', '.betteryoo.com']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	  var activeReminders = '<?=$tmpl['active_reminders']?>';
	  var reminderLimit   = '<?=$tmpl['reminder_limit']?>';
  	</script>
</head>
 
<body class="<?=$tmpl['bodyClass']?>">
	
<? if ($_SESSION['logged'] == false) : ?>	
	<div id="loginForm">
		<iframe src="http://www.facebook.com/plugins/like.php?app_id=224753644203171&amp;href=http%3A%2F%2Fbetteryoo.com&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=trebuchet+ms&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe>
		<a href="<?=WEBROOT?>login/" title="Member Login">login</a>
		<!--
		<form action="<?=WEBROOT?>login/" method="POST">
			<input type="tel" name="phone" placeholder="Phone Number" />
			<input type="password" name="password" placeholder="Password" />
			<input type="submit" value="login" class="submitButtonSmall" />
		</form>
		-->
	</div>
<? else: ?>
	<div id="loginForm" class="fade" style="width: 310px; margin-top: 8px;">
		Welcome, <a href="<?=WEBROOT . $_SESSION['phone']?>"><?=$_SESSION['phone']?></a> &bull; <a href="<?=WEBROOT?>logout/">logout</a>
		<iframe src="http://www.facebook.com/plugins/like.php?app_id=224753644203171&amp;href=http%3A%2F%2Fbetteryoo.com&amp;send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=trebuchet+ms&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px; margin-top: 0px;" allowTransparency="true"></iframe>
	</div>
<? endif; ?>

<div id="container">

