<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
if( ! isset($CI))
{
    $CI = new CI_Controller();
}
$CI->load->helper('url');

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style>
body{
font-family:arial;
}
a{
color:<?=defined('CC_COLOR')? CC_COLOR: 'blue'?>;
text-decoration:none;
}
.container{
padding: 10px;
margin:20px;
text-align:center;
}
</style>
</head>
<body>
	<div class="container">
		<img src="<?=base_url('assets/images/sad-emoji-404.png')?>" alt="Sad, coz we didn't find" height="250">
		<h1><span style="color:red">404</span> Page not found</h1>
		<p>That&apos;s an error - <tt>The page you requested was not found on this server.</tt></p>
		<p>
			<a href="<?=site_url()?>">You may try from the homepage</a>
		</p>
	</div>
</body>
</html>