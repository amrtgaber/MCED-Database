$( document ).ready(function() {
  $( "input" ).keydown(function() {
    $( "#error" ).hide();
    $( "#error" ).html( "" );
  });

  $( "form" ).submit(function() {
    $.post(
      "action_login.php", 
      $( "form" ).serialize(),
      function( data, s, jqXHR ) { 
        window.location = "home.php";  
      }
    ).fail(function( data, s, jqXHR ) {
      $( "#error" ).html( "Incorrect username or password" );
      $( "#error" ).show();
    });
    
    return false;
  });
});

