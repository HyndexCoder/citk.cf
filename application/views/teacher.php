
<style>

</style>
<div class="w3-row">
    <div class="w3-col l3 m12 s12 w3-padding">
        <div class="w3-card w3-round w3-white">
            <div class="w3-container">
                <p class="w3-center">
                    <img src="<?=htmlspecialchars($teacher['photo'])?>" class="w3-circle" style="height:106px;width:106px" alt="Prof. <?=htmlspecialchars($teacher['name'])?>">
                </p>
                <h4 class="w3-center">
                    Prof. <?=htmlspecialchars($teacher['name'])?>

                </h4>
                <hr>
                <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-text-theme"></i> <?=htmlspecialchars($teacher['designation'])?></p>
                <p><i class="fa fa-envelope fa-fw w3-margin-right w3-text-theme"></i> 
                    <a href="mailto:<?=urlencode($teacher['email'])?>" target="_blank">
                        <?=htmlspecialchars($teacher['email'])?>

                    </a>
                </p>
                <p>
                    <i class="fa fa-phone fa-fw w3-margin-right w3-text-theme"></i> <?=htmlspecialchars($teacher['phone'])?>

                </p>
                <p>
                    <i class="fa fa-calendar fa-fw w3-margin-right w3-text-theme"></i> <?=htmlspecialchars($teacher['tenure'])?>

                </p>
            </div>
            <?php /*Admin's edit link */ if (isset($auth_username)):?>
            <div class="w3-center">
                <a class="w3-btn w3-border w3-border-white w3-<?=CC_COLOR?> w3-round w3-margin" href="<?=site_url('teacher/edit/'.$teacher['id'])?>"><i class="fa fa-edit"></i> Edit this teacher</a>
            </div>
            <?php endif;?>

        </div>
    </div>

    <div class="w3-col l9 m12 s12 w3-padding-right" style="width:100%;max-width:800px;">
        <div class="w3-text-grey w3-margin w3-padding">Total notes <?=$notes['total']?></div>

        <?php foreach ($notes['notes'] as $note):?>

            <div class="w3-padding w3-white w3-border">
                <div>
                    <a href="<?=site_url_filter('note/'.$note['id'])?>" class="w3-text-<?=CC_COLOR?> w3-hover-text-black">
                        <h3><?=htmlspecialchars($note['title'])?></h3>
                    </a>
                </div>
<?php
$unit_link = site_url_filter('unit/'.$note['unit']['id']);
$branch_link = site_url_filter('branch/'.CC_BRANCH);
$subject_link = add_url_query($branch_link, array('class'=>$note['unit']['class'], 'subject'=>$note['unit']['subject']));
$class_link = add_url_query($branch_link, array('class'=>$note['unit']['class']));
?>

                <div class="w3-small w3-text-grey">
                    <div>
                    <?php if ($note['date_is_auto'] == '0'):?>
    <i class="fa fa-calendar"></i> <?=format_date($note['date'])?>
                    <?php endif;?>

                    </div>
                    <div>
                        Unit: <a class="bold-text w3-hover-text-dark-grey" href="<?=$unit_link?>"><?=htmlspecialchars($note['unit']['title'])?></a> (<a class="bold-text w3-hover-text-dark-grey" href="<?=$subject_link?>"><?=htmlspecialchars($note['unit']['subject'])?></a>), 
                        Class: <a class="bold-text w3-hover-text-dark-grey" href="<?=$class_link?>"><?=classcode_to_name($note['unit']['class'])?></a>
                    </div>
                </div>
            </div>
            <div class="w3-hide-small" style="height:10px;"></div>
        <?php endforeach;?>

        <center>
            <div class="w3-row w3-padding w3-margin" style="width:100%;max-width:300px;">
                <div class="w3-col l6 m6 s6">
                    <a class="w3-btn w3-border <?=($page === 1? 'not-active w3-light-grey w3-text-grey':('w3-'.CC_COLOR))?>" href="<?=site_url_filter('teacher/'.$teacher['id'].'/'.($page-1).'/'.$num)?>"><i class="fa fa-angle-left w3-margin-right"></i>Previous page</a>
                </div>

                <div class="w3-col l6 m6 s6">
                    <a class="w3-btn w3-border  <?=( $total_pages <= $page? 'not-active w3-light-grey w3-text-grey': ('w3-'.CC_COLOR))?>" href="<?=site_url_filter('teacher/'.$teacher['id'].'/'.($page+1).'/'.$num)?>">Next page<i class="fa fa-angle-right w3-margin-left"></i></a>
                </div>
                <div class="w3-col l12 m12 s12 w3-margin-top">
                    <span class="w3-text-grey w3-small">Page <?=$page?> of <?=$total_pages?></span>
                </div>
            </div>
        </center>

    </div>

</div>
