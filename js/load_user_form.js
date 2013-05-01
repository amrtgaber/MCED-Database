var v;

/* set up form handlers */
function user_form_handlers() {
  /* change password submit handler */
  $( "#change-password-submit-button" ).click(function() {
    $( "#change-password-form-status" ).addClass( "alert" );
    $( "#change-password-form-status" ).addClass( "alert-error" );
    
    if( $( "#newPassword" ).val() != $( "#changePassword" ).val() ) {
      $( "#change-password-form-status" ).html( "Passwords must match." );
      $( "#change-password-form-status" ).show();
    } else if( $( "#newPassword" ).val().length == 0 ) {
      $( "#change-password-form-status" ).html( "Both fields are required." );
      $( "#change-password-form-status" ).show();
    } else {
      $( "#change-password-form-status" ).html( "" );
      $( "#change-password-form-status" ).removeClass( "alert" );
      $( "#change-password-form-status" ).removeClass( "alert-error" );
      $( "#change-password-form-status" ).removeClass( "alert-success" );
      
      /* generate post request string */
      var cpPostString = $( "#change-password-form" ).serialize()
                         + "&uid=" + uid;
      $.post(
        "action_change_password.php",
        cpPostString,
        function( data, textStatus, jqXHR ) {
          $( "#change-password-form-status" ).html( jqXHR.responseText );
          $( "#change-password-form-status" ).show();
        }
        ).fail(function( data, textStatus, jqXHR ) {
          $( "#change-password-form-status" ).addClass( "alert alert-error" );
          $( "#change-password-form-status" ).html( "There was an unknown error in the server. "
            + "If you get this error more than once, "
            + "please try again later or contact jalhaj@mc-ed.org." );
          $( "#change-password-form-status" ).show();
        }
        ).always(function( data, textStatus, jqXHR ) {
          /* Debug */
          console.log( "Sent     --> " + cpPostString );
          console.log( "Received --> " + jqXHR.responseText );
      });
    }
  });
  
  /* change password clear handler */
  $( "#change-password-clear-button" ).click(function() {
    $( "#newPassword" ).val( "" );
    $( "#confirmPassword" ).val( "" );
    $( "#change-password-form-status" ).hide();
    $( "#change-password-form-status" ).html( "" );
  });
  
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
  var v = $( "#user-form" ).validate({
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
  
  $( "#user-form-status" ).html( "" );
  $( "#user-form-status" ).removeClass( "alert" );
  $( "#user-form-status" ).removeClass( "alert-error" );
  $( "#user-form-status" ).removeClass( "alert-success" );
    
  /* construct post request string */
  var postString = $( "#user-form" ).serialize()
                     + "&uid=" + uid
                     + "&add=" + add;

  $.post(
    "action_add_user.php",
    postString,
    function( data, textStatus, jqXHR ) {
      $( "#user-form-status" ).html( jqXHR.responseText );
      $( "#user-form-status" ).show();
    }
    ).fail(function( data, textStatus, jqXHR ) {
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
