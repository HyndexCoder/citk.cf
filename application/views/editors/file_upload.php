<html>
<head>
    <title>Upload Form</title>
    <?=css_tag('dropzone.css')?>
    <?=script_tag('dropzone.js')?>
    <?=script_tag('clipboard.min.js')?>

    <style>
    *{
    font-family:arial;
    }
    .file-item{
    border:solid 1px grey;
    border-radius: 5px;
    padding: 5px;
    cursor:default;
    width:200px;
    height:200px;
    overflow:auto;
    display:inline-block;
    text-align:center;
    margin-top:3px;
    }
    .file-item:hover{
    background-color: #eee;
    border-color: #bbb;
    }
    </style>
</head>
<body>
<h5>Click on any file to copy it&apos;s link</h5>

<form action="<?=site_url('upload/do')?>" method="post" enctype="multitype/form-data" class="dropzone" id="uploader">
<input type="hidden" name="note_id" value="<?=htmlspecialchars($note_id)?>">
</form>

<div style="width:100%; padding:5px;text-align:center;">
<?php foreach($files as $file):?>
    <div class="file-item" data-clipboard-text="<?=htmlspecialchars($file['link'])?>" title="Click to copy link">
        <div>
            <?php if ($file['is_image']):?>
                <img src="<?=htmlspecialchars($file['link'])?>" alt="<?=htmlspecialchars($file['name'])?>" height="150">
                <br>
            <?php endif;?>
            <?=htmlspecialchars($file['name'])?>
        </div>
    </div>
<?php endforeach;?>
</div>

<div id="tpl" style="display:none;">
    <div class="dz-preview dz-file-preview" style="border:solid 1px green;min-width: 100px;" title="Click to get link">
        <img data-dz-thumbnail />
    </div>
</div>



<script>
Dropzone.autoDiscover = false;

const myDropzone = new Dropzone("#uploader", {
    resizeHeight: 1280,
    resizeWidth: 1280,
});

myDropzone.on("success", function(file, data) {
    data = JSON.parse(data);
    
    if(data.success){
        console.log('Successfully uploaded to '+data.url);

        file.previewElement.addEventListener("click", function() {
            promptCopy(data.url);
        });
    }
    else{
        alert('Failed to upload: '+ data.error);
    }
});

//Clipboard
const clp = new Clipboard('.file-item');
clp.on('success', function(e){
    var els = document.getElementsByClassName('file-item');
    for(var i=0;i<els.length;i++){
        els[i].style.backgroundColor='white';
    }

    e.trigger.style.backgroundColor='yellow';
});

function promptCopy(link=''){
    prompt('Press Ctrl+C to copy the link', link);
}

</script>

</body>
</html>