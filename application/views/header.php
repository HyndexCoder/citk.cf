<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$current_url = htmlspecialchars(isset($current_url)? $current_url:current_url());
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#1585FF" />
    
    <link rel="canonical" href="<?=$current_url?>" />
    <?=css_tag('w3.css')?>
    <?=css_tag('font-awesome.min.css')?>
    <?=css_tag('main.css')?>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="192x192"  href="<?=base_url('assets/images/icon/android-icon-192x192.png')?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('assets/images/icon/favicon-32x32.png')?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=base_url('assets/images/icon/favicon-96x96.png')?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/images/icon/favicon-16x16.png')?>">


    <title><?=(isset($page_title)?htmlspecialchars($page_title):"Welcome")?> | <?=CC_SITENAME?></title>
</head>
<body>
<div id="container">

<div class="w3-row" style="background-color:#1585FF;color:#fff;">
    <div class="w3-col l3 m4 s6">
        <a href="<?=site_url()?>" class="w3-padding" style="font-size:30px;font-weight:bold;font-family:sans-serif;">
            <span style="color:#fff">CITK</span><span class="w3-text-dark-grey" style="">.cf</span>
        </a>
    </div>
    <div class="w3-col l9 m8 s6">
        <div class="w3-right w3-margin-right w3-margin-top">
            <form class="w3-hide-small" action="<?=site_url("search")?>">
                <input type="hidden" name="ref" value="<?=uri_string()?>">
                <div class="w3-light-grey w3-round" style="">
                    <input id="pc-search-text" type="text" name="query" class="w3-round" placeholder="Search">
                    <button id="pc-search-btn"><i class="fa fa-search"></i></button>
                </div>
            </form>
            <div class="w3-hide-large w3-hide-medium">
                <button onclick="mobile_search_toggle(this)" style="background-color:inherit;color:inherit;border:none;"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
</div>

<div id="mobile-search" class="w3-hide-large w3-hide-medium w3-container w3-center" style="background-color:#1585FF;color:#fff;position:static;top:100px;width:100%;z-index:20;display:none;"><!--Mobile search form-->
    <div class="w3-padding">
        <form action="<?=site_url("search")?>">
            <input type="hidden" name="ref" value="<?=uri_string()?>">

            <input id="mobile-search-text" class="w3-input" type="text" name="query" placeholder="Type here to search">
            <button class="w3-input w3-grey">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </div>
</div>

<?php
/**
 * Retriving flash messages if they exists
 */
$flash = get_flashmsg();
if (! is_null($flash)):?>
<div class="w3-card w3-margin-top w3-padding w3-<?=$flash['color']?> w3-round" style="position:fixed;z-index:1000;max-height:90%;overflow:auto;bottom:100px;right:10px;" id="flash-msg">
    <div class="w3-row">
        <div class="w3-padding w3-col l10 m10 s10"><?=$flash['message']?></div>
        <div class="w3-col l2 m2 s2 w3-padding">
            <button onclick="remove_flash('flash-msg')" style="background-color:transparent;" class="w3-border w3-border-<?=$flash['color']?> w3-<?=$flash['color']?> w3-display-middleright">
                <i class="fa fa-window-close"></i>
            </button>
        </div>
    </div>
</div>
<script>
function remove_flash($id='flash-msg'){
var fls=document.getElementById('flash-msg');
return fls.parentNode.removeChild(fls);
}
setTimeout(remove_flash, 10000);
</script>
<?php endif;?>
<script>
function mobile_search_toggle(el){
    var ms=document.getElementById('mobile-search'),btn=el.childNodes[0];
    if(ms.style.display=='none'){
        ms.style.display='block';
        document.getElementById('mobile-search-text').focus();
        btn.classList.remove('fa-search');
        btn.classList.add('fa-times');
    }
    else{
        ms.style.display='none';
        btn.classList.remove('fa-times');
        btn.classList.add('fa-search');
    }
}
</script>
<div id="body">
