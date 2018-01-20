<div class="w3-content w3-padding">
    <h3>Edit email for <i><?=htmlspecialchars($username)?></i></h3>

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

    <?=form_open('user/edit/email')?>

        <input type="hidden" name="user_id" value="<?=$user_id?>">
        <label>Email address
            <input class="w3-input w3-border" type="text" name="email" value="<?=$save_req? set_value('email'): htmlspecialchars($email)?>" autocomplete="off">
        </label>
        <div class="w3-margin">
            <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                <i class="fa fa-save"></i> Save
            </button>
            <a class="w3-hover-text-red w3-margin-left" href="javascript:history.go(-1)">Cancel</a>
        </div>
    </form>
</div>