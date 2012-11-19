$( document ).ready(function() {
  $( "#user-list" ).load( "load_select_user.php" );

  $( "form" ).submit(function() {
    if( $( "input[type=radio]:checked" ).val() == undefined ) {
      $( "#remove-user-form-status" ).html( "You must select a user from the list." );
      $( "#remove-user-form-status" ).show();
    } else {
      $( "#remove-user-form-status" ).hide();
      $( "#remove-user-form-status" ).html( "" );

      $( ".modal-body" ).html( "This action cannot be undone. Clicking remove will permanently remove "
                               + $( "input[type=radio]:checked" ).val()
                               + " as a user." );
      $( "#modal" ).modal( "show" );
    }

    return false;
  });
  
  $( "#removeConfirm" ).click(function() {
    $( "#remove-user-form-status" ).html( "" );
    $( "#remove-user-form-status" ).removeClass( "alert alert-error alert-success" );

    $.post(
      "action_remove_user.php",
      $( "form" ).serialize(),
      function( data, s, jqXHR ) {
        $( "#remove-user-form-status" ).html( jqXHR.responseText );
        $( "#remove-user-form-status" ).show();
        $( "#modal" ).modal( "hide" );
        $( "#user-list" ).load( "load_select_user.php" );
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#remove-user-form-status" ).addClass( "alert alert-error" );
        $( "#remove-user-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#remove-user-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + $( "form" ).serialize() );
        console.log( "Received --> " + jqXHR.responseText );
    });
  });
});
