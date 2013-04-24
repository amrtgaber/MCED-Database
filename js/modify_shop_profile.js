$( document ).ready(function() {

  /*Load with id passed from view page*/
  if( quickSelect ) {
    $( "#search" ).hide();

    $( "#formFields" ).load( "load_shop_profile_form.php?id=" + quickId );
    $( "#updateButton" ).attr( "data-id", quickId );
    $( "#update" ).fadeToggle( "slow" );
  }
  
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search" ).hide();
    
    $( "#selectTable" ).load( "load_select_shop_profile.php?" + $( "#search" ).serialize(), function() {        
      $( ".shop" ).click(function() {
        $( "#select" ).hide();

        $( "#formFields" ).load( "load_shop_profile_form.php?id=" + $( this ).attr( "data-wid" ) ,function() {
          /* Add worker button */
          $( "#add-worker-button" ).click(function() {
            var workerToAdd = $( "#addWorker" ).val();
            
            var workerID = workerToAdd.substring( workerToAdd.length - 5 );
            var workerInfo = workerToAdd.substring( 0, workerToAdd.length - 7 );
            
            $( "#added-workers" ).append( "<div class='row-fluid worker' data-id='" + workerID + "'>" + workerInfo + "<button type='button' class='close' onclick='$( this ).parent().remove();'>&times;</button></div>" );
            
            $( "#addWorker" ).val( "" );
          }); 
        });
        
        $( "#updateButton" ).attr( "data-id", $( this ).attr( "data-wid" ) );
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
  
    jQuery.validator.addMethod( "phoneLength", function( phone_number, element ) {
        phone_number = phone_number.replace(/\s+/g, ""); 
        return this.optional( element ) || phone_number.length == 10;
  }, "Phone number must be 10 digits long." );

  /* Validate form */
  var v = $( "#update" ).validate({
    rules: {
      wname: {
        required: true
      },
      phone: {
        required: true,
        phoneLength: true,
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
        phoneLength: "Phone number must be exactly 10 digits long.",
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
      error.appendTo( $( "#edit-shop-profile-form-status" ) );
      $( "#edit-shop-profile-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#edit-shop-profile-form-status" ).hide();
        $( "#edit-shop-profile-form-status" ).html( "" );
      }
    }
  });
  
  /* Submit form if valid */
  $( "#update" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $( "#edit-shop-profile-form-status" ).html( "" );
    $( "#edit-shop-profile-form-status" ).removeClass( "alert" );
    $( "#edit-shop-profile-form-status" ).removeClass( "alert-error" );
    $( "#edit-shop-profile-form-status" ).removeClass( "alert-success" );
    
    /* generate worker id list */
    var addWorkers = "";    
    $( ".worker" ).each(function() {
      addWorkers += $( this ).attr( "data-id" ) + ",";
    });
    
    /* remove trailing comma */
    if( addWorkers.length > 0 ) {
      addWorkers = addWorkers.substring( 0, addWorkers.length - 1 );
    }

    $.post(
      "action_shop_profile_form.php",
      $( "#update" ).serialize() + "&id=" + $( "#updateButton" ).attr( "data-id" ) + "&addWorkers=" + addWorkers,
      function( data, textStatus, jqXHR ) {
        $( "#edit-shop-profile-form-status" ).html( jqXHR.responseText );
        $( "#edit-shop-profile-form-status" ).show();
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#edit-shop-profile-form-status" ).addClass( "alert alert-error" );
        $( "#edit-shop-profile-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#edit-shop-profile-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + $( "#update" ).serialize() + "&id=" + $( "#updateButton" ).attr( "data-id" ) + "&addWorkers=" + addWorkers );
        console.log( "Received --> " + jqXHR.responseText );
      });
    
    return false;
  });
});
