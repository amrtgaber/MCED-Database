var v;

/* set up form handlers */
function contact_form_handlers() {
  /* activate datepicker */
  $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
  
  /* save and cancel buttons */
  $( "#save-button" ).click( submit_contact_form );
  $( "#cancel-button" ).click( load_contact_form );
}

/* Validate form */
function validate_contact_form() {
  v = $( "#contact-form" ).validate({
    rules: {
      firstName: {
        required: true
      },
      lastName: {
        required: true
      },
      email: {
        email: true
      },
      phone: {
        minlength: 10,
        maxlength: 10,
        digits: true
      },
      state: {
        minlength: 2,
        maxlength: 2
      },
      zipcode: {
        minlength: 5,
        maxlength: 5,
        digits: true
      },
      dollars: {
        digits: true
      },
      cents: {
        minlength: 2,
        maxlength: 2,
        digits: true
      },
      syear: {
        minlength: 2,
        maxlength: 2,
        digits: true
      }
    },
    messages: {
      firstName: {
        required: "First Name is a required field."
      },
      lastName: {
        required: "Last Name is a required field."
      },
      email: {
        email: "Please enter a valid email."
      },
      phone: {
        minlength: "Phone number must be exactly 10 digits long.",
        maxlength: "Phone number must be exactly 10 digits long.",
        digits: "Phone number can only contain digits."
      },
      state: {
        minlength: "State must be 2 letter abbreviation.",
        maxlength: "State must be 2 letter abbreviation."
      },
      zipcode: {
        minlength: "Zipcode must be exactly 5 digits long.",
        maxlength: "Zipcode must be exactly 5 digits long.",
        digits: "Zipcode can only contain digits."
      },
      dollars: {
        digits: "Dollars can only contain digits."
      },
      cents: {
        minlength: "Cents must be exactly 2 digits long.",
        maxlength: "Cents must be exactly 2 digits long.",
        digits: "Cents can only contain digits."
      },
      syear: {
        minlength: "School year must be exactly 2 digits long.",
        maxlength: "School year must be exactly 2 digits long.",
        digits: "School year can only contain digits."
      }
    },
    errorPlacement: function( error, element ) {
      error.appendTo( $( "#contact-form-status" ) );
      $( "#contact-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#contact-form-status" ).hide();
        $( "#contact-form-status" ).html( "" );
      }
    }
  });
}

/* load form */
function load_contact_form() {
  $( "#contact-form" ).load( "load_contact_form.php?id=" + id + "&add=" + add, contact_form_handlers );
  validate_contact_form();
}
  
/* Submit form if valid */
function submit_contact_form() {
  if( !v.form() ) {
    return false;
  }
  
  $( "#contact-form-status" ).html( "" );
  $( "#contact-form-status" ).removeClass( "alert" );
  $( "#contact-form-status" ).removeClass( "alert-error" );
  $( "#contact-form-status" ).removeClass( "alert-success" );
  
  /* construct post request string */
  var postString = $( "#contact-form" ).serialize()
                     + "&contactType=" + $( "#contactType option:selected" ).attr( "data-ctype" )
                     + "&wid=" + $( "#workplace option:selected" ).attr( "data-wid" )
                     + "&aid=" + $( "#action option:selected" ).attr( "data-aid" )
                     + "&id=" + id
                     + "&add=" + add;

  $.post(
    "action_contact_form.php",
    postString,
    function( data, textStatus, jqXHR ) {
      $( "#contact-form-status" ).html( jqXHR.responseText );
      $( "#contact-form-status" ).show();
    }
    ).fail(function( data, textStatus, jqXHR ) {
      $( "#contact-form-status" ).addClass( "alert alert-error" );
      $( "#contact-form-status" ).html( "There was an unknown error in the server. "
        + "If you get this error more than once, "
        + "please try again later or contact jalhaj@mc-ed.org." );
      $( "#contact-form-status" ).show();
    }
    ).always(function( data, textStatus, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + postString );
      console.log( "Received --> " + jqXHR.responseText );
  });
  
  return false;
}
