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
    $( "#error" ).html( "" );
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
      error.appendTo( $( "#error" ) );
    }
  });

  $( "form" ).submit(function() {
    if( !v.form() ) {
      return false;
    }

    $.post(
      "add_user_action.php",
      $( "form" ).serialize() + "&privilegeLevel=" + $( "#slider" ).slider( "value" ),
      function( data, s, jqXHR ) {
        var response = jqXHR.responseText.trim();

        if( response == "Success" ) {
          alert( "Success! "
            + $( "input[name=username]" ).val()
            + " is now a user and can login immediately." );
        } else if( response == "Invalid Username" ) {
          alert( "Username is a required field.");
        } else if( response == "Invalid Username Length" ) {
          alert( "Username must be at least 4 characters long.");
        } else if( response == "Invalid Password" ) {
          alert( "Password is a required field." );
        } else if( response == "Invalid Confirm Password" ) {
          alert( "Passwords must match." );
        } else if( response == "Invalid Privilege Level" ) {
          alert( "Privilege level is invalid." );
        } else if( response == "Duplicate Entry" ) {
          alert( "That user already exists in the database. Please choose a different username." );
        } else if( response == "SQL Error" ) {
          alert( "There was an error with the database. If you get this response more than once, "
            + "please try again later or contact admin@debrijja.com" );
        } else if( response == "Unauthorized" ) {
          alert( "You must be logged in to add a user." );
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
      console.log( "Sent     --> " + $( "form" ).serialize() + "&privilegeLevel=" + $( "#slider" ).slider( "value" ) );
      console.log( "Received --> " + jqXHR.responseText );
    });

    return false;
  });
});

