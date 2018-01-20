<style>
.branch-box{
    width:100%;
    max-width:400px;
    height:200px;
    display: inline-block;
    vertical-align: top;
    text-align:center;
}
.units-div>a{
    color:#999;
    margin-left:10px;
    font-size: 13px;
}
</style>

<div class="w3-padding w3-center w3-xxlarge w3-margin" style="background-image:url('<?=base_url('assets/images/cit-main-image.png')?>'); background-position:center; background-repeat: no-repeat; height:250px;position:relative;">
    <div class="w3-display-middle w3-round w3-text-white" style="background-color:rgba(130,130,130,0.9); padding:3px;">
        <sup><i class="fa fa-quote-left"></i></sup> <?=CC_SITENAME?> is a website of the CITians, by the CITians, and for the CITians. <sup><i class="fa fa-quote-right"></i></sup>
    </div>
</div>

<div class="w3-padding">
<?php foreach ($branches as $branch):?>
<a href="<?=site_url('branch/'.$branch['code'])?>">
<div class="branch-box w3-round w3-border w3-white w3-hover-border-black w3-padding w3-margin-right w3-margin-top">
    <h1><i class="fa fa-<?=$branch['icon']?> w3-large"></i></h1>
    <h2><?=$branch['name']?></h2>
</div>
</a>
<?php endforeach;?>

</div>

<div class="units-div w3-padding w3-margin">
<span>Some random units:</span>
<?php
//Random units for SEO
foreach ($units as $unit):?>
<a href="<?=site_url('unit/'.$unit['id'])?>"><?=htmlspecialchars($unit['title'])?></a>
<?php endforeach;?>
</div>
