$( document ).ready(function() {
  /* Load form fields */
  $( "#form-fields" ).load( "load_shop_profile_form.php" );

  /* Clear button */
  $( 'button[type="reset"]' ).click(function() {
    $( "#add-shop-profile-form-status" ).hide();
    $( "#add-shop-profile-form-status" ).html( "" );
  });
  
  jQuery.validator.addMethod( "phoneLength", function( phone_number, element ) {
        phone_number = phone_number.replace(/\s+/g, ""); 
        return this.optional( element ) || phone_number.length == 10;
  }, "Phone number must be 10 digits long." );

  /* Validate form */
  var v = $( "form" ).validate({
    rules: {
      wname: {
        required: true
      },
      phone: {
        required: true,
        phoneLength: true,
        digits: true
      },
      address: {
        required: true
      },
      city: {
        required: true
      },
      state: {
        required: true,
        minlength: 2,
        maxlength: 2
      },
      zipcode: {
        required: true,
        minlength: 5,
        maxlength: 5,
        digits: true
      },
      numWorkers: {
        digits: true
      }
    },
    messages: {
      wname: {
        required: "Workplace Name is a required field."
      },
      phone: {
        required: "Phone is a required field.",
        phoneLength: "Phone number must be exactly 10 digits long.",
        digits: "Phone number can only contain digits."
      },
      address: {
        required: "Address is a required field."
      },
      city: {
        required: "City is a required field."
      },
      state: {
        required: "State is a required field.",
        minlength: "State must be 2 letter abbreviation.",
        maxlength: "State must be 2 letter abbreviation."
      },
      zipcode: {
        required: "Zipcode is a required field.",
        minlength: "Zipcode must be exactly 5 digits long.",
        maxlength: "Zipcode must be exactly 5 digits long.",
        digits: "Zipcode can only contain digits."
      },
      numWorkers: {
        digits: "Total Workers can only contain digits."
      }
    },
    errorPlacement: function( error, element ) {
      error.appendTo( $( "#add-shop-profile-form-status" ) );
      $( "#add-shop-profile-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#add-shop-profile-form-status" ).hide();
        $( "#add-shop-profile-form-status" ).html( "" );
      }
    }
  });
  
  /* Submit form if valid */
  $( "form" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $( "#add-shop-profile-form-status" ).html( "" );
    $( "#add-shop-profile-form-status" ).removeClass( "alert" );
    $( "#add-shop-profile-form-status" ).removeClass( "alert-error" );
    $( "#add-shop-profile-form-status" ).removeClass( "alert-success" );

    $.post(
      "action_shop_profile_form.php",
      $( "form" ).serialize(),
      function( data, textStatus, jqXHR ) {
        $( "#add-shop-profile-form-status" ).html( jqXHR.responseText );
        $( "#add-shop-profile-form-status" ).show();
        $( "form" ).each(function () {
          this.reset();
        });
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#add-shop-profile-form-status" ).addClass( "alert alert-error" );
        $( "#add-shop-profile-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#add-shop-profile-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + $( "form" ).serialize() );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
