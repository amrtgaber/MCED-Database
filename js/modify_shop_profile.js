$( document ).ready(function() {

  /*Load with id passed from view page*/
  if( quickSelect ) {
    $( "#search" ).hide();

    $( "#formFields" ).load( "load_shop_profile_form.php?id=" + quickId, function() {
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
    });

    $( "#updateButton" ).attr( "data-id", quickId );
    $( "#update" ).fadeToggle( "slow" );
  }
  
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search" ).hide();
    
    $( "#selectTable" ).load( "load_select_shop_profile.php?" + $( "#search" ).serialize(), function() {        
      $( ".shop" ).click(function() {
        $( "#select" ).hide();

        $( "#formFields" ).load( "load_shop_profile_form.php?id=" + $( this ).attr( "data-wid" ), function() {
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

  /* Validate form */
  var v = $( "#update" ).validate({
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
