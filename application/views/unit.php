
<style>
.inline{
    display:inline-block;
    width:100%;
    max-width:300px;
    min-height:150px;
    vertical-align:top;
    margin-top:3px;
    border:solid 1px transparent;
    cursor:pointer;
}
.inline:hover{
    border-color:#888;
}
.teacher-link:hover{
    text-decoration:underline;
}
</style>

<div class="w3-<?=CC_COLOR?> w3-card" style="padding:30px;">
    <h1 style="margin:0px;margin-top:1px;"><?=htmlspecialchars($title)?></h1>
    
    <div>
        <a class="light-blue-text" href="<?=site_url_filter('branch/'.CC_BRANCH, 'http', null, $class)?>">
            <i class="fa fa-graduation-cap"></i> <?=$class_name?>
        </a>
        <br>
        <a class="light-blue-text" href="<?=site_url_filter('branch/'.CC_BRANCH, 'http', null, $class, $subject['code'])?>">
            <i class="fa fa-book"></i>&nbsp;&nbsp;<?=htmlspecialchars($subject['code'].': '.$subject['title'])?>
        </a>

        <div>
            <?php foreach($branches as $branch):?>
 <a href="<?=site_url_filter('branch/'.$branch['code'])?>" title="<?=htmlspecialchars($branch['name'])?>">
                <span class="w3-tag w3-light-blue w3-text-dark-grey w3-tiny w3-hover-text-white" style="border-radius:2px;"><?=strtoupper($branch['code'])?></span>
            </a>
            <?php endforeach;?>
        </div>

        <?php /*Admin's edit link */ if (isset($auth_username)):?>
            <?php if ($auth_level >= 6):?>
            <a class="w3-btn w3-border w3-border-white w3-<?=CC_COLOR?> w3-round w3-margin w3-right" href="<?=site_url('unit/edit/'.urlencode($id))?>"><i class="fa fa-edit"></i> Edit this unit</a>
            <?php endif;?>
        <?php endif;?>
    </div>

</div>

<div class="w3-hide-large w3-hide-medium" style="z-index:11;position:fixed;right:10px;top:45px;">
    <div onclick="toggle_mobile_menu()" id="mobile-menu-btn" class="w3-round w3-white w3-card" style="opacity:0.85;padding:10px;">
        <i class="fa fa-bars"></i>
    </div>
</div>

<div class="w3-row">
    <div class="w3-col l9 m8 s12 w3-padding w3-center"><!-- Wrapper for main content -->

<?php if (count($notes) < 1):?>
    
    <div class="w3-container w3-center w3-margin-top" style="min-height:300px;">
        <img src="<?=base_url('assets/images/sad-emoji-404.png')?>" alt="Nothing is here" height="200">
        <div class="w3-xlarge bold-text w3-margin">Alas! I have nothing to show you in this unit.</div>
    </div>

