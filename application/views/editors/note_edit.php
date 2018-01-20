<style>
label{
    color:#666;
}
.dashed-border{
    border:solid 1px #aaa;
    border-style:dashed;
}
.details-input{
    max-height:40px;
    overflow:hidden;
}
</style>
<?=css_import('datepicker.min.css')?>

<div class="w3-container">
    <?=form_open('note/'.$form_type)?>
    <input type="hidden" name="id" value="<?=htmlspecialchars($id)?>">
    <input id="unit-text" type="hidden" name="unit" value="<?=htmlspecialchars($unit['id'])?>">
    <input id="teacher-text" type="hidden" name="teacher" value="<?=htmlspecialchars($teacher['id'])?>">

    <div class="w3-row" id="editor-header">
        <div class="w3-col l6 m6 s6">
            <h3><i class="fa fa-sticky-note-o w3-margin-right"></i>Notes editor
                <i class="fa fa-arrows-alt w3-margin-left w3-text-grey w3-hover-text-black" title="Re-position the edtior" onclick="reposition()" style="cursor:pointer"></i>
            </h3>
        </div>
        <div class="w3-col l6 m6 s6">
            <div class="w3-right w3-padding w3-center">
                <button class="w3-btn w3-white w3-border w3-border-<?=CC_COLOR?> w3-round">
                    <i class="fa fa-save"></i> Save
                </button><br>
                <a class="w3-hover-text-red" href="<?=site_url('note/'.urlencode(empty($id)? '..':$id))?>">Cancel</a>
            </div>
        </div>
    </div>

    <div class="w3-row">
        <div class="w3-col l4 m6 s12">
            <label>Title
                <input class="w3-input w3-border bold-text details-input" type="text" name="title" value="<?=htmlspecialchars($title)?>" autocomplete="off" maxlength="30">
            </label>
        </div>
        <div class="w3-col l8 m6 s12">
            <div class="w3-row">
                <div class="w3-col l4 m4 s4">
                    <label>Unit
                        <div id="unit-holder" class="w3-input w3-white w3-border details-input" title="<?=htmlspecialchars($unit['title']).' (id:'.$unit['id'].')'?>">
                            <span class="w3-tiny w3-text-grey">id <span id="unitid-holder"><?=$unit['id']?></span></span>
                            <span id="unitname-holder"><?=htmlspecialchars($unit['title'])?></span>

                            <div class="w3-right">
                                <button id="unit-btn" class="w3-white w3-border w3-border-white" type="button" title="Change unit">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="w3-col l4 m4 s4">
                    <label>Teacher
                        <div id="teacher-holder" class="w3-input w3-white w3-border details-input" title="<?=htmlspecialchars($teacher['name']).' (id:'.$teacher['id'].')'?>">
                            <div class="w3-row">
                                <div class="w3-col l10 m10 s10">
                                    <span class="w3-tiny w3-text-grey">id <span id="teacherid-holder"><?=$teacher['id']?></span></span>
                                    <span id="teachername-holder"><?=htmlspecialchars($teacher['name'])?></span>
                                </div>
                                <div class="w3-col l2 m2 s2">
                                    <div class="w3-right">
                                        <button id="teacher-btn" class="w3-white w3-border w3-border-white" type="button" title="Change teacher">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="w3-col l4 m4 s4">
                    <label for="datepicker">Date</label>
                    <div class="w3-row w3-white w3-border">
                        <div class="w3-col l4 m4 s4" style="overflow:hidden">
                            <input id="datepicker" class="w3-padding details-input w3-border w3-border-white" type="text" name="date" data-toggle="datepicker" value="<?=htmlspecialchars($date)?>" autocomplete="off">
                        </div>
                        <div class="w3-col l8 m8 s8 w3-padding">
                            <label>
                                <span style="vertical-align:middle" class="w3-margin-right">Real date <span style="border-bottom:dotted 1px #555" title="If the note was given on this date, then check it, otherwise uncheck it." class="w3-small">(?)</span></span>
                                <input type="checkbox" name="date_is_auto" <?=($date_is_auto == 1? '':'checked')?> style="vertical-align:middle">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="w3-row" style="overflow:auto">
        <div class="w3-col l9 m7 s12">
            <textarea id="content-html" type="text" name="content" style="height:600px;width:1000px;"><?=htmlspecialchars($content)?></textarea>
        </div>
        <div class="w3-col l3 m5 s12 w3-white w3-border-left">
            <iframe src="<?=site_url('upload/form/'.$id)?>" frameborder="0" style="height:530px;width:100%;"></iframe>
        </div>
    </div>
    <div>

    </div>
    </form>
</div>

<?=jquery()?>
<?=helper_js()?>
<?=script_tag('_ckeditor/ckeditor.js')?>
<?=script_tag('datepicker.min.js')?>
<?=script_tag('msgbox.js')?>

<script>
CKEDITOR.config.allowedContent = true;
//CKEDITOR.config.extraPlugins = 'lineutils';
CKEDITOR.config.extraPlugins = 'youtube';
CKEDITOR.config.mathJaxLib = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML';
//CKEDITOR.config.extraPlugins = 'googledocs';
CKEDITOR.replace('content-html');

const load_units_url='<?=site_url('unit/all_json')?>';
const load_teachers_url='<?=site_url('teacher/all_json')?>';
//Preloaded units and teacher
var unit_id='<?=htmlspecialchars($unit['id'])?>',teacher_id='<?=htmlspecialchars($teacher['id'])?>';
</script>
<?=script_tag('note-editor.js')?>
