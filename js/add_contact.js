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
      cell: {
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
      dollars: {
        digits: true
      },
      cents: {
        minlength: 2,
        maxlength: 2,
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
      cell: {
        phoneUS: "Please enter a valid cell phone number."
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
      year: {
        minlength: "Year must be exactly 4 digits long.",
        maxlength: "Year must be exactly 4 digits long.",
        digits: "Year can only contain digits."
      }
    },
    errorPlacement: function( error, element ) {
      if( element.attr( "name" ) == "email"
          || element.attr( "name" ) == "phone"
          || element.attr( "name" ) == "state"
          || element.attr( "name" ) == "zipcode" ) {
        error.appendTo( $( "#error-contact" ) );
      } else if( element.attr( "name" ) == "dollars"
                 || element.attr ("name" ) == "cents" ) {
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
