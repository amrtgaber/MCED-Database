$( document ).ready(function() {
  $( 'button[type="reset"]' ).click(function() {
    $( "#error-contact" ).html( "" );
    $( "#error-worker" ).html( "" );
    $( "#error-student" ).html( "" );
  });

  jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, ""); 
          return this.optional(element) || phone_number.length > 9 &&
              phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
  }, "Please specify a valid phone number");

  $( "form" ).validate({
    rules: {
      email: {
        email: true
      },
      phone: {
        phoneUS: true
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
      wage: {
        digits: true
      },
      year: {
        minlength: 4,
        maxlength: 4,
        digits: true
      }
    },
    messages: {
      email: {
        email: "Please enter a valid email."
      },
      phone: {
        phoneUS: "Please enter a valid phone number."
      },
      state: {
        minlength: "State must be 2 letter abbreviation.",
        maxlength: "State must be 2 letter abbreviation."
      },
      zipcode: {
        minlength: "Zipcode must be 5 digits.",
        maxlength: "Zipcode must be 5 digits.",
        digits: "Zipcode can only contain digits."
      },
      wage: {
        digits: "Please enter a valid wage."
      },
      year: {
        minlength: "Year must be 4 digits.",
        maxlength: "Year must be 4 digits.",
        digits: "Year can only contain digits."
      }
    },
    errorPlacement: function( error, element ) {
      if( element.attr( "name" ) == "email"
          || element.attr( "name" ) == "phone"
          || element.attr( "name" ) == "state"
          || element.attr( "name" ) == "zipcode" ) {
        error.appendTo( $( "#error-contact" ) );
      } else if( element.attr( "name" ) == "wage" ) {
        error.appendTo( $( "#error-worker" ) );
      } else {
        error.appendTo( $( "#error-student" ) );
      }
    },
    submitHandler: function( form ) {
      $( "form" ).submit(function() {
        $.post(
          "add_contact_action.php",
          $( "form" ).serialize(),
          function() {
            console.log("success");
          }
        )
      });
    }
  });
});
