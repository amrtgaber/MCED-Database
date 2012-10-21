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
    $.post(
      "remove_contact_action.php",
      "id=" + $( "#removeConfirm" ).attr( "data-id" ),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          alert( "Success! The entry for "
            + $( "input[name=firstName]" ).val()
            + " "
            + $( "input[name=lastName]" ).val()
            + " was successfully removed." );
          
          $( "#modal" ).modal( "hide" );
          $( "#select" ).hide();
          $( "#search" ).fadeToggle( "slow" );
        } else if( response == "Invalid ID" ) {
          alert( "ID is a required field.");
        } else if( response == "SQL Error" ) {
          alert( "There was an error with the database. If you get this response more than once, "
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
      console.log( "Sent     --> " + "id=" + $( "#removeConfirm" ).attr( "data-id" ) );
      console.log( "Received --> " + jqXHR.responseText );
    });
  });
});
