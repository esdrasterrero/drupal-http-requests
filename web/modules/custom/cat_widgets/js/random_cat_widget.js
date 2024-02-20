// random_cat_widget.js

(function ($) {
  Drupal.behaviors.randomCatWidget = {
    attach: function (context, settings) {
      $('#random-cat-widget').once('random-cat-widget', function () {
        $(this).on('click', '.vote-up, .vote-down', function () {
          var $button = $(this);
          var isVoteUp = $button.hasClass('vote-up');
          var $widget = $button.closest('#random-cat-widget');
          var catId = $widget.data('cat-id');
          var voteType = isVoteUp ? 1 : -1;

          // Make AJAX call to submit vote.
          $.ajax({
            url: 'https://api.thecatapi.com/v1/votes', // Replace with actual vote endpoint.
            method: 'POST',
            data: {
              image_id: catId,
              value: voteType,
            },
            success: function (response) {
              // Handle success response.
            },
            error: function (xhr, status, error) {
              // Handle error.
            }
          });
        });
      });
    }
  };
})(jQuery);
