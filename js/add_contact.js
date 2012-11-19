$( document ).ready(function() {
  /* Load form fields */
  $( "#form-fields" ).load( "load_contact_form.php" );

  /* Clear button */
  $( 'button[type="reset"]' ).click(function() {
    $( "#add-contact-form-status" ).hide();
    $( "#add-contact-form-status" ).html( "" );
  });

  jQuery.validator.addMethod( "phoneLength", function( phone_number, element ) {
        phone_number = phone_number.replace(/\s+/g, ""); 
        return this.optional( element ) || phone_number.length == 7 || phone_number.length == 10;
  }, "Phone number must either be 7 digits long or 10 digits long." );

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
        phoneLength: true,
        digits: true
      },
      cell: {
        phoneLength: true,
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
        phoneLength: "Phone number must be 7 or 10 digits long.",
        digits: "Phone number can only contain digits."
      },
      cell: {
        phoneLength: "Cell phone number must be 7 or 10 digits long.",
        digits: "Cell phone number can only contain digits."
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
    $( "#add-contact-form-status" ).removeClass( "alert alert-error alert-success" );

    $.post(
      "action_contact_form.php",
      $( "form" ).serialize() + "&contactType=" + $( "#contactType" ).val().toLowerCase(),
      function( data, textStatus, jqXHR ) {
        $( "#add-contact-form-status" ).html( jqXHR.responseText );
        $( "#add-contact-form-status" ).show();
        $( "form" ).each(function () {
          this.reset();
        });
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
        console.log( "Sent     --> " + $( "form" ).serialize() + "&contactType=" + $( "#contactType" ).val().toLowerCase() );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
