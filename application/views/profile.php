<div class="w3-content w3-padding w3-white w3-border w3-center w3-margin-top w3-round" style="max-width:400px;">
    <h3>
        <i class="fa fa-user"></i> 
        <?=htmlspecialchars($user['username'])?>

        <?php if ($editable):?>
            <a title="Edit username" class="w3-small" href="<?=site_url('user/edit/username/'.$user['user_id'])?>"><i class="fa fa-edit"></i></a>
        <?php endif;?>

    </h3>
    <p>
        <?php if ($user['banned']):?>
            <span class="bold-text w3-text-red"><i class="fa fa-ban"></i> BANNED</span>
        <?php endif;?>

    </p>
    <p>
        <i class="fa fa-envelope"></i> 
        <?=htmlspecialchars($user['email'])?>

        <?php if ($editable):?>
            <a title="Edit email address" class="w3-small" href="<?=site_url('user/edit/email/'.$user['user_id'])?>"><i class="fa fa-edit"></i></a>
        <?php endif;?>

    </p>
    <p>
    <?php if ($editable):?>
            <a class="w3-btn w3-border w3-round" href="<?=site_url('user/edit/password/'.$user['user_id'])?>"><i class="fa fa-edit"></i> Change password</a>
        <?php endif;?>

    </p>
    <p>
    <?php if ($editable):?>
        <?php if ($auth_level >= 9):?>
            <a class="w3-btn w3-border w3-round" href="<?=site_url('user/edit/level/'.$user['user_id'])?>"><i class="fa fa-edit"></i> Change user level (<?=$user['auth_level']?>)</a>
        <?php endif;?>
    <?php endif;?>

    </p>
    <p>
    <?php if ($editable):?>
        <?php if ($auth_level >= 9):?>
            <?=form_open('user/edit/ban', array('onsubmit' => 'return confirm(\'Do you really want to perform this action?\')'))?>

                <input type="hidden" name="user_id" value="<?=htmlspecialchars($user['user_id'])?>">
                <input type="hidden" name="banned" value="<?=$user['banned']?'0':'1'?>">

                <button class="w3-btn w3-border w3-round">
                    <i class="fa fa-ban"></i> <?=$user['banned']? 'Lift ':''?> Ban
                </button>

            </form>
        <?php endif;?>
    <?php endif;?>

    </p>
</div>
