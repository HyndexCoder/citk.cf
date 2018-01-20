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
        <h3 class="w3-margin-left">Teacher editor</h3>

        <?=form_open_multipart('teacher/'.($new? 'new':'edit'))?>

        <input type="hidden" name="id" value="<?=htmlspecialchars($id)?>">

        <table class="w3-table">
            <tr>
                <th>Avatar</th>
                <td>
                    <div class="w3-row">
                        <div class="w3-col l2 m3 s6">
                            <img src="<?=htmlspecialchars($photo)?>" alt="Prof. <?=htmlspecialchars($name)?>"  class="w3-circle" style="height:106px;width:106px">
                        </div>
                        <div class="w3-col l10 m9 s6">
                            <div class="w3-padding">
                                <input type="file" name="photo" class="w3-input w3-border">
                            </div>
                            <div class="w3-padding">
                                <span class="w3-text-grey">or paste a complete link</span>
                                <input type="text" name="photo_link" class="w3-input w3-border" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <td>
                    <input type="text" name="name" value="<?=htmlspecialchars($name)?>" class="w3-input w3-border" style="font-size:30px" autocomplete="off" maxlength="40">
                </td>
            </tr>
            <tr>
                <th>Designation</th>
                <td>
                    <input type="text" name="designation" value="<?=htmlspecialchars($designation)?>" class="w3-input w3-border" autocomplete="off" maxlength="50">
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email" value="<?=htmlspecialchars($email)?>" class="w3-input w3-border" autocomplete="off" maxlength="50">
                </td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>
                    <input type="text" name="phone" value="<?=htmlspecialchars($phone)?>" class="w3-input w3-border" autocomplete="off" maxlength="15">
                </td>
            </tr>
            <tr>
                <th>Tenure</th>
                <td>
                    <input type="text" name="tenure" value="<?=htmlspecialchars($tenure)?>" class="w3-input w3-border" autocomplete="off" maxlength="25">
                </td>
            </tr>

            <tr>
                <th></th>
                <td>
                    <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <a class="w3-hover-text-red w3-margin-left" href="<?=site_url($new? '/':'teacher/'.$id)?>">Cancel</a>
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>