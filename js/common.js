$( document ).ready(function() {
  $( "#navbar" ).load( "load_navbar.php", function() {
    $( "#logout" ).click(function() {
      $.post(
        'logout.php', 
        function() { 
          window.location = "login.php";  
        }
      );
    });

    $( "#change-password-menu" ).click(function() {
      $( "#change-password-modal" ).modal( "show" );
    });

    var v = $( "#change-password-form" ).validate({
      rules: {
        newPassword: {
          required: true
        },
        confirmNewPassword: {
          required: true,
          equalTo: "#newPassword"
        }
      },
      messages: {
        newPassword: {
          required: "New password is a required field."
        },
        confirmNewPassword: {
          required: "Confirm new password is a required field.",
          equalTo: "Passwords must match."
        }
      },
      errorPlacement: function( error, element ) {
        error.appendTo( $( "#change-password-form-invalid" ) );
        $( "#change-password-response" ).hide();
        $( "#change-password-response" ).html( "" );
        $( "#change-password-form-invalid" ).show();
      },
      success: function() {
        if( v.numberOfInvalids() == 0 ) {
          $( "#change-password-form-invalid" ).hide();
          $( "#change-password-form-invalid" ).html( "" );
        }
      }
    });

    $( "#change-password-form" ).submit(function() {
      if( !v.form() ) {
        return false;
      }

      $( "#change-password-form-invalid" ).hide();
      $( "#change-password-form-invalid" ).html( "" );

      $.post(
        "action_change_password.php",
        $( "#change-password-form" ).serialize(),
        function( data, textStatus, jqXHR ) {
          $( "#change-password-form-response" ).html( jqXHR.responseText );
          $( "#change-password-form-response" ).show();
          $( "#change-password-form" ).each(function() {
            this.reset();
          });
        }
        ).fail(function( data, textStatus, jqXHR ) {
          $( "#change-password-form-response" ).html( jqXHR.responseText );
          $( "#change-password-form-response" ).show();
        }
        ).always(function( data, textStatus, jqXHR ) {
          /* Debug */
          console.log( "Sent     --> " + $( "#change-password-form" ).serialize() );
          console.log( "Received --> " + jqXHR.responseText );
        });

      return false;
    });

    $( "#change-password-modal" ).on( "hidden", function() {
      $( "#change-password-form-response" ).hide();
      $( "#change-password-form-response" ).html( "" );
    });
  });

  $( "#sidebar" ).load( "load_sidebar.php" );
  $( "#footer" ).load( "load_footer.php" );
});
