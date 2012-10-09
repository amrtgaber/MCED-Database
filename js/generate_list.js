$(document).ready(function() {
  $( "a[data-toggle='tab']" ).click(function() {
    $( "form" ).each(function() {
      this.reset();
    });
  });

  $( "form" ).submit(function() {
    $.post(
      "generate_list_action.php",
      $( "form" ).serialize(),
      function( data, st, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Invalid People" ) {
          alert( "You must select at least one group to include in the list." );
        } else if( response == "Unauthorized" ) {
          alert( "You must be logged in to add a contact." );
          window.location = "login.php";
        } else if( response == "SQL Error" ) {
          alert( "There was an error with the database. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com" );
        } else {
          $( "#list" ).html( response );
        }
      }
    ).fail(function( data, st, jqXHR ) {
      alert( "There was an unknown error in the server. If you get this error more than once, "
        + "please try again later or contact admin@debrijja.com." );
    }
    ).always(function( data, st, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + $( "form" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });
});
