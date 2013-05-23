/* set up form handlers */
function action_form_handlers() {
  /* Add contact search button */
  $( "#add-contact-search-button" ).click(function() {
    $( "#add-contact-search-results" ).load( "load_add_contact_search_table.php?firstName=" + $( "#firstName" ).val() + "&lastName=" + $( "#lastName" ).val(), function() {
      /* Add worker button */
      $( ".add-contact-button" ).click(function() {
        $( this ).removeClass( "btn-success" );
        $( this ).addClass( "btn-danger" );
        
        $( this ).children().removeClass( "icon-plus" );
        $( this ).children().addClass( "icon-minus" );
        
        $( this ).parent().parent().addClass( "contact" );
        $( this ).click(function() {
          $( this ).parent().parent().remove();
        });
        
        $( "#contact-table-body" ).append( $( this ).parent().parent() );
      });
    });
  });
  
  /* Add worker clear button */
  $( "#add-contact-clear-button" ).click(function() {
    $( "#firstName" ).val( "" );
    $( "#lastName" ).val( "" );
    $( "#add-contact-search-results" ).html( "" );
  });
  
  /* action buttons */
  $( "#save-button" ).click( submit_action_form );
  $( "#cancel-button" ).click( load_action_form );
  $( "#delete-confirm-button" ).click(function() {
    $.post( "action_delete_action.php", "aid=" + aid );
    window.location = "search_action.php";
  });
}

/* load form */
function load_action_form() {
  $( "#action-form" ).load( "load_action_form.php?aid=" + aid + "&add=" + add, action_form_handlers );
}

/* Submit form if valid */
function submit_action_form() {
  $( "#save-button" ).attr( "disabled", "disabled" );
  
  $( "#action-form-status" ).html( "" );
  $( "#action-form-status" ).removeClass( "alert" );
  $( "#action-form-status" ).removeClass( "alert-error" );
  $( "#action-form-status" ).removeClass( "alert-success" );

  /* generate worker id list */
  var addContacts = "";    
  $( ".contact" ).each(function() {
    addContacts += $( this ).attr( "data-id" ) + ",";
  });
  
  /* remove trailing comma */
  if( addContacts.length > 0 ) {
    addContacts = addContacts.substring( 0, addContacts.length - 1 );
  }
  
  /* construct post request string */
  var postString = $( "#action-form" ).serialize()
                     + "&addContacts=" + addContacts
                     + "&aid=" + aid
                     + "&add=" + add;

  $.post(
    "action_action_form.php",
    postString,
    function( data, textStatus, jqXHR ) {
      $( "#action-form-status" ).html( jqXHR.responseText );
      
      if( $( "#action-form-status" ).children().hasClass( "alert-error" ) ) {
        $( "#save-button" ).removeAttr( 'disabled' );
      }
      
      $( "#action-form-status" ).show();
    }
    ).fail(function( data, textStatus, jqXHR ) {
      $( "#save-button" ).removeAttr( 'disabled' );
      $( "#action-form-status" ).addClass( "alert alert-error" );
      $( "#action-form-status" ).html( "There was an unknown error in the server. "
        + "If you get this error more than once, "
        + "please try again later or contact jalhaj@mc-ed.org." );
      $( "#action-form-status" ).show();
    }
    ).always(function( data, textStatus, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + postString );
      console.log( "Received --> " + jqXHR.responseText );
    });
  
  return false;
}
