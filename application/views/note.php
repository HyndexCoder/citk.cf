<style>
#content-holder a{
color:<?=CC_COLOR?>;
}
#content-holder{
font-size:18px;
}
</style>


<div class="w3-<?=CC_COLOR?> w3-card" style="padding:30px;z-index:5">
    <a class="light-blue-text" href="<?=$unit_link?>">
        <i class="fa fa-angle-left w3-margin-right"></i>Back to <?=htmlspecialchars($unit['title'])?>
    </a>
    <h1 style="margin:0px;margin-top:1px;"><?=htmlspecialchars($title)?></h1>

    <div>
        <span>
            By
            <a class="w3-hover-text-light-grey" style="font-weight:bold" href="<?=site_url_filter('teacher/'.urlencode($teacher['id']))?>">
                Prof. <?=htmlspecialchars($teacher['name'])?>

            </a>
<?php       if ($date_is_auto != 1):?>
                on <?=$date_string?>
<?php       endif;?>

        </span>
    </div>

    <?php /*Admin's edit link */ if (isset($auth_username)):?>
        <?php if ($auth_level >= 6):?>
        <a class="w3-btn w3-border w3-border-white w3-<?=CC_COLOR?> w3-round w3-margin w3-right" href="<?=site_url('note/edit/'.urlencode($id))?>"><i class="fa fa-edit"></i> Edit this note</a>
        <?php endif;?>
    <?php endif;?>

</div>

<div class="w3-hide-large w3-hide-medium" style="z-index:11;position:fixed;right:10px;top:45px;">
    <div onclick="toggle_mobile_menu()" id="mobile-menu-btn" class="w3-round w3-white w3-card" style="opacity:0.85;padding:10px;">
        <i class="fa fa-bars"></i>
    </div>
</div>

<div class="w3-row">
    <div class="w3-col l1 s12 m1 w3-hide-small" style="min-height:100%;">
        &nbsp;
    </div>

    <div class="w3-col l8 s12 m8 w3-white" style="min-height:100%;">
        <div style="height:50px;"></div>

        <div class="w3-padding" id="content-holder" style="overflow:auto">
            <?php if (strlen($content) > 0):?>
                <?=$content?>
            <?php else:?>
                <div class="w3-container w3-center" style="min-height:300px;">
                    <img src="<?=base_url('assets/images/sad-emoji-404.png')?>" alt="Nothing is here" height="200">
                    <div class="w3-xlarge bold-text w3-margin">Alas! I have nothing to show you in this note.</div>
                </div>
            <?php endif;?>

        </div>

        <div class="w3-margin-bottom w3-border-bottom" style="height:200px;clear:both;"></div>
        <div class="w3-padding">
            <?php if (! is_null($nearby['prev'])):?>
            <a class="w3-btn w3-border w3-left" href="<?=site_url_filter('note/'.$nearby['prev']['id'])?>">
                <i class="fa fa-angle-left w3-margin-right"></i> Previous note
            </a>
            <?php endif;?>

            <?php if (! is_null($nearby['next'])):?>
            <a class="w3-btn w3-border w3-right" href="<?=site_url_filter('note/'.$nearby['next']['id'])?>">
                Next note <i class="fa fa-angle-right w3-margin-left"></i>
            </a>
            <?php endif;?>

        </div>
        <div class="w3-margin-bottom" style="height:70px;clear:both;"></div>

        <div class="w3-padding">
            <div id="disqus_thread"></div>
        </div>
    </div>

    <div class="w3-col l3 s12 m3 w3-hide-small">
        <div id="pc-menu" style="">
            <p class="w3-padding w3-small w3-text-grey">Notes on <?=htmlspecialchars($unit['title'])?> (<?=$total_notes?>)</p>

            <?php
            $mobile_menu = '<ul class="w3-ul" id="mobile-menu-main">'.PHP_EOL;
            foreach ($others as $note):?>
            <?php
            //Preparing data to bulid menu
            $o_link = site_url_filter('note/'.$note['id']);
            $o_title = htmlspecialchars($note['title']);
            $o_color = $note['id'] == $id? 'white':'hover-grey';
            $o_icon = $note['id'] == $id? 'left':'right';
            ?>

            <a href="<?=$o_link?>">
                    <div class="w3-<?=$o_color?> w3-padding w3-border-bottom w3-border-dark-grey">
                        <i class="fa fa-angle-<?=$o_icon?> w3-padding"></i>
                        <?=$o_title?>

                    </div>
            </a>
            <?php
            //Collecting data to generate the menu for mobiles
            $mobile_menu .= '<li><a href="'.$o_link.'" class="w3-text-'.($note['id'] == $id? 'black bold-text':'blue').'">'.$o_title.'</a></li>'.PHP_EOL;
            ?>
            <?php
            endforeach;
            $mobile_menu .= '</ul>';
            ?>
        </div>

        <?php //If load more button is required
        if ($total_notes > count($others)):?>
            <center>
                <div>
                    <button id="pc-load-more" class="w3-btn w3-margin w3-border w3-white w3-round">Load more</button>
                </div>
            </center>

        <?php endif;?>
    </div>
