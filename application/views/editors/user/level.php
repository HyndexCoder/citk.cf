<div class="w3-content w3-padding">
    <h3>Edit user level for <i><?=htmlspecialchars($username)?></i></h3>

    <?php
    $error = validation_errors();
    if (!empty($error)):?>

        <div class="w3-padding w3-margin w3-border w3-border-red">
            <h4>Some errors occured</h4>
            <ul class="w3-ul">
            <?=$error?>
            </ul>
        </div>

    <?php endif;?>

    <?=form_open('user/edit/level')?>

        <input type="hidden" name="user_id" value="<?=$user_id?>">
        <label>User level
            <select name="auth_level" class="w3-input w3-border">
                <option value="">-SELECT-</option>
                <option value="1" <?=$auth_level==1? 'selected':''?>>Subscriber (can only read)</option>
                <option value="6" <?=$auth_level==6? 'selected':''?>>Editor (can write)</option>
                <option value="9" <?=$auth_level==9? 'selected':''?>>Admin (can manage almost everything)</option>
            </select>
        </label>
        <div class="w3-margin">
            <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                <i class="fa fa-save"></i> Save
            </button>
            <a class="w3-hover-text-red w3-margin-left" href="javascript:history.go(-1)">Cancel</a>
        </div>
    </form>
</div>