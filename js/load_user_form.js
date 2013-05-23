var v;

/* set up form handlers */
function user_form_handlers() {
  /* action buttons */
  $( "#save-button" ).click( submit_user_form );
  $( "#cancel-button" ).click( load_user_form );
  $( "#delete-confirm-button" ).click(function() {
    $.post( "action_delete_user.php", "uid=" + uid );
    window.location = "search_user.php";
  });
}

/* Validate form */
function validate_user_form() {
  v = $( "#user-form" ).validate({
    rules: {
      username: {
        required: true,
        minlength: 4
      }
    },
    messages: {
      username: {
        required: "Username is required.",
        minlength: "Username must be at least 4 characters long."
      }
    },
    errorPlacement: function( error, element ) {
      error.appendTo( $( "#user-form-status" ) );
      $( "#user-form-status" ).addClass( "alert" );
      $( "#user-form-status" ).addClass( "alert-error" );
      $( "#user-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#user-form-status" ).hide();
        $( "#user-form-status" ).html( "" );
      }
    }
  });
}

/* load form */
function load_user_form() {
  $( "#user-form" ).load( "load_user_form.php?uid=" + uid + "&add=" + add, user_form_handlers );
  validate_user_form();
}

/* Submit form if valid */
function submit_user_form() {
  if( !v.form() ) {
    return false;
  }
  
  $( "#save-button" ).attr( "disabled", "disabled" );
  
  $( "#user-form-status" ).html( "" );
  $( "#user-form-status" ).removeClass( "alert" );
  $( "#user-form-status" ).removeClass( "alert-error" );
  $( "#user-form-status" ).removeClass( "alert-success" );
    
  /* construct post request string */
  var postString = $( "#user-form" ).serialize()
                     + "&uid=" + uid
                     + "&add=" + add;

  $.post(
    "action_user_form.php",
    postString,
    function( data, textStatus, jqXHR ) {
      $( "#user-form-status" ).html( jqXHR.responseText );
      
      if( $( "#user-form-status" ).children().hasClass( "alert-error" ) ) {
        $( "#save-button" ).removeAttr( 'disabled' );
      }
      
      $( "#user-form-status" ).show();
    }
    ).fail(function( data, textStatus, jqXHR ) {
      $( "#save-button" ).removeAttr( 'disabled' );
      $( "#user-form-status" ).addClass( "alert alert-error" );
      $( "#user-form-status" ).html( "There was an unknown error in the server. "
        + "If you get this error more than once, "
        + "please try again later or contact jalhaj@mc-ed.org." );
      $( "#user-form-status" ).show();
    }
    ).always(function( data, textStatus, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + postString );
      console.log( "Received --> " + jqXHR.responseText );
  });

  return false;
}
