$( document ).ready(function() {
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search" ).hide();
    $( "#selectTable" ).load( "load_select_contact.php?" + $( "#search" ).serialize(), function() {
      $( "input[type=radio]:first" ).attr( "checked", true );
    });
    $( "#select" ).fadeToggle( "slow" );
    
    return false;
  });
  
  /* Back to search */
  $( "#backToSearch" ).click(function() {
    $( "#select" ).hide();
    $( "#search" ).fadeToggle( "slow" );
  });

  /* First remove button press */
  $( "#selectButton" ).click(function() {
    var id = $( "input[type=radio]:checked" ).val();
    $( ".modal-body" ).html( "This action cannot be undone. Clicking remove will permanently remove "
                             + $( "#firstname" + id ).text()
                             + " "
                             + $( "#lastname" + id ).text()
                             + " from the database." );

    $( "#modal" ).modal( "show" );
    $( "#removeConfirm" ).attr( "data-id", id );

    return false;
  });

  /* Remove confirmed */
  $( "#removeConfirm" ).click(function() {
    var id = $( "#removeConfirm" ).attr( "data-id" );
    $.post(
      "remove_contact_action.php",
      "id=" + id,
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          $( "#response" ).removeClass( "alert-error" );
          $( "#response" ).addClass( "alert-success" );
          $( "#response" ).html( "The entry for "
            + $( "#firstname" + id ).text()
            + " "
            + $( "#lastname" + id ).text()
            + " was successfully removed." );
          
          $( "#modal" ).modal( "hide" );
          $( "#select" ).hide();
          $( "#search" ).fadeToggle( "slow" );
        } else {
          $( "#response" ).removeClass( "alert-success" );
          $( "#response" ).addClass( "alert-error" );
          
          if( response == "Invalid ID" ) {
            $( "#response" ).html( "ID is a required field.");
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
      console.log( "Sent     --> " + "id=" + $( "#removeConfirm" ).attr( "data-id" ) );
      console.log( "Received --> " + jqXHR.responseText );
    });
  });
});
