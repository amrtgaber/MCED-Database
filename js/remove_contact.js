$( document ).ready(function() {
  $( "#search" ).submit(function() {
    $.post(
      "modify_contact_action_search.php",
      $( "#search" ).serialize() + "&remove=true",
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response.substring( 0, 7 ) == "Success" ) {
          $( "#search" ).hide();
          $( "#select" ).html( response.substring( 8 ) );
          $( "input[type=radio]:first" ).attr( "checked", true);
          $( "#select" ).fadeToggle( "slow" );
  
          $( "#backToSearch" ).click(function() {
            $( "#select" ).hide();
            $( "#search" ).fadeToggle( "slow" );
          });
        } else if( response == "Not Found" ) {
          alert( "The contact you searched for was not found in the database.");
        } else if( response == "Invalid Name" ) {
          alert( "First Name and Last Name are required fields.");
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
      console.log( "Sent     --> " + $( "#search" ).serialize() + "&remove=true" );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });

  $( "#select" ).submit(function() {
    $.post(
      "remove_contact_action.php",
      $( "#select" ).serialize(),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          alert( "Success! The entry for "
            + $( "input[name=firstName]" ).val()
            + " "
            + $( "input[name=lastName]" ).val()
            + " was successfully removed." );
          
          $( "#select" ).hide();
          $( "#search" ).fadeToggle( "slow" );
        } else if( response == "Invalid ID" ) {
          alert( "The ID of the contact you selected is invalid.");
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
      console.log( "Sent     --> " + $( "#select" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });
});
