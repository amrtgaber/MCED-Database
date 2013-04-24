$( document ).ready(function() {
  if( quickSelect ) {
    $( "#search" ).hide();

    $( "#formFields" ).load( "load_contact_form.php?id=" + quickId, function() {
      /* activate datepicker */
      $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
    });
    $( "#updateButton" ).attr( "data-id", quickId );
    $( "#update" ).fadeToggle( "slow" );
  }

  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search" ).hide();
    
    $( "#selectTable" ).load( "load_select_contact.php?" + $( "#search" ).serialize(), function() {
      $( ".contact" ).click(function() {
        $( "#select" ).hide();

        $( "#formFields" ).load( "load_contact_form.php?id=" + $( this ).attr( "data-id" ), function() {
          /* activate datepicker */
          $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
        });
  
        $( "#updateButton" ).attr( "data-id", $( this ).attr( "data-id" ) );
        $( "#update" ).fadeToggle( "slow" );
      });      
    });
    
    $( "#select" ).fadeToggle( "slow" );
    
    return false;
  });
  
  /* Back to search */
  $( "#backToSearch" ).click(function() {
    $( "#select" ).hide();
    $( "#search" ).fadeToggle( "slow" );
  });

  /* Back to select */
  $( "#backToSelect" ).click(function() {
    $( "#update" ).hide();
    $( "#select" ).fadeToggle( "slow" );
    
    $( "#update" ).each(function () {
      this.reset();
    });
  });

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
  
  /* Submit form if valid */
  $( "#update" ).submit(function() {
    if( !v.form() ) {
      return false;
    }
    
    $( "#edit-contact-form-status" ).html( "" );
    $( "#edit-contact-form-status" ).removeClass( "alert" );
    $( "#edit-contact-form-status" ).removeClass( "alert-error" );
    $( "#edit-contact-form-status" ).removeClass( "alert-success" );
    
    /* generate workplace id */    
    var workplace = $( "#workplace" ).val();
    var workplaceID = workplace.substring( workplace.length - 4 );
    
    /* generate action id */    
    var action = $( "#action" ).val();
    var actionID = action.substring( action.length - 4 );
    
    /* construct post request string */
    var postString = $( "#update" ).serialize() + "&contactType=" + $( "#contactType" ).val() + "&id=" + $( "#updateButton" ).attr( "data-id" ) + "&wid=" + workplaceID + "&aid=" + actionID;

    $.post(
      "action_contact_form.php",
      postString,
      function( data, textStatus, jqXHR ) {
        $( "#edit-contact-form-status" ).html( jqXHR.responseText );
        $( "#edit-contact-form-status" ).show();
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
        console.log( "Sent     --> " + postString );
        console.log( "Received --> " + jqXHR.responseText );
    });
    
    return false;
  });
});
