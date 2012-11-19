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
        
    $( "#remove-contact-form-status" ).html( "" );
    $( "#remove-contact-form-status" ).removeClass( "alert" );
    $( "#remove-contact-form-status" ).removeClass( "alert-error" );
    $( "#remove-contact-form-status" ).removeClass( "alert-success" );

    $.post(
      "action_remove_contact.php",
      "id=" + id,
      function( data, s, jqXHR ) {
        $( "#remove-contact-form-status" ).html( jqXHR.responseText );
        $( "#remove-contact-form-status" ).show();
        $( "#modal" ).modal( "hide" );
        $( "#select" ).hide();
        $( "#search" ).fadeToggle( "slow" );
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#remove-contact-form-status" ).addClass( "alert alert-error" );
        $( "#remove-contact-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#remove-contact-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + "id=" + $( "#removeConfirm" ).attr( "data-id" ) );
        console.log( "Received --> " + jqXHR.responseText );
    });
  });
});
