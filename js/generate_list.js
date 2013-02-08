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
        $( "#edit-contact-form-status" ).hide();
        $( "#edit-contact-form-status" ).html( "" );
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
  
  /* basic csv export */
  $( "#basic-csv-export" ).click( function() {
    window.open( "load_basic_list.php?" + $( "#basic-form" ).serialize() + "&csv=true" );
  });
  
  /* Phone Bank form */
  $( "#phone-bank-form" ).submit(function() {
    $( "#list" ).load( "load_phone_bank_list.php?" + $( "#phone-bank-form" ).serialize() );
    return false;
  });

  /* phone bank print */
  $( "#phone-bank-print" ).click( function() {
    window.open( "load_phone_bank_list.php?" + $( "#phone-bank-form" ).serialize() + "&print=true" );
  });

  /* Remove confirmed */
  $( "#removeConfirm" ).click(function() {
    $.post(
      "action_remove_contact.php",
      "id=" + $( "#removeConfirm" ).attr( "data-id" ),
      function( data, s, jqXHR ) {
        $( "#modal-remove" ).modal( "hide" );
        $( "#basic-form" ).submit();
      }
      ).fail(function( data, textStatus, jqXHR ) {
      }
      ).always(function( data, textStatus, jqXHR ) {
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
      error.appendTo( $( "#edit-contact-form-status" ) );
      $( "#edit-contact-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#edit-contact-form-status" ).hide();
        $( "#edit-contact-form-status" ).html( "" );
      }
    }
  });

  /* Update contact */
  $( "#update" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $( "#edit-contact-form-status" ).hide();
    $( "#edit-contact-form-status" ).html( "" );

    $.post(
      "action_contact_form.php",
      $( "#update" ).serialize() + "&contactType=" + $( "#contactType" ).val() + "&id=" + $( "#updateButton" ).attr( "data-id" ),
      function( data, s, jqXHR ) {
        $( "#edit-contact-form-status" ).html( jqXHR.responseText );
        $( "#edit-contact-form-status" ).show();
        $( "#basic-form" ).submit();

        $( "#update" ).each(function () {
          this.reset();
        });
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#edit-contact-form-status" ).addClass( "alert alert-error" );
        $( "#edit-contact-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#edit-contact-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> "
                     + $( "#update" ).serialize()
                     + "&contactType="
                     + $( "#contactType" ).val()
                     + "&id="
                     + $( "#updateButton" ).attr( "data-id" ) );
        console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });
});
