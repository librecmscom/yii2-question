$(function () {
    $('.js-vote').on('click', 'a.js-vote-up, a.js-vote-down', function (e) {
        $.post($(this).attr('href'), $(this).data('post'), function (response) {
            if (response.status) {
                $('.js-vote-up').addClass('question-vote-up-disabled');
                $('.js-vote-down').addClass('question-vote-up-disabled');
                $('.question-vote-count').html(response.votes);
            }
        });
        e.preventDefault();
    });
    $('.js-favorite').on('click', 'a.js-favorite-link', function (e) {
        $.post($(this).attr('href'), $(this).data('post'), function (response) {
            if (response.status) {
                if (response.isFavorite) {
                    $('.js-favorite').addClass('question-favorite-active');
                    $('.js-favorite span').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
                } else {
                    $('.js-favorite').removeClass('question-favorite-active');
                    $('.js-favorite span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                }

                $('.question-favorite-count').html(response.favorites);
            }
        });
        e.preventDefault();
    });
    $('.js-answer-correct').on('click', 'a.js-answer-correct-link', function (e) {
        var _this = $(this);
        $.post($(this).attr('href'), $(this).data('post'), function (response) {
            if (response.status == true) {
                if (response.isCorrect) {
                    _this.removeClass('btn-default');
                    _this.addClass('btn-warning');
                } else {
                    _this.addClass('btn-default');
                    _this.removeClass('btn-warning');
                }
            }
        });
        e.preventDefault();
    });
    $('.js-answer-vote').on('click', 'a.js-answer-vote-up', function (e) {
        var _this = $(this);
        $.post($(this).attr('href'), $(this).data('post'), function (response) {
            if (response.status) {
                _this.addClass('disabled');
                _this.html("<span class=\"glyphicon glyphicon-heart\"></span> " + response.votes);
            }
        });
        e.preventDefault();
    });

});