<?php else:?>
    <div class="w3-left" style="text-align:left;margin-left:34px;">
        <h4 class="">Notes in <?=htmlspecialchars($title)?></h4>
        <p class="w3-text-grey">Total: <?=$total_notes?></p>
    </div>
    <div style="clear:both;">
    <?php foreach($notes as $note):?>
    <?php
    $url = site_url_filter('note/'.urlencode($note['id']) );
    $url = add_url_query($url, array(
        'unit_page'=>$page,
        'unit_num'=>$num
    ));
    ?>
    <div class="w3-margin-small w3-white w3-padding w3-center inline" onclick="location.href='<?=$url?>'">
        <div class="">
            <h3 class="w3-text-<?=CC_COLOR?> w3-hover-text-black">
                <a href="<?=$url?>">
                    <?=htmlspecialchars($note['title'])?>

                </a>
            </h3>
            <a class="w3-small w3-text-grey w3-hover-text-dark-grey teacher-link" href="<?=site_url_filter('teacher/'.urlencode($note['teacher']['id']) )?>">
                By <span style="font-weight:bold;">Prof. <?=htmlspecialchars($note['teacher']['name'])?></span>
            <?php if ($note['date_is_auto'] != 1):?> on <?=$note['date_string']?> <?php endif;?>

            </a>
        </div>
    </div>

    <!--<div class="w3-hide-small" style="height:10px;"></div>-->

    <?php endforeach;?>
    </div>
    <center>
    <div class="w3-row w3-padding w3-margin" style="width:100%;max-width:300px;">
        <div class="w3-col l6 m6 s6">
            <a class="w3-btn w3-border <?=($page === 1? 'not-active w3-light-grey w3-text-grey':('w3-'.CC_COLOR))?>" href="<?=site_url_filter('unit/'.$id.'/'.($page-1).'/'.$num)?>"><i class="fa fa-angle-left w3-margin-right"></i>Previous page</a>
        </div>

        <div class="w3-col l6 m6 s6">
            <a class="w3-btn w3-border  <?=( $total_pages <= $page? 'not-active w3-light-grey w3-text-grey': ('w3-'.CC_COLOR))?>" href="<?=site_url_filter('unit/'.$id.'/'.($page+1).'/'.$num)?>">Next page<i class="fa fa-angle-right w3-margin-left"></i></a>
        </div>
        <div class="w3-col l12 m12 s12 w3-margin-top">
            <span class="w3-text-grey w3-small">Page <?=$page?> of <?=$total_pages?></span>
        </div>
    </div>

    </center>
<?php endif;?>
    </div>

    <div class="w3-col l3 m4 w3-hide-small"><!-- Wrapper for other units -->
        <div class="w3-margin-top w3-padding">
            <div class="w3-margin-left w3-margin-top w3-padding">
                <p class="w3-text-grey w3-margin-left">Other units in <?=htmlspecialchars($subject['title'])?></p>
                <div class="w3-border" style="margin-top:25px;">
                    <?php
                    $mobile_menu = '<ul class="w3-ul">'.PHP_EOL;
                    foreach ($others as $o_units):?>
                        <a href="<?=site_url_filter('unit/'.$o_units['id'])?>">
                            <div class="w3-padding w3-<?=$id == $o_units['id']? 'white':'hover-grey'?>">
                                <i class="fa fa-angle-<?=$id == $o_units['id']? 'left':'right'?> w3-margin-right"></i> <?=htmlspecialchars($o_units['title'])?>

                            </div>
                        </a>
                    <?php
                    $mobile_menu .= '<li><a class="'.($id == $o_units['id']? 'bold-text':'').'" href="'.site_url_filter('unit/'.$o_units['id']).'">'.htmlspecialchars($o_units['title']).'</a></li>'.PHP_EOL;

                    endforeach;?>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="overlay" onclick="toggle_mobile_menu()" class="w3-hide-large w3-hide-medium w3-black" style="display:none;width:100%;height:100%;position:fixed;top:0px;z-index:9;opacity:0.6;">
</div>
<div id="mobile-menu" class="w3-hide-large w3-hide-medium w3-margin w3-padding w3-white w3-border w3-round" style="position:fixed;z-index:10;top:0px;right:10px;max-height:80%;overflow:auto;display:none;">
<p class="w3-padding w3-small w3-text-grey">Other units in <?=htmlspecialchars($subject['title'])?></p>

<?=$mobile_menu?>

</div>

<?=jquery()?>

<script>
const mobile_menu=$('#mobile-menu');
const overlay=$('#overlay');
const mmbtn=$('#mobile-menu-btn');
const i=mmbtn.find('i');

function toggle_mobile_menu(){
    if (mobile_menu.is(':visible')){
        mobile_menu.hide();
        overlay.hide();
        mmbtn.css('opacity',0.7);
        i.removeClass('fa-times').addClass('fa-bars');
    }
    else{
        mobile_menu.show();
        overlay.show();
        mmbtn.css('opacity',1);
        i.removeClass('fa-bars').addClass('fa-times');
    }
}
</script>
