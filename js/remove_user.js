$( document ).ready(function() {
  $( "#user-list" ).load( "load_select_user.php" );

  $( "form" ).submit(function() {
    if( $( "input[type=radio]:checked" ).val() == undefined ) {
      $( "#form-invalid" ).html( "You must select a user from the list." );
      $( "#form-invalid" ).show();
    } else {
      $( "#form-invalid" ).hide();
      $( "#form-invalid" ).html( "" );

      $( ".modal-body" ).html( "This action cannot be undone. Clicking remove will permanently remove "
                               + $( "input[type=radio]:checked" ).val()
                               + " as a user." );
      $( "#modal" ).modal( "show" );
    }

    return false;
  });
  
  $( "#removeConfirm" ).click(function() {
    $.post(
      "remove_user_action.php",
      $( "form" ).serialize(),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          $( "#response" ).removeClass( "alert-error" );
          $( "#response" ).addClass( "alert-success" );
          $( "#response" ).html( "Success! The user was successfully removed." );
          
          $( "#modal" ).modal( "hide" );
          $( "#user-list" ).load( "load_select_user.php" );
        } else {
          $( "#response" ).removeClass( "alert-success" );
          $( "#response" ).addClass( "alert-error" );
        
          if( response == "Invalid Username" ) {
            $( "#response" ).html( "Username is a required field.");
          } else if( response.substring( 0, 9 ) == "SQL Error" ) {
            $( "#response" ).html( "There was an error with the database. "
              + "If you get this response more than once, "
              + "please try again later or contact jalhaj@mc-ed.org. "
              + "ERROR: "
              + response.substring( 10 ) + "." );
          } else if( response == "Permission Denied" ) {
            $( "#response" ).html( "You do not have the required privilege level to modify a contact." );
          } else if( response == "Unauthorized" ) {
            alert( "You must be logged in to add a contact." );
            window.location = "login.php";
          } else {
            $( "#response" ).html( "The server received the request but returned an unknown response. If you get this response more than once, "
              + "please try again later or contact jalhaj@mc-ed.org." );
          }
        }
        
        $( "#response" ).show();
      }
    ).fail(function( data, s, jqXHR ) {
      $( "#response" ).removeClass( "alert-success" );
      $( "#response" ).addClass( "alert-error" );
      $( "#response" ).html( "There was an unknown error in the server. "
        + "If you get this error more than once, "
        + "please try again later or contact jalhaj@mc-ed.org." );
      $( "#response" ).show();
    }
    ).always(function( data, s, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + $( "form" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
  });
});
