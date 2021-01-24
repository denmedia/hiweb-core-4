jQuery(document).ready(function($){

    $('body').on('field_init', '.hiweb-field_checkbox[data-field-init="0"]', function(){
        let id = 'field_checkbox_' + Math.ceil( Math.random() * 999 );
        let $input_wrap = $(this);
        $input_wrap.find('input').attr('id', id);
        $input_wrap.find('label').attr('for', id);
    });

});