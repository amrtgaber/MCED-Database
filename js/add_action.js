$( document ).ready(function() {
  /* Load form fields */
  $( "#form-fields" ).load( "load_action_form.php" );
  
  /* Submit form if valid */
  $( "form" ).submit(function() {
    $( "#add-action-form-status" ).html( "" );
    $( "#add-action-form-status" ).removeClass( "alert" );
    $( "#add-action-form-status" ).removeClass( "alert-error" );
    $( "#add-action-form-status" ).removeClass( "alert-success" );

    var postString = $( "form" ).serialize();

    $.post(
      "action_action_form.php",
      postString,
      function( data, textStatus, jqXHR ) {
        $( "#add-action-form-status" ).html( jqXHR.responseText );
        $( "#add-action-form-status" ).show();
        $( "form" ).each(function () {
          this.reset();
        });
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#add-action-form-status" ).addClass( "alert alert-error" );
        $( "#add-action-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#add-action-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + postString );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
