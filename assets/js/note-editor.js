$(function(){
    $('[data-toggle="datepicker"]').datepicker({
        format:'yyyy-mm-dd',
        autoHide:true,
        startDate:'2006/12/19',
        endDate:new Date()
    });
});
reposition();
//
const txt={
    unit:$('#unit-text'),
    teacher:$('#teacher-text')
};
$('#unit-btn').click(function(e){
    const box=new msgbox;
    box.show({
        title:'Select unit',
        easyClose:true,
        body:loading_ui('Loading units...')
    });

    load_units(box);
});
$('#teacher-btn').click(function(e){
    const box=new msgbox;
    box.show({
        title:'Select teacher',
        easyClose:true,
        body:loading_ui('Loading teachers...')
    });

    load_teachers(box);
});

function load_units(box) {
    $.ajax({
        url: load_units_url,
        dataType: 'json',
        success: function (data, status) {
            box.$body.html('');
            if (status == 'success') {
                data.forEach(unit => {
                    box.$body.append($('<a>', {
                        href: 'javascript:void(0)'
                    }).html($('<div>', {
                        class: 'w3-padding w3-hover-grey ' + (unit_id == unit.id.toString() ? ' w3-border w3-border-green' : 'w3-border-bottom'),
                        style: 'text-align:left'
                    }).html($('<i>', {
                        class: 'fa fa-angle-right w3-margin-right'
                    })).append(htmlencode(unit.title) + ' (id: ' + unit.id + ')').click(function (e) {
                        set_unit(unit);
                        box.close();
                    })));
                });
            }
        },
        error: function (xhr) {
            load_options_error(box, load_units);
        }
    });
}

function load_teachers(box) {
    $.ajax({
        url: load_teachers_url,
        dataType: 'json',
        success: function (data, status) {
            box.$body.html('');
            if (status == 'success') {
                data.forEach(teacher => {
                    box.$body.append($('<a>', {
                        href: 'javascript:void(0)'
                    }).html($('<div>', {
                        class: 'w3-padding w3-hover-grey ' + (teacher_id == teacher.id.toString() ? ' w3-border w3-border-green' : 'w3-border-bottom')
                    }).html($('<div>', {
                        class: 'w3-row'
                    }).append($('<div>', {
                        class: 'w3-col l2 m2 s3'
                    }).html($('<img>', {
                        src:teacher.photo,
                        height: 50,
                        class: 'w3-circle'
                    }))).append($('<div>', {
                        class: 'w3-col l10 m10 s9',
                        style: 'text-align:left'
                    }).html($('<div>').text(teacher.name)).append($('<div>', {
                        class: 'w3-small w3-text-grey'
                    }).text(teacher.designation + ' (id: ' + teacher.id + ')')))).click(function (e) {
                        set_teacher(teacher);
                        box.close();
                    })));
                });
            }
        },
        error: function (xhr) {
            load_options_error(box, load_teachers);
        }
    });
}

function load_options_error(box, retry_func) {
    box.$body.html($('<div>',{
        class:'w3-padding'
    }).html($('<i>', {
        class: 'fa fa-exclaimation-triangle w3-margin-right'
    })).append('Failed to load').append($('<button>', {
        class: 'w3-btn w3-white w3-border w3-round w3-margin-left',
        click: function (e) {
            box.$body.html(
                loading_ui()
            );
            retry_func(box);
        }
    }).html($('<i>', {
        class: 'fa fa-refresh w3-margin-right'
    })).append('Try again')));
}

function set_unit(unit){
    $('#unit-text').val(unit.id);
    $('#unitid-holder').text(unit.id);
    $('#unitname-holder').text(unit.title);
    $('#unit-holder').attr('title', unit.title+' (id: '+unit.id+')');

    unit_id=unit.id;
}
function set_teacher(teacher){
    $('#teacher-text').val(teacher.id);
    $('#teacherid-holder').text(teacher.id);
    $('#teachername-holder').text(teacher.name);
    $('#teacher-holder').attr('title', teacher.name+' (id: '+teacher.id+')');

    teacher_id=teacher.id;
}
function reposition(to='#editor-header'){
    return scrollto(to);
}