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

    jQuery("#appendRewardSubmit").click(function(){
        var user_total_conis = '14';
        var reward = jQuery("#question_coins").val();

        if(reward>parseInt(user_total_conis)){
            jQuery("#rewardAlert").attr('class','alert alert-warning');
            jQuery("#rewardAlert").html('积分数不能大于'+user_total_conis);
        }else{
            jQuery("#rewardAlert").empty();
            jQuery("#rewardAlert").attr('class','');
            jQuery("#rewardForm").submit();
        }
    });
});