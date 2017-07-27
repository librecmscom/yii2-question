jQuery(document).ready(function () {
    /*采纳回答为最佳答案*/
    jQuery(".adopt-answer").click(function () {
        var answer_id = jQuery(this).data('answer_id');
        jQuery("#adoptAnswerSubmit").attr('data-answer_id', answer_id);
        jQuery("#answer_quote").html(jQuery(this).data('answer_content'));
    });

    jQuery("#adoptAnswerSubmit").click(function () {
        jQuery.post("/question/answer/adopt", {answerId: jQuery(this).data('answer_id')});
    });

    jQuery(".widget-comments").on('show.bs.collapse', function () {
        load_comments(jQuery(this).data('source_type'),jQuery(this).data('source_id'));
    });

    jQuery(".widget-comments").on('hide.bs.collapse', function () {
        clear_comments(jQuery(this).data('source_type'),jQuery(this).data('source_id'));
    });
});