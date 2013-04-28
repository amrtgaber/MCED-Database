var v;

/* set up for form handlers */
function contact_sheet_form_handlers() {
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
  
  /* save and cancel buttons */
  $( "#save-button" ).click( submit_contact_sheet_form );
  $( "#cancel-button" ).click( load_contact_sheet_form );
}

/* Validate form */
function validate_contact_sheet_form() {
  v = $( "#contact-sheet-form" ).validate({
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
      error.appendTo( $( "#contact-sheet-form-status" ) );
      $( "#contact-sheet-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#contact-sheet-form-status" ).hide();
        $( "#contact-sheet-form-status" ).html( "" );
      }
    }
  });
}

/* load form */
function load_contact_sheet_form() {
  $( "#contact-sheet-form" ).load( "load_contact_sheet_form.php?id=" + id + "&add=" + add, contact_sheet_form_handlers );
  validate_contact_sheet_form();
}

/* Submit form if valid */
function submit_contact_sheet_form() {
  if( !v.form() ) {
    return false;
  }

  $( "#contact-sheet-form-status" ).html( "" );
  $( "#contact-sheet-form-status" ).removeClass( "alert" );
  $( "#contact-sheet-form-status" ).removeClass( "alert-error" );
  $( "#contact-sheet-form-status" ).removeClass( "alert-success" );
  
  /* generate post string */
  var postString = $( "#contact-sheet-form" ).serialize()
                     + "&oid=" + $( "#organizer option:selected" ).attr( "data-oid" )
                     + "&wid=" + $( "#workplace option:selected" ).attr( "data-wid" )
                     + "&id=" + id
                     + "&add=" + add;

  $.post(
    "action_contact_sheet_form.php",
    postString,
    function( data, textStatus, jqXHR ) {
      $( "#contact-sheet-form-status" ).html( jqXHR.responseText );
      $( "#contact-sheet-form-status" ).show();
    }
    ).fail(function( data, textStatus, jqXHR ) {
      $( "#contact-sheet-form-status" ).addClass( "alert alert-error" );
      $( "#contact-sheet-form-status" ).html( "There was an unknown error in the server. "
        + "If you get this error more than once, "
        + "please try again later or contact jalhaj@mc-ed.org." );
      $( "#contact-sheet-form-status" ).show();
    }
    ).always(function( data, textStatus, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + postString );
      console.log( "Received --> " + jqXHR.responseText );
    });
  
  return false;
}