</div>

<div id="overlay" onclick="toggle_mobile_menu()" class="w3-hide-large w3-hide-medium w3-black" style="display:none;width:100%;height:100%;position:fixed;top:0px;z-index:9;opacity:0.6;">
</div>
<div id="mobile-menu" class="w3-hide-large w3-hide-medium w3-margin w3-padding w3-white w3-border w3-round" style="position:fixed;z-index:10;top:0px;right:10px;max-height:80%;overflow:auto;display:none;">
<p class="w3-padding w3-small w3-text-grey">Notes on <?=htmlspecialchars($unit['title'])?> (<?=$total_notes?>)</p>

<?=$mobile_menu?>

<?php //If load more button is required
if ($total_notes > count($others)):?>
    <center>
        <div>
            <button id="mobile-load-more" class="w3-btn w3-margin w3-border w3-white w3-round">Load more</button>
        </div>
    </center>

<?php endif;?>

</div>

<?=jquery()?>
<?=helper_js()?>
<script>
var total_notes=<?=$total_notes?>,loaded_other_pc_notes=<?=count($others)?>,loaded_other_mobile_notes=loaded_other_pc_notes;
const current_note_id='<?=$id?>';
const load_notes_url='<?=site_url('note/by_unit_json/'.$unit['id'])?>/';
const note_link_replacer='<?php $note_link_replacer = '@noteid:@'; echo $note_link_replacer;?>';
const note_link_template='<?=site_url_filter('note/'.$note_link_replacer)?>';
</script>


<script>
const mobile_menu=$('#mobile-menu');
const overlay=$('#overlay');
const mmbtn=$('#mobile-menu-btn');
const i=mmbtn.find('i');
const load_per_request=5;

$('#pc-load-more').click(function(e){
    load_notes_uimanager(true,$(this),$('#pc-menu'),function(title='',link='#',current=false){
        return $('<a>',{
            href:link
        }).html(
            $('<div>',{
                class:'w3-'+(current?'white':'hover-grey')+' w3-padding w3-border-bottom w3-border-dark-grey'
            }).html(
                $('<i>',{
                    class:'fa fa-angle-'+(current?'left':'right')+' w3-padding'
                })
            ).append(title)
        );
    });
});
$('#mobile-load-more').click(function(e){
    mobile_menu.scrollTop(mobile_menu.prop("scrollHeight"));
    load_notes_uimanager(false,$(this),$('#mobile-menu-main'),function(title='',link='#',current=false){
        return $('<li>').append(
            $('<a>',{
                href:link,
                class:'w3-text-'+(current?'black bold-text':<?=CC_COLOR?>)+''
            }).html(title)
        );
    },function(){
        mobile_menu.animate({ scrollTop: mobile_menu.prop("scrollHeight")}, 1000);
    });
});

function load_notes_uimanager(pc,el,parent,template,success=null){
    var load_ui=loading_ui();
    el.hide().after(load_ui);

    if(pc)var loaded=loaded_other_pc_notes;
    else var loaded=loaded_other_mobile_notes;

    if(loaded<total_notes){
        load_notes(loaded,function(data,status){
            if(status=='success'){
                
                if(pc)loaded_other_pc_notes+=data.notes.length;
                else loaded_other_mobile_notes+=data.notes.length;

                if(pc)loaded=loaded_other_pc_notes;
                else loaded=loaded_other_mobile_notes;

                if(loaded>=total_notes)el.remove();
                else el.show();

                data.notes.forEach(function(note,i){
                    parent.append(template(
                        htmlencode(note.title),
                        note_link_template.replace(note_link_replacer,note.id),
                        current_note_id==note.id,
                    ))
                });
                if(typeof success=='function')success();
            }
        },function(xhr){
            alert('Failed to load notes');
        },load_per_request,function(){
            load_ui.remove();
        });
    }
}

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

function load_notes(offset=2,success=null,error=null,limit=10,complete=null){
    $.ajax({
        url:load_notes_url+offset+'/'+limit,
        dataType:'json',
        success:function(data,status){
            if(typeof success=='function')success(data,status);
        },
        error:function(xhr){
            if(typeof error=='function')error(xhr);
        },
        complete:function(){
            if(typeof complete=='function')complete();
        }
    });
}

</script>

<?php //Disqus' javascript?>
<script>
var disqus_config = function () {
    this.page.url = 'http://citk.cf/index.php/note/<?=htmlspecialchars($id)?>'; 
    this.page.identifier = 'note/<?=htmlspecialchars($id)?>';
};
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = 'https://citk.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>
    Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a>
</noscript>

<script type="text/javascript" async
  src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
