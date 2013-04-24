$( document ).ready(function() {
  /* Search button */
  $( "#search-button" ).click(function() {
    $( "#search" ).hide();
    
    $( "#selectTable" ).load( "load_select_contact.php?" + $( "#search" ).serialize(), function() {
      $( ".contact" ).click(function () {
        $( "#select" ).hide();

        /* Load form fields and attach handlers*/
        $( "#form-fields" ).load( "load_contact_sheet_form.php?id=" + $( this ).attr( "data-id" ), form_handlers );
        
        $( "#add-contact-sheet-button" ).attr( "data-id", $( this ).attr( "data-id" ) );
        $( "#view" ).fadeToggle( "slow" );
      });
    });
    
    $( "#select" ).fadeToggle( "slow" );
  });
  
  /* Back to search */
  $( "#backToSearch" ).click(function() {
    $( "#select" ).hide();
    $( "#search" ).fadeToggle( "slow" );
  });
  
  /* set up form handlers */
  function form_handlers() {
    /* show/hide potential legal issues textarea */
    $( "#pliCheck" ).click( function() {
      if( $( "#pliCheck" ).attr( "checked" ) ) {
        $( "#pliText" ).show();
      } else {
        $( "#pliText" ).hide();
      }
    });
    
    /* show/hide other contact type text box */
    $( ".contact-type" ).click( function() {
      if( $( "#cto" ).attr( "checked" ) ) {
        $( "#ctoText" ).css( "opacity", 1 );
      } else {
        $( "#ctoText" ).css( "opacity", 0 );
      }
    });
    
    /* activate datepicker */
    $( "#date" ).datepicker({ dateFormat: "yy-mm-dd" });
    $( "#date" ).datepicker( "setDate", new Date());
  }
  
  /* blank sheet button */
  $( ".blank-contact-sheet-button" ).click(function() {
    $( "#search" ).hide();
    $( "#select" ).hide();

    /* Load form fields and attach handlers */
    $( "#form-fields" ).load( "load_contact_sheet_form.php", form_handlers );
    
    $( "#add-contact-sheet-button" ).attr( "data-id", "" );
    $( "#view" ).fadeToggle( "slow" );
  });

  /* Clear button */
  $( 'button[type="reset"]' ).click(function() {
    $( "#add-contact-sheet-form-status" ).hide();
    $( "#add-contact-sheet-form-status" ).html( "" );
  });
  
  /* Validate form */
  var v = $( "#add-contact-sheet-form" ).validate({
    rules: {
      firstName: {
        required: true
      },
      lastName: {
        required: true
      },
      phone: {
        minlength: 10,
        maxlength: 10,
        digits: true
      },
      cell: {
        minlength: 10,
        maxlength: 10,
        digits: true
      },
      zipcode: {
        minlength: 5,
        maxlength: 5,
        digits: true
      },
      issues: {
        required: true
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
      phone: {
        minlength: "Phone number must be exactly 10 digits long.",
        maxlength: "Phone number must be exactly 10 digits long.",
        digits: "Phone number can only contain digits."
      },
      cell: {
        minlength: "Cell phone number must be exactly 10 digits long.",
        maxlength: "Cell phone number must be exactly 10 digits long.",
        digits: "Cell phone number can only contain digits."
      },
      zipcode: {
        minlength: "Zipcode must be exactly 5 digits long.",
        maxlength: "Zipcode must be exactly 5 digits long.",
        digits: "Zipcode can only contain digits."
      },
      issues: {
        required: "Issues is a required field."
      },
      date: {
        required: "Date is a required field.",
        date: "Date must be a valid date (yyyy-mm-dd)."
      }
    },
    errorPlacement: function( error, element ) {
      error.appendTo( $( "#add-contact-sheet-form-status" ) );
      $( "#add-contact-sheet-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#add-contact-sheet-form-status" ).hide();
        $( "#add-contact-sheet-form-status" ).html( "" );
      }
    }
  });
  
  /* Submit form if valid */
  $( "#add-contact-sheet-form" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $( "#add-contact-sheet-form-status" ).html( "" );
    $( "#add-contact-sheet-form-status" ).removeClass( "alert" );
    $( "#add-contact-sheet-form-status" ).removeClass( "alert-error" );
    $( "#add-contact-sheet-form-status" ).removeClass( "alert-success" );
    
    /* generate workplace id */    
    var workplace = $( "#workplace" ).val();
    var workplaceID = workplace.substring( workplace.length - 4 );
    
    /* generate post string */
    var postString = $( "#add-contact-sheet-form" ).serialize() + "&organizer=" + $( "#organizer" ).val() + "&id=" + $( "#add-contact-sheet-button" ).attr( "data-id" ) + "&wid=" + workplaceID;

    $.post(
      "action_contact_sheet_form.php",
      postString,
      function( data, textStatus, jqXHR ) {
        $( "#add-contact-sheet-form-status" ).html( jqXHR.responseText );
        $( "#add-contact-sheet-form-status" ).show();
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#add-contact-sheet-form-status" ).addClass( "alert alert-error" );
        $( "#add-contact-sheet-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#add-contact-sheet-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + $( "#add-contact-sheet-form" ).serialize() + "&organizer=" + $( "#organizer" ).val() + "&id=" + $( "#add-contact-sheet-button" ).attr( "data-id" ) );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
