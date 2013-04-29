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
  
  /* action buttons */
  $( "#save-button" ).click( submit_contact_sheet_form );
  $( "#cancel-button" ).click( load_contact_sheet_form );
  $( "#delete-confirm-button" ).click(function() {
    $.post( "action_delete_contact_sheet.php", "csid=" + csid );
    window.location = "search_contact_sheet.php";
  });
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
  $( "#contact-sheet-form" ).load( "load_contact_sheet_form.php?csid=" + csid + "&add=" + add, contact_sheet_form_handlers );
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
  
  /* get valid wid */
  var wid = "";
  if( $( "#workplace option:selected" ).attr( "data-wid" ) != undefined ) {
    wid = $( "#workplace option:selected" ).attr( "data-wid" );
  }
  
  /* construct post request string */
  var postString = $( "#contact-sheet-form" ).serialize()
                     + "&oid=" + $( "#organizer option:selected" ).attr( "data-oid" )
                     + "&wid=" + wid
                     + "&csid=" + csid
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
