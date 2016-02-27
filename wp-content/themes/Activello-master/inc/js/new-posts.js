/* New posts */
(function($) {
 $( "#have_seen" ).live( "click", function() {
 var last_date_recorded = 0,
  inputs = {}, post_data;


 /* Default POST values */
 object = '';
 item_id = 87123;
content = "VIDQNA E";
 firstrow = "HELLO";
 activity_row = firstrow;
 timestamp = null;

 post_data = $.extend( {
  action: 'post_update',
  'cookie': bp_get_cookies(),
  '_wpnonce_post_update': $('#_wpnonce_post_update').val(),
  'content': content,
  'object': object,
  'item_id': item_id,
  'since': last_date_recorded,
  '_bp_as_nonce': $('#_bp_as_nonce').val() || ''
 }, inputs );

 $.post( ajaxurl, post_data, function( response ) {
  console.log("send");
 });
});



})( jQuery );
