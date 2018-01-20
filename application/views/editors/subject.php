<style>
th{
    text-align:right;
}
td{
    text-align:left;
}
</style>

<div class="w3-content">
    <div class="w3-padding w3-border w3-round w3-white w3-margin-top">
        <h3 class="w3-margin-left">Subject editor</h3>
        <?=form_open('subject/'.($new? 'new':'edit'))?>
        <input type="hidden" name="id" value="<?=htmlspecialchars($id)?>">
            <table class="w3-table">
                <tr>
                    <th>Title</th>
                    <td>
                        <input type="text" name="title" class="w3-input w3-border" value="<?=htmlspecialchars($title)?>" style="font-size:30px" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td>
                        <input type="text" id="code-text" name="code" class="w3-input w3-border" value="<?= htmlspecialchars($code)?>" maxlength="6" autocomplete="off">
                        
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <textarea name="description" class="w3-input w3-border" rows="5"><?=htmlspecialchars($description)?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>Classes</th>
                    <td>
                        <ul class="w3-ul">
                            <li>
                                <label><input id="class-select-all" type="checkbox" class="w3-margin-right"> Select all</label>
                            </li>
                        <?php foreach ($all_classes as $class):?>
                            <li>
                                <label>
                                    <input type="checkbox" name="class-<?=$class['id']?>" class="class-checkbox w3-margin-right" <?=(in_array($class['id'], $classes)? 'checked':'')?>>
                                    <?=$class['name']?>

                                </label>
                            </li>

                        <?php endforeach;?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                            <i class="fa fa-save"></i> Save
                        </button>
                        <?php
                        $cancel_link = add_url_query(site_url_filter($new? '': 'branch/'.CC_BRANCH), array('subject'=>$code, 'class'=>CC_CLASS));
                        ?>
                        <a href="<?=$cancel_link?>" class="w3-margin-left w3-hover-text-red">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?=jquery()?>

<script>
$(function(){
    check_all_check();
    $('#class-select-all').on('change', function(e){
        $('.class-checkbox').prop('checked',$(this).prop('checked'))
    });

    $('.class-checkbox').on('change', function(e){
        check_all_check();
    });

    $('#code-text').change(function(e){
        $(this).val($(this).val().toUpperCase());
    });
});

function check_all_check(){
    var all_checked=true;
    $('.class-checkbox').each(function(){
        if(!$(this).prop('checked')){
            all_checked=false;
        }
    });

    $('#class-select-all').prop('checked',all_checked);
}
</script>
