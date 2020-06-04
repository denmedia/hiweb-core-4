jQuery(document).ready(function ($) {

    let $posts_list_table = $('.wp-admin .wp-list-table [data-hiweb-duplicate]');
    if ($posts_list_table.length > 0) {
        let source_url = window.location.href.replace(window.location.search, '');
        let destination_query = window.location.search.replace(/&hiweb-post-duplicate=[\d]+/g, '').replace(/&_wpnonce=[^&]+/g, '');
        window.history.pushState("", "", destination_query);
        $('link[rel="canonical"]').each(function () {
            $(this).attr('href', $(this).attr('href').replace(/&hiweb-post-duplicate=[\d]+/g, '').replace(/&_wpnonce=[^&]+/g, ''));
            $(this).attr('href', $(this).attr('href').replace(/&#038;hiweb-post-duplicate=[\d]+/g, '').replace(/&#038;_wpnonce=[^&]+/g, ''));
            $(this).attr('href', $(this).attr('href').replace(/%26hiweb-post-duplicate=[\d]+/g, '').replace(/%26_wpnonce=[^&]+/g, ''));
        });
        $('a:not([data-hiweb-duplicate])').each(function () {
            $(this).attr('href', $(this).attr('href').replace(/&hiweb-post-duplicate=[\d]+/g, '').replace(/&_wpnonce=[^&]+/g, ''));
            $(this).attr('href', $(this).attr('href').replace(/&#038;hiweb-post-duplicate=[\d]+/g, '').replace(/&#038;_wpnonce=[^&]+/g, ''));
            $(this).attr('href', $(this).attr('href').replace(/%26hiweb-post-duplicate=[\d]+/g, '').replace(/%26_wpnonce=[^&]+/g, ''));
        });
        $('input').each(function () {
            $(this).val($(this).val().replace(/&hiweb-post-duplicate=[\d]+/g, '').replace(/&_wpnonce=[^&]+/g, ''));
            $(this).val($(this).val().replace(/&#038;hiweb-post-duplicate=[\d]+/g, '').replace(/&#038;_wpnonce=[^&]+/g, ''));
            $(this).val($(this).val().replace(/%26hiweb-post-duplicate=[\d]+/g, '').replace(/%26_wpnonce=[^&]+/g, ''));
        });
        // $posts_list_table.on('click', '[data-hiweb-duplicate]', function(e){
        //     e.preventDefault();
        //     $.ajax({
        //         url: ajaxurl + '?action=hiweb-post-duplicator',
        //         type: 'post',
        //         dataType: 'json',
        //         data: {post_id: $(this).attr('data-hiweb-duplicate')},
        //         success: function(response){
        //             if(response.hasOwnProperty('success')) {
        //                 const c = document.documentElement.scrollTop || document.body.scrollTop;
        //                 if (c > 0) {
        //                     window.requestAnimationFrame(scrollToTop);
        //                     window.scrollTo(0, c - c / 8);
        //                 }
        //                 if(response.success) {
        //
        //                 } else {
        //
        //                 }
        //             }
        //         },
        //         error: function(response){
        //             alert('An error occurred during the execution of the task...see console form more info.');
        //         }
        //     });
        // });
    }

});