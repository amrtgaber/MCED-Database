$(document).ready(function() {
  /* Reset all fields when forms are switched */
  $( "a[data-toggle='tab']" ).click(function() {
    $( "form" ).each(function() {
      this.reset();
    });
  });

  /* Basic form */
  $( "#basic-form" ).submit(function() {
    $( "#list" ).load( "load_basic_list.php?" + $( "#basic-form" ).serialize(), function() {
      /* Edit contact */
      $( "button.edit" ).click(function() {
        var id = $( this ).attr( "data-id" );

        $( "#modalBodyEdit" ).load( "load_contact_form.php?id=" + id );
        $( "#updateButton" ).attr( "data-id", id );
        $( "#modal-edit" ).modal( "show" );
      });
      
      /* Remove contact */
      $( "button.remove" ).click(function() {
        var id = $( this ).attr( "data-id" );
        
        $( "#removeConfirm" ).attr( "data-id", id );
        $( "#modalBodyRemove" ).html( "This action cannot be undone. Clicking remove will permanently remove "
                                 + $( "#firstname" + id ).text()
                                 + " "
                                 + $( "#lastname" + id ).text()
                                 + " from the database." );

        $( "#modal-remove" ).modal( "show" );
      });
    });
      
    return false;
  });
  
  /* Remove confirmed */
  $( "#removeConfirm" ).click(function() {
    $.post(
      "remove_contact_action.php",
      "id=" + $( "#removeConfirm" ).attr( "data-id" ),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          alert( "Success! The entry was successfully removed." );
          
          $( "#modal-remove" ).modal( "hide" );
          $( "#basic-form" ).submit();
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
      console.log( "Sent     --> " + "id=" + $( "#removeConfirm" ).attr( "data-id" ) );
      console.log( "Received --> " + jqXHR.responseText );
    });
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

  /* Update contact */
  $( "#update" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $.post(
      "contact_update_action.php",
      $( "#update" ).serialize() + "&contactType=" + $( "#contactType" ).val().toLowerCase() + "&id=" + $( "#updateButton" ).attr( "data-id" ),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText;

        if( response == "Success" ) {
          alert( "Success! The entry for "
            + $( "input[name=firstName]" ).val()
            + " "
            + $( "input[name=lastName]" ).val()
            + " was successfully changed." );

          $( "#modal-edit" ).modal( "hide" );
          $( "#basic-form" ).submit();

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
        } else if( response.substring( 0, 9 ) == "SQL Error" ) {
          alert( "There was an error with the database: "
                 + response.substring( 10 ) + ". "
                 + "If you get this response more than once, "
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
      console.log( "Sent     --> "
                   + $( "#update" ).serialize()
                   + "&contactType="
                   + $( "#contactType" ).val().toLowerCase()
                   + "&id="
                   + $( "#updateButton" ).attr( "data-id" ) );
      console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });
});
