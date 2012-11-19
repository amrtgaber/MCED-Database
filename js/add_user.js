$( document ).ready(function() {
  var MOST_PRIVILEGE_IDX  = 4;
  var value;
  
  $( "#slider" ).slider({
    min: 0,
    max: 4,
    range: "min",
    value: 2,
    animate: "slow",
    create: function( e, u ) {
      value = $( "#slider" ).slider( "value" );
  
      for( var idx = value + 1; idx <= MOST_PRIVILEGE_IDX; idx++ ) {
        $( "#privilege-description li:eq(" + idx + ")" ).hide();
      }
    },
    change: function( e, u ) {
      var idx;

      if( u.value == value ) {
        return;
      } else  if( u.value > value ) {
        for( idx = value + 1; idx <= u.value; idx++ ) {
          $( "#privilege-description li:eq(" + idx + ")" ).fadeToggle( "slow" );
        }
      } else {
        for( idx = value; idx > u.value; idx-- ) {
          $( "#privilege-description li:eq(" + idx + ")" ).fadeToggle( "slow" );
        }
      }

      value = u.value;
    }
  });

  $( 'button[type="reset"]' ).click(function() {
    $( "#add-user-form-status" ).hide();
    $( "#add-user-form-status" ).html( "" );
  });

  var v = $( "form" ).validate({
    rules: {
      username: {
        required: true,
        minlength: 4
      },
      password: {
        required: true
      },
      confirmPassword: {
        required: true,
        equalTo: "#password"
      }
    },
    messages: {
      username: {
        required: "Username is required.",
        minlength: "Username must be at least 4 characters long."
      },
      password: {
        required: "Password is required."
      },
      confirmPassword: {
        required: "Confirm password is required.",
        equalTo: "Passwords must match."
      }
    },
    errorPlacement: function( error, element ) {
      error.appendTo( $( "#add-user-form-status" ) );
      $( "#add-user-form-status" ).show();
    },
    success: function() {
      if( v.numberOfInvalids() == 0 ) {
        $( "#add-user-form-status" ).hide();
        $( "#add-user-form-status" ).html( "" );
      }
    }
  });

  $( "form" ).submit(function() {
    if( !v.form() ) {
      return false;
    }
    
    $( "#add-user-form-status" ).html( "" );
    $( "#add-user-form-status" ).removeClass( "alert" );
    $( "#add-user-form-status" ).removeClass( "alert-error" );
    $( "#add-user-form-status" ).removeClass( "alert-success" );

    $.post(
      "action_add_user.php",
      $( "form" ).serialize() + "&privilegeLevel=" + $( "#slider" ).slider( "value" ),
      function( data, textStatus, jqXHR ) {
        $( "#add-user-form-status" ).html( jqXHR.responseText );
        $( "#add-user-form-status" ).show();
        $( "form" ).each(function () {
          this.reset();
        });
      }
      ).fail(function( data, textStatus, jqXHR ) {
        $( "#add-user-form-status" ).addClass( "alert alert-error" );
        $( "#add-user-form-status" ).html( "There was an unknown error in the server. "
          + "If you get this error more than once, "
          + "please try again later or contact jalhaj@mc-ed.org." );
        $( "#add-user-form-status" ).show();
      }
      ).always(function( data, textStatus, jqXHR ) {
        /* Debug */
        console.log( "Sent     --> " + $( "form" ).serialize() + "&privilegeLevel=" + $( "#slider" ).slider( "value" ) );
        console.log( "Received --> " + jqXHR.responseText );
      });

    return false;
  });
});

