/*
* JavaScript to updade the database when seen or want to see buttons are clicked.
* -vkolova
*/

(function($) {

  $( "#have_seen" ).live( "click", function() {
    $( "#have_seen" ).empty().append('<img src="' + vk_select_params.plugin_url + '/woocommerce/assets/images/loading.gif"> запазване...');

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
          switch ( response ) {
          	case '0':
          		$( ".btn-group" ).empty().append( '<button type="button" class="btn btn-default" data-toggle="dropdown" id="want_to_see">			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  Искам да я гледам		</button>	  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">	    <span class="caret"></span>	    <span class="sr-only">Toggle Dropdown</span>	  </button>	  <ul class="dropdown-menu">	    <li><a id="have_seen">Гледах я</a></li>	  </ul>' );
          		break;
          	case '1':
              $( ".btn-group" ).empty().append( '	  <button type="button" class="btn btn-default" data-toggle="dropdown" id="have_seen">			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  Гледах я		</button>	  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">	    <span class="caret"></span>	    <span class="sr-only">Toggle Dropdown</span>	  </button>	  <ul class="dropdown-menu">	    <li><a id="want_to_see">Искам да я гледам</a></li>	  </ul>' );
          		break;
          	default:
            	$( ".btn-group" ).empty().append( '<button type="button" class="btn btn-success" data-toggle="dropdown" id="want_to_see">			Искам да я гледам		</button>	  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">	    <span class="caret"></span>	    <span class="sr-only">Toggle Dropdown</span>	  </button>	  <ul class="dropdown-menu">	    <li><a id="have_seen">Гледах я</a></li>	  </ul>' );
          }
        }
			});
    });

    $( "#want_to_see" ).live( "click", function() {
      $( "#want_to_see" ).empty().append('<img src="' + vk_select_params.plugin_url + '/woocommerce/assets/images/loading.gif"> запазване...');

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
            switch ( response ) {
            	case '0':
            		$( ".btn-group" ).empty().append( '<button type="button" class="btn btn-default" data-toggle="dropdown" id="want_to_see">			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  Искам да я гледам		</button>	  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">	    <span class="caret"></span>	    <span class="sr-only">Toggle Dropdown</span>	  </button>	  <ul class="dropdown-menu">	    <li><a id="have_seen">Гледах я</a></li>	  </ul>' );
            		break;
            	case '1':
                $( ".btn-group" ).empty().append( '	  <button type="button" class="btn btn-default" data-toggle="dropdown" id="have_seen">			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>  Гледах я		</button>	  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">	    <span class="caret"></span>	    <span class="sr-only">Toggle Dropdown</span>	  </button>	  <ul class="dropdown-menu">	    <li><a id="want_to_see">Искам да я гледам</a></li>	  </ul>' );
            		break;
            	default:
              	$( ".btn-group" ).empty().append( '<button type="button" class="btn btn-success" data-toggle="dropdown" id="want_to_see">			Искам да я гледам		</button>	  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">	    <span class="caret"></span>	    <span class="sr-only">Toggle Dropdown</span>	  </button>	  <ul class="dropdown-menu">	    <li><a id="have_seen">Гледах я</a></li>	  </ul>' );
            }
					}
				});
      });
})(jQuery);
