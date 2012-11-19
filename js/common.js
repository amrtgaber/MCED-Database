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
        error.appendTo( $( "#change-password-form-status" ) );
        $( "#change-password-form-status" ).show();
      },
      success: function() {
        if( v.numberOfInvalids() == 0 ) {
          $( "#change-password-form-status" ).hide();
          $( "#change-password-form-status" ).html( "" );
        }
      }
    });

    $( "#change-password-form" ).submit(function() {
      if( !v.form() ) {
        return false;
      }

      $( "#change-password-form-status" ).html( "" );
      $( "#change-password-form-status" ).removeClass( "alert" );
      $( "#change-password-form-status" ).removeClass( "alert-error" );
      $( "#change-password-form-status" ).removeClass( "alert-success" );

      $.post(
        "action_change_password.php",
        $( "#change-password-form" ).serialize(),
        function( data, textStatus, jqXHR ) {
          $( "#change-password-form-status" ).html( jqXHR.responseText );
          $( "#change-password-form-status" ).show();
          $( "#change-password-form" ).each(function() {
            this.reset();
          });
        }
        ).fail(function( data, textStatus, jqXHR ) {
          $( "#change-password-form-status" ).html( jqXHR.responseText );
          $( "#change-password-form-status" ).show();
        }
        ).always(function( data, textStatus, jqXHR ) {
          /* Debug */
          console.log( "Sent     --> " + $( "#change-password-form" ).serialize() );
          console.log( "Received --> " + jqXHR.responseText );
        });

      return false;
    });

    $( "#change-password-modal" ).on( "hidden", function() {
      $( "#change-password-form-status" ).hide();
      $( "#change-password-form-status" ).html( "" );
    });
  });

  $( "#sidebar" ).load( "load_sidebar.php" );
  $( "#footer" ).load( "load_footer.php" );
});
