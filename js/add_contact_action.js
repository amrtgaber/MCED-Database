$( document ).ready(function() {
  /* Load form fields */
  $( "#form-fields" ).load( "load_contact_action_form.php", function() {
    
    /* activate datepicker */
    $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#date" ).datepicker( "setDate", new Date());
    
  });
  
  /* Clear button */
  $( 'button[type="reset"]' ).click(function() {
    $( "#add-contact-action-form-status" ).hide();
    $( "#add-contact-action-form-status" ).html( "" );
  });
  
  /* Submit form if valid */
  $( "form" ).submit(function() {
    $( "#add-contact-action-form-status" ).html( "" );
    $( "#add-contact-action-form-status" ).removeClass( "alert" );
    $( "#add-contact-action-form-status" ).removeClass( "alert-error" );
    $( "#add-contact-action-form-status" ).removeClass( "alert-success" );
    
    /* construct post string */
    var aname = $( "#aname" ).val();
    var cname = $( "#cname" ).val();
    var aid = aname.substring( aname.length - 5 );
    var cid = cname.substring( cname.length - 5 );
    
    var postString = "aname=" + aname + "&aid=" + aid + "&cname=" + cname + "&cid=" + cid + "&date=" + $( "#date" ).val();

    $.post(
      "action_contact_action_form.php",
      postString,
      function( data, textStatus, jqXHR ) {
        $( "#add-contact-action-form-status" ).html( jqXHR.responseText );
        $( "#add-contact-action-form-status" ).show();
        $( "#cname" ).val( "" );
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#add-contact-action-form-status" ).addClass( "alert alert-error" );
        $( "#add-contact-action-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#add-contact-action-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + postString );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
