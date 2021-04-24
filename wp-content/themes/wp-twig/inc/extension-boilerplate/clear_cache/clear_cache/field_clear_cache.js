jQuery(document).ready(function() {
  jQuery('.js_clear_cache').on('click', function() {

    var data = {
      action: 'clear_cache',
      security: wp_ajax_nonce
    };

    jQuery.post( ajaxurl, data)
      .done(function(msg) {
        alert(msg);
      })
      .fail(function(xhr, status, error) {
        alert(error);
      })
  })

});