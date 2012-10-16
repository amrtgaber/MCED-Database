$( document ).ready(function() {
  $( "#search" ).submit(function() {
    console.log( "#search submitted" );
    $.post(
      "modify_contact_action_search.php",
      $( "#search" ).serialize(),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response.substring( 0, 7 ) == "Success" ) {
          $( "#search" ).hide();
          $( "#select" ).html( response.substring( 8 ) );
          $( "input[type=radio]:first" ).attr( "checked", true);
          $( "#select" ).fadeToggle( "slow" );
        } else if( response == "Not Found" ) {
          alert( "The contact you searched for was not found in the database.");
        } else if( response == "Invalid Name" ) {
          alert( "First Name and Last Name are required fields.");
        } else if( response == "SQL Error" ) {
          alert( "There was an error with the database. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com" );
        } else if( response == "Permission Denied" ) {
          alert( "You do not have the required privilege level to modify a contact." );
        } else if( response == "Unauthorized" ) {
          alert( "You must be logged in to add a contact." );
          window.location = "login.php";
        } else {
          alert( "The server received the request but returned an unknown response. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com." );
        }
      }
    ).fail(function( data, s, jqXHR ) {
      alert( "There was an unknown error in the server. If you get this error more than once, "
        + "please try again later or contact admin@debrijja.com." );
    }
    ).always(function( data, s, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + $( "#search" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });

  $( "#select" ).submit(function() {
    console.log( "#select submitted" );
    $.post(
      "modify_contact_action_select.php",
      $( "#select" ).serialize(),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response.substring( 0, 7 ) == "Success" ) {
          $( "#select" ).hide();
          $( "#update" ).html( response.substring( 8 ) );
          $( "#update" ).fadeToggle( "slow" );
        } else if( response == "Invalid ID" ) {
          alert( "The ID of the contact you selected is invalid.");
        } else if( response == "SQL Error" ) {
          alert( "There was an error with the database. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com" );
        } else if( response == "Permission Denied" ) {
          alert( "You do not have the required privilege level to modify a contact." );
        } else if( response == "Unauthorized" ) {
          alert( "You must be logged in to add a contact." );
          window.location = "login.php";
        } else {
          alert( "The server received the request but returned an unknown response. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com." );
        }
      }
    ).fail(function( data, s, jqXHR ) {
      alert( "There was an unknown error in the server. If you get this error more than once, "
        + "please try again later or contact admin@debrijja.com." );
    }
    ).always(function( data, s, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + $( "#select" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });

  jQuery.validator.addMethod( "phoneLength", function( phone_number, element ) {
        phone_number = phone_number.replace(/\s+/g, ""); 
        return this.optional( element ) || phone_number.length == 7 || phone_number.length == 10;
  }, "Phone number must either be 7 digits long or 10 digits long." );

  /* Validate form */
  var v = $( "#update" ).validate({
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
      if( element.attr( "name" ) == "firstName"
          || element.attr( "name" ) == "lastName"
          || element.attr( "name" ) == "email"
          || element.attr( "name" ) == "phone"
          || element.attr( "name" ) == "cell"
          || element.attr( "name" ) == "state"
          || element.attr( "name" ) == "zipcode" ) {
        error.appendTo( $( "#error" ) );
      } else {
        error.appendTo( $( "#error-optional" ) );
      }
    }
  });
  
  /* Submit form if valid */
  $( "#update" ).submit(function() {
    console.log( "#update submitted" );
    if( !v.form() ) {
      return false;
    }

    $.post(
      "modify_contact_action_update.php",
      $( "#update" ).serialize(),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          alert( "Success! The entry for "
            + $( "input[name=firstName]:last" ).val()
            + " "
            + $( "input[name=lastName:last]" ).val()
            + " was successfully changed." );

          $( "#update" ).hide();
          $( "#search" ).fadeToggle();

          $( "#update" ).each(function () {
            this.reset();
          });
        } else if( response == "Invalid Name" ) {
          alert( "First Name and Last Name are required fields.");
        } else if( response == "Invalid State" ) {
          alert( "State field is invalid." );
        } else if( response == "Invalid Zipcode" ) {
          alert( "Zipcode field is invalid." );
        } else if( response == "Invalid Phone" ) {
          alert( "Phone field is invalid." );
        } else if( response == "Invalid Cell" ) {
          alert( "Cell field is invalid." );
        } else if( response == "Invalid Email" ) {
          alert( "Email field is invalid." );
        } else if( response == "Invalid Dollars" ) {
          alert( "Dollars field is invalid." );
        } else if( response == "Invalid Cents" ) {
          alert( "Cents field is invalid." );
        } else if( response == "Invalid School Year" ) {
          alert( "School year field is invalid." );
        } else if( response == "SQL Error" ) {
          alert( "There was an error with the database. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com" );
        } else if( response == "Permission Denied" ) {
          alert( "You do not have the required privilege level to modify a contact." );
        } else if( response == "Unauthorized" ) {
          alert( "You must be logged in to add a contact." );
          window.location = "login.php";
        } else {
          alert( "The server received the request but returned an unknown response. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com." );
        }
      }
    ).fail(function( data, s, jqXHR ) {
      alert( "There was an unknown error in the server. If you get this error more than once, "
        + "please try again later or contact admin@debrijja.com." );
    }
    ).always(function( data, s, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + $( "#update" ).serialize() );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });
});
