var v;

/* set up form handlers */
function shop_profile_form_handlers() {
  /* Add worker search button */
  $( "#add-worker-search-button" ).click(function() {
    $( "#add-worker-search-results" ).load( "load_add_worker_search_table.php?firstName=" + $( "#firstName" ).val() + "&lastName=" + $( "#lastName" ).val(), function() {
      /* Add worker button */
      $( ".add-worker-button" ).click(function() {
        $( this ).removeClass( "btn-success" );
        $( this ).addClass( "btn-danger" );
        
        $( this ).children().removeClass( "icon-plus" );
        $( this ).children().addClass( "icon-minus" );
        
        $( this ).parent().parent().addClass( "worker" );
        $( this ).click(function() {
          $( this ).parent().parent().remove();
        });
        
        $( "#worker-table-body" ).append( $( this ).parent().parent() );
      });
    });
  });
  
  /* Add worker clear button */
  $( "#add-worker-clear-button" ).click(function() {
    $( "#firstName" ).val( "" );
    $( "#lastName" ).val( "" );
    $( "#add-worker-search-results" ).html( "" );
  });
  
  /* action buttons */
  $( "#save-button" ).click( submit_shop_profile_form );
  $( "#cancel-button" ).click( load_shop_profile_form );
  $( "#delete-confirm-button" ).click(function() {
    $.post( "action_delete_shop_profile.php", "wid=" + wid );
    window.location = "search_shop_profile.php";
  });
}

/* Validate form */
function validate_shop_profile_form() {
  v = $( "#shop-profile-form" ).validate({
    rules: {
      wname: {
        required: true
      },
      phone: {
        required: true,
        minlength: 10,
        maxlength: 10,
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
        minlength: "Phone number must be exactly 10 digits long.",
        maxlength: "Phone number must be exactly 10 digits long.",
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
      error.appendTo( $( "#shop-profile-form-status" ) );
      $( "#shop-profile-form-status" ).addClass( "alert" );
      $( "#shop-profile-form-status" ).addClass( "alert-error" );
      $( "#shop-profile-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#shop-profile-form-status" ).hide();
        $( "#shop-profile-form-status" ).html( "" );
      }
    }
  });
}

/* load form */
function load_shop_profile_form() {
  $( "#shop-profile-form" ).load( "load_shop_profile_form.php?wid=" + wid + "&add=" + add, shop_profile_form_handlers );
  validate_shop_profile_form();
}

/* Submit form if valid */
function submit_shop_profile_form() {
  if( !v.form() ) {
    return false;
  }

  $( "#shop-profile-form-status" ).html( "" );
  $( "#shop-profile-form-status" ).removeClass( "alert" );
  $( "#shop-profile-form-status" ).removeClass( "alert-error" );
  $( "#shop-profile-form-status" ).removeClass( "alert-success" );
  
  /* generate worker id list */
  var addWorkers = "";    
  $( ".worker" ).each(function() {
    addWorkers += $( this ).attr( "data-id" ) + ",";
  });
  
  /* remove trailing comma */
  if( addWorkers.length > 0 ) {
    addWorkers = addWorkers.substring( 0, addWorkers.length - 1 );
  }
  
  /* construct post request string */
  var postString = $( "#shop-profile-form" ).serialize()
                     + "&addWorkers=" + addWorkers
                     + "&wid=" + wid
                     + "&add=" + add;

  $.post(
    "action_shop_profile_form.php",
    postString,
    function( data, textStatus, jqXHR ) {
      $( "#shop-profile-form-status" ).html( jqXHR.responseText );
      $( "#shop-profile-form-status" ).show();
    }
    ).fail(function( data, textStatus, jqXHR ) {
      $( "#shop-profile-form-status" ).addClass( "alert alert-error" );
      $( "#shop-profile-form-status" ).html( "There was an unknown error in the server. "
        + "If you get this error more than once, "
        + "please try again later or contact jalhaj@mc-ed.org." );
      $( "#shop-profile-form-status" ).show();
    }
    ).always(function( data, textStatus, jqXHR ) {
      /* Debug */
      console.log( "Sent     --> " + postString );
      console.log( "Received --> " + jqXHR.responseText );
    });
  
  return false;
}
