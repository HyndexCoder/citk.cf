<div class="w3-container w3-margin">
    <center>
    <div class="w3-round w3-border w3-white w3-padding" style="width:100%;max-width:400px;text-align:left;">
        <h3 class="w3-margin-left">Login</h3>

        <?php if (isset($login_error_mesg)):?>

        <div class="w3-panel w3-padding w3-border w3-border-red">
            <?=$login_error_mesg?>: Failed to login
        </div>

        <?php endif;?>

        <?php echo form_open($login_url);?>
            <div class="w3-panel">
                <label>Username <input type="text" name="login_string" autofocus class="w3-input w3-border"></label>
            </div>
            <div class="w3-panel">
                <label>Password <input type="password" name="login_pass" class="w3-input w3-border"></label>
            </div>
            <div class="w3-panel">
                <button class="w3-btn w3-white w3-border w3-round w3-right">Login</button>
            </div>
        </form>
    </div>
    </center>
</div>

