/*
* JavaScript to updade the database when seen or want to see buttons are clicked.
* -vkolova
*/

(function($) {
//  console.log(vk_select_params.vk_ajax_url);
  $( "#have_seen" ).click(function() {
      $.ajax({
					url: vk_select_params.vk_ajax_url,
					data: {
            'action': 'update_seen',
            'security': vk_select_params.ajax_nonce,
            'user_id': $( '#user_id' ).val(),
            'play_id': $( '#play_id' ).val()
          },
					type: 'POST',
					success: function( response ) {
console.log(response);
					}
				});
    });
    $( "#want_to_see" ).click(function() {
        $.ajax({
  					url: vk_select_params.vk_ajax_url,
  					data: {
              'action': 'want_to_see',
              'security': vk_select_params.ajax_nonce,
              'user_id': $( '#user_id' ).val(),
              'play_id': $( '#play_id' ).val()
            },
  					type: 'POST',
  					success: function( response ) {
  console.log(response);
  					}
  				});
      });
})(jQuery);
