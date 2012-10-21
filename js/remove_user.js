$( document ).ready(function() {
  $( "#user-list" ).load( "load_select_user.php" );

  $( "form" ).submit(function() {
    $( ".modal-body" ).html( "This action cannot be undone. Clicking remove will permanently remove "
                             + $( "input[type=radio]:checked" ).val()
                             + " as a user." );

    $( "#modal" ).modal( "show" );
    return false;
  });
  
  $( "#removeConfirm" ).click(function() {
    $.post(
      "remove_user_action.php",
      $( "form" ).serialize(),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          alert( "Success! The user was successfully removed." );
          
          $( "#modal" ).modal( "hide" );
          $( "#user-list" ).load( "load_select_user.php" );
        } else if( response == "Invalid Username" ) {
          alert( "Username is a required field.");
        } else if( response.substring( 0, 9 ) == "SQL Error" ) {
          alert( "There was an error with the database: "
                 + response.substring( 10 ) + ". "
                 + "If you get this response more than once, "
                 + "please try again later or contact admin@debrijja.com" );
        } else if( response == "Permission Denied" ) {
          alert( "You do not have the required privilege level to modify a contact." );
        } else if( response == "Unauthorized" ) {
          alert( "You must be logged in to add a contact." );
          window.location = "login.php";
        } else {
          alert( "The server received the request but returned an unknown response. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com." );
        }
      }
    ).fail(function( data, s, jqXHR ) {
      alert( "There was an unknown error in the server. If you get this error more than once, "
        + "please try again later or contact admin@debrijja.com." );
    }
    ).always(function( data, s, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + $( "form" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
  });
});
