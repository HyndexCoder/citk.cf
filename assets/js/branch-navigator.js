$(function(){
    $('.class-accordion').click(function(e){
        var el=$(this),subs=el.next('div.class-subjects');

        subs.toggle();
        if(subs.attr('data-loaded')!='1'){
            subs.html(loading_ui('Loading subjects...'));
            load_subjects(subs);
        }

        header_toggle(el);
    });

    $('.class-accordion.selected').click();
    scrollto('.class-accordion.selected');
});

function load_subjects(el){
    if(el.attr('data-loaded') != '1'){
        $.ajax({
            url:load_subjects_url+encodeURI(el.attr('data-class')),
            dataType:'json',
            success: function(data,status){
                if(status=='success'){
                    el.attr('data-loaded','1');

                    if(data.length < 1){
                        el.html(
                            $('<span>',{
                                class:'w3-text-grey w3-margin-left w3-small'
                            }).text('No subjects added till now!')
                        );
                        return;
                    }
                    else el.html('');

                    var wrap = el.html($('<div>', {
                        class:''
                    }).html(
                        $('<p>',{
                            class:'w3-margin-left w3-text-grey'
                        }).text('Subjects:')
                    )).find('div');

                    var selected_subject_header;
                    data.forEach(function(subject, i){
                        wrap.append(
                            () => {
                                var sub_header = $('<div>',{
                                    class:'subject-accordion w3-padding w3-hover-grey w3-border-bottom',
                                    style:'',
                                    click:function(e){
                                        var el=$(this),units=el.next('div.unit-box');
                                        units.toggle();
                                        if(el.attr('data-expanded') == '0'){
                                            load_units(units);
                                        }
                                        header_toggle(el);
                                    }
                                }).attr('data-expanded','0').html(
                                    $('<i>',{
                                        class:'fa fa-angle-down w3-right'
                                    })
                                ).append(
                                    $('<a>',{
                                        name:'subject-'+subject.code,
                                    }).text((i+1)+'. '+subject.title+' ('+subject.code.toUpperCase()+')')
                                );

                                if(subject.code.toLowerCase()==selected_subject.toLowerCase() && selected_class.length>0){
                                    selected_subject_header=sub_header;
                                    selected_subject='';
                                }

                                return sub_header;
                            }
                        ).append(
                            $('<div>',{
                                class:'unit-box w3-padding w3-margin-bottom',
                            }).attr('data-loaded','0').attr('data-subject',subject.code).html(loading_ui('Loading units...'))
                        )
                    });

                    if(typeof selected_subject_header=='object'){
                        if(typeof selected_subject_header.click=='function'){
                            selected_subject_header.click();
                            scrollto(selected_subject_header);
                        }
                    }
                }
                else{
                    el.html('No subjects!');
                }
            },
            error:function(){
                show_failure(el,load_subjects,'Unable to load subjects','Loading subjects');
            }
        });
    }
}

function load_units(el){
    if(el.attr('data-loaded')=='0'){
        $.ajax({
            url:load_units_url+el.attr('data-subject'),
            dataType:'json',
            success:function(data,status){
                if(status=='success'){
                    el.html('');
                    if(data.subject.description.length>0) el.html(
                        $('<blockquote>',{
                            style:'font-style:italic'
                        }).append(
                            $('<sup>').html($('<i>',{
                                class:'fa fa-quote-left w3-margin-right'
                            }))
                        ).append(htmlencode(data.subject.description)).append(
                            $('<sup>').html($('<i>',{
                                class:'fa fa-quote-right w3-margin-left'
                            }))
                        )
                    );

                    if(data.units.length<1){
                        el.append(
                            $('<span>',{
                                class:'w3-text-grey w3-margin-left w3-small'
                            }).text('No units added till now!')
                        );
                        return;
                    }

                    var list=el.append(
                        $('<p>',{
                            class:'w3-margin-left w3-text-grey'
                        }).text('Units:')
                    ).append('<ol>').find('ol');

                    data.units.forEach(function(unit,i){
                        list.append(
                            $('<li>',{
                                class:'w3-margin-top'
                            }).append(
                                $('<a>',{
                                    href: unit_link.replace(unit_link_replacer,unit.id),
                                    class:'w3-text-blue'
                                }).text(unit.title)
                            )
                        );
                    });
                }
            },
            error:function(){
                show_failure(el,load_units,'Unable to load units','Loading units');
            }
        });
    }
}

function show_failure(el,try_again=null,failure_text='Unable to load',loading_text='Loading...'){
    el.html(
        $('<p>', {
            class:'w3-padding w3-text-red'
        }).append(
            $('<i>',{
                class:'fa fa-exclamation-triangle w3-padding'
            })
        ).append(failure_text).append(
            $('<button>', {
                class:'w3-margin w3-btn w3-white w3-border w3-round'
            }).click(function(e){
                el.html(
                    loading_ui(loading_text)
                );
                
                if(typeof try_again!='function'){
                    try_again=function(){};
                }
                try_again(el);
            }).append(
                $('<i>', {
                    class:'fa fa-refresh w3-margin-right'
                })
            ).append('Try again')
        )
    )
}

function header_toggle(el){
    if(el.attr('data-expanded') == '0'){
        el.attr('data-expanded','1').addClass('bold-text').removeClass('w3-hover-grey').find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
    }
    else{
        el.attr('data-expanded','0').removeClass('bold-text').addClass('w3-hover-grey').find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
}
