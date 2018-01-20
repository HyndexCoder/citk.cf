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
    <h3 class="w3-margin-left">Add new user</h3>

    <?=form_open('user/new')?>

    <?php
    $errors = validation_errors();
    
    if (!empty($errors)):?>
        <div class="w3-padding w3-margin w3-border w3-border-red">
            <h4>Errors occured while submitting the form</h4>
            <ul class="w3-ul">
            <?=$errors;?>

            </ul>
        </div>
    <?php endif;?>

        <table class="w3-table">
            <tr>
                <th>Username</th>
                <td>
                    <input type="text" name="username" value="<?=set_value('username')?>" class="w3-input w3-border" style="font-size:30px" autocomplete="off">
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email" value="<?=set_value('email')?>" class="w3-input w3-border" autocomplete="off">
                </td>
            </tr>
            <tr>
                <th>Password</th>
                <td>
                    <input type="text" name="password" value="<?=set_value('password')?>" class="w3-input w3-border" autocomplete="off">
                </td>
            </tr>

            <tr>
                <th>User level</th>
                <td>
                    <select name="auth_level" class="w3-input w3-border">
                        <option value="" <?=set_select('auth_level','',true)?>>-SELECT-</option>
                        <option value="1" <?=set_select('auth_level','1')?>>Subscriber (can only read)</option>
                        <option value="6" <?=set_select('auth_level','6')?>>Editor (can write)</option>
                        <option value="9" <?=set_select('auth_level','9')?>>Admin (can manage almost everything)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <a class="w3-hover-text-red w3-margin-left" href="<?=site_url()?>">Cancel</a>
                </td>
            </tr>
        </table>
        
    </form>
    </div>
</div>
