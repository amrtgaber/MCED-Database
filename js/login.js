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
        window.location = "index.php";
      }
    ).fail(function( data, s, jqXHR ) {
      $( "#error" ).html( "Incorrect username or password" );
      $( "#error" ).show();
    }).always( function( data, s, jqXHR ) {
      console.log( "Sent-->      " + $( "form" ).serialize() );
      console.log( "Received-->  " + jqXHR.responseText );
    });
    
    return false;
  });
});

