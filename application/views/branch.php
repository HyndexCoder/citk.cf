
<style>
.class-accordion,.subject-accordion{
    cursor:pointer;
    -webkit-user-select: none; /* Safari */        
    -moz-user-select: none; /* Firefox */
    -ms-user-select: none; /* IE10+/Edge */
    user-select: none; /* Standard */
}
.class-accordion{
    font-size:22px;
    color:#555!important;
}
.class-subjects,.unit-box{
    display:none;
    min-height: 50px;
}
</style>

<div class="w3-<?=CC_COLOR?> w3-card" style="padding:30px;">
    <h1 style="margin:0px;margin-top:1px;">
        <i class="fa fa-<?=htmlspecialchars($branch['icon'])?> w3-padding w3-hide-small"></i><?=htmlspecialchars($branch['name'])?>
    </h1>
</div>

<div class="w3-content w3-margin-top w3-margin-bottom w3-card">
    <?php foreach ($classes as $class):?>
        <div data-expanded="0" class="class-accordion <?=($class['selected']?'selected':'')?> w3-padding w3-border-top w3-white w3-hover-grey">
            <a name="class-<?=$class['id']?>" class="">
                <i class="fa fa-angle-down w3-margin-right w3-right"></i>
                <span class="w3-margin-left"><?=$class['name']?></span>
            </a>
        </div>

        <div data-loaded="0" data-class="<?=$class['id']?>" class="class-subjects w3-container w3-white w3-padding w3-margin-bottom">
            
        </div>

    <?php endforeach;?>
</div>

<script>
var selected_class = '<?=htmlspecialchars($selected_class)?>';
var selected_subject = '<?=htmlspecialchars($selected_subject)?>';
const load_subjects_url = '<?=site_url('subject/load_json/')?>';
const load_units_url = '<?=site_url('unit/by_subject_json/')?>';
const unit_link_replacer = '<?php $unit_link_replacer = '{@unitid}'; echo $unit_link_replacer;?>';
const unit_link = '<?=site_url_filter('unit/'.$unit_link_replacer);?>';
</script>
<?=jquery()?>
<?=helper_js()?>
<script src="<?=base_url('assets/js/branch-navigator.js')?>"></script>
