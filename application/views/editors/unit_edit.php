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
    <h3 class="w3-margin-left">Unit editor</h3>

    <?=form_open('unit/'.$form_type)?>
    <input type="hidden" name="id" value="<?=htmlspecialchars($id)?>">

        <table class="w3-table">
            <tr>
                <th>Title</th>
                <td>
                    <input type="text" name="title" value="<?=htmlspecialchars($title)?>" class="w3-input w3-border" style="font-size:30px" autocomplete="off">
                </td>
            </tr>
            <tr>
                <th>Subject</th>
                <td>
                    <select name="subject" class="w3-input w3-border">
                        <?php foreach ($subjects as $s):?>
    <option value="<?=htmlspecialchars($s['code'])?>" <?=($s['code'] == $subject['code']? 'selected': '')?>><?=htmlspecialchars($s['code'].': '.$s['title'])?></option>
                        <?php endforeach;?>

                    </select>
                </td>
            </tr>
            <tr>
                <th>Class</th>
                <td>
                    <select name="class" class="w3-input w3-border">
                        <?php foreach ($classes as $c):?>
    <option value="<?=htmlspecialchars($c['id'])?>" <?=($c['id'] == $class? 'selected':'')?>><?=strip_tags($c['name'])?></option>
                        <?php endforeach;?>

                    </select>
                </td>
            </tr>
            <tr>
                <th>Branches</th>
                <td>
                    <ul class="w3-ul">
                        <li>
                            <label><input id="branch-select-all" type="checkbox" class="w3-margin-right"> Select all</label>
                        </li>

                        <?php foreach ($all_branches as $b):?>
                            <li>
                                <label>
                                    <input class="branch-checkbox" type="checkbox" name="branch-<?=htmlspecialchars($b['code'])?>" <?=(in_array(strtoupper($b['code']), $branch)? 'checked':'')?>> <i class="fa fa-<?=htmlspecialchars($b['icon'])?> w3-margin-left"></i>&nbsp;&nbsp;<?=htmlspecialchars($b['name'])?>
                                </label>
                            </li>
                        <?php endforeach;?>
                    </ul>

                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <a class="w3-hover-text-red w3-margin-left" href="<?=site_url('unit/'.urlencode($id))?>">Cancel</a>
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
    $('#branch-select-all').on('change', function(e){
        $('.branch-checkbox').prop('checked',$(this).prop('checked'))
    });

    $('.branch-checkbox').on('change', function(e){
        check_all_check();
    });
});

function check_all_check(){
    var all_checked=true;
    $('.branch-checkbox').each(function(){
        if(!$(this).prop('checked')){
            all_checked=false;
        }
    });

    $('#branch-select-all').prop('checked',all_checked);
}
</script>