jQuery(document).ready(function($){

    let comments_make_pagination = function () {
        if ($(document).pagination) {
            $('.comments-pagination').pagination({
                items: $('.comments-list').find('.comment, .comment-answer').length,
                itemsOnPage: 10,
                prevText: '',
                nextText: '',
                cssStyle: 'light-theme',
                onPageClick: function (pageNumber, event) {
                    $('.comments-page').hide();
                    $('.comments-page[data-page="' + pageNumber + '"]').show();
                }
            });
        }
    };
    comments_make_pagination();

    let comments_answer_set = function (comment_id) {
        comment_id = parseInt(comment_id);
        if (isNaN(comment_id) || comment_id === 0) {
            //UNSET
            $comments_form.find('[data-reply-id]').attr('data-reply-id', 0);
            $comments_form.find('input[name="comment_parent"]').val(0);
        } else {
            //SET
            let comment_name = $('[data-comment-id="' + comment_id + '"] .name').text();
            let comment_text_truncate = $('[data-comment-id="' + comment_id + '"] .text').text().trim().substr(0, 200);
            $comments_form.find('input[name="comment_parent"]').val(comment_id);
            $comments_form.find('[data-comment-answer-name]').html(comment_name + ': ' + comment_text_truncate + '...');
            $comments_form.find('[data-reply-id]').attr('data-reply-id', comment_id);
        }
    };
    let comments_answer = function ($click_element) {
        $click_element = $($click_element);
        //set answer id
        comments_answer_set($click_element.closest('[data-comment-id]').attr('data-comment-id'));
        //scroll to form
        $([document.documentElement, document.body]).animate({
            scrollTop: $comments_form.offset().top - 150
        }, 1200);
    };
    let comments_like_loading = function ($click_element, status) {
        $click_element = $($click_element);
        if ($click_element.length === 0) return;
        if (status === 0) {
            $click_element.closest('[data-comment-id]').find('.loading').stop().animate({opacity: 1}, 500, function () {
                $(this).css('visibility', 'hidden')
            });
        } else {
            $click_element.closest('[data-comment-id]').find('.loading').css('visibility', 'visible').animate({opacity: 1});
        }
    };
    let comments_like_click = function ($click_element) {
        $click_element = $($click_element);
        comments_like_loading($click_element);
        let like = $click_element.attr('data-click') === 'dislike' ? -1 : 1;
        let comment_id = $click_element.closest('[data-comment-id').attr('data-comment-id');
        $.ajax({
            url: '/wp-json/zorbasmedia/like-comment',
            type: 'post',
            dataType: 'json',
            data: {comment_id: comment_id, like: like},
            success: function (response) {
                comments_like_loading($click_element, 0);
                if (response.hasOwnProperty('success')) {
                    if (response.success && response.hasOwnProperty('likes')) {
                        $click_element.closest('.like').find('.count').html(response.likes);
                    } else {
                        console.warn(response);
                    }
                } else {
                    console.warn(response);
                }
            },
            error: function (response) {
                console.error(response);
                comments_like_loading($click_element, 0);
            }
        });
    };
    $('.single-comments, #comments-form').on('click tap', '[data-click]', function (e) {
        e.preventDefault();
        let action = $(this).attr('data-click');
        switch (action) {
            case 'like':
                comments_like_click(this);
                break;
            case 'dislike':
                comments_like_click(this);
                break;
            case 'comment-reply':
                comments_answer(this);
                break;
            case 'comment-reply-disable':
                comments_answer_set(0);
                break;
            default:
                console.warn('неизвестный актион в блоке комментариев [' + action + ']');
                break;
        }
    });

    var $comments_form = $('#comments-form');
    if ($comments_form.length > 0) {

        var recaptcha_get_token = function ($form, success_fn) {
            if(typeof grecaptcha === 'undefined'){
                console.error('Объект [grecaptcha] не подключен в теле сайта. Подключите удаленный JS файл [https://www.google.com/recaptcha/api.js?render={recaptcha public key}]');
            } else {
                var $input_token = $form.find('input[name="recaptcha-token"]');
                if ($input_token.length > 0) {
                    grecaptcha.execute($input_token.data('key')).then(function (token) {
                        $input_token.val(token);
                        if (typeof success_fn === 'function') success_fn($form, $input_token);
                        //$input_token.closest('form').find('[type="submit"]').removeAttr('disabled');
                    });
                }
            }
        };

        $comments_form.on('submit', function (e) {
            e.preventDefault();
            $('.form-wrap').attr('data-status', 'wait');

            recaptcha_get_token($comments_form, function(){
                $comments_form.ajaxSubmit({
                    success: function (response) {
                        if (response.hasOwnProperty('success')) {
                            if (response.success) {
                                $('.form-wrap').attr('data-status', 'success');
                                $('.single-comments-wrap').html($(response.comments_html).html());
                                //$('.single-comments-wrap').find('.comment').eq(0).opacity(0).animate({opacity: 1}, 500);
                                comments_answer_set(0);
                                $comments_form[0].reset();
                            } else {
                                $('.form-wrap').attr('data-status', 'error');
                            }
                        } else {
                            $('.form-wrap').attr('data-status', 'error');
                        }
                        setTimeout(function () {
                            $('.form-wrap').attr('data-status', '');
                        }, 3000);
                    },
                    error: function (response) {
                        $('.form-wrap').attr('data-status', 'error');
                        setTimeout(function () {
                            $('.form-wrap').attr('data-status', '');
                        }, 3000);
                    }
                });
            });

        });
    }

});