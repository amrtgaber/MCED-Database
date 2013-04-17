$( document ).ready(function() {
  /* set up form handlers */
  function form_handlers() {
    /* activate datepicker */
    $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#date" ).datepicker( "setDate", new Date());
  }
  
  /* Load form fields */
  $( "#form-fields" ).load( "load_contact_form.php", form_handlers );

  /* Clear button */
  $( 'button[type="reset"]' ).click(function() {
    $( "#add-contact-form-status" ).hide();
    $( "#add-contact-form-status" ).html( "" );
  });

  /* Validate form */
  var v = $( "form" ).validate({
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
      },
      date: {
        required: true,
        date: true
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
      },
      date: {
        required: "Date is a required field.",
        date: "Date must be a valid date (yyyy-mm-dd)."
      }
    },
    errorPlacement: function( error, element ) {
      error.appendTo( $( "#add-contact-form-status" ) );
      $( "#add-contact-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#add-contact-form-status" ).hide();
        $( "#add-contact-form-status" ).html( "" );
      }
    }
  });
  
  /* Submit form if valid */
  $( "form" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $( "#add-contact-form-status" ).html( "" );
    $( "#add-contact-form-status" ).removeClass( "alert" );
    $( "#add-contact-form-status" ).removeClass( "alert-error" );
    $( "#add-contact-form-status" ).removeClass( "alert-success" );

    /* generate workplace id */    
    var workplace = $( "#workplace" ).val();
    var workplaceID = workplace.substring( workplace.length - 4 );
    
    /* generate action id */    
    var action = $( "#action" ).val();
    var actionID = action.substring( action.length - 4 );
    
    /* construct post request string */
    var postString = $( "form" ).serialize() + "&contactType=" + $( "#contactType" ).val() + "&wid=" + workplaceID + "&aid=" + actionID;

    $.post(
      "action_contact_form.php",
      postString,
      function( data, textStatus, jqXHR ) {
        $( "#add-contact-form-status" ).html( jqXHR.responseText );
        $( "#add-contact-form-status" ).show();
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#add-contact-form-status" ).addClass( "alert alert-error" );
        $( "#add-contact-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#add-contact-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + postString );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
