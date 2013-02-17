$( document ).ready(function() {
  if( quickSearch ) {
    $( "#search" ).hide();
    $( "#selectTable" ).load( "load_select_contact.php?" + $( "#search" ).serialize(), function() {
      $( "input[type=radio]:first" ).attr( "checked", true );
    });
    $( "#select" ).fadeToggle( "slow" );
  }
  
  /*Shortcut to View if ContactID is provided in querystring*/
  if(quickView){
    $( "#search" ).hide();
    $( "#contactInfo" ).load( "load_contact_profile.php?id=" + quickid );
    $( "#updateButton" ).attr( "data-id", $( "input[type=radio]:checked" ).val() );
    $( "#view" ).fadeToggle( "slow" );
  }

  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search" ).hide();
    $( "#selectTable" ).load( "load_select_contact.php?" + $( "#search" ).serialize(), function() {
      $( "input[type=radio]:first" ).attr( "checked", true );
    });
    $( "#select" ).fadeToggle( "slow" );
    
    return false;
  });
  
  /* Back to search */
  $( "#backToSearch" ).click(function() {
    $( "#select" ).hide();
    $( "#search" ).fadeToggle( "slow" );
  });

  /* Select button */
  $( "#selectButton" ).click(function() {
    $( "#select" ).hide();

    $( "#contactInfo" ).load( "load_contact_profile.php?id=" + $( "input[type=radio]:checked" ).val() );
    $( "#updateButton" ).attr( "data-id", $( "input[type=radio]:checked" ).val() );
    $( "#view" ).fadeToggle( "slow" );
  });

  /* Back to select */
  $( "#backToSelect" ).click(function() {
    $( "#view" ).hide();
    $( "#select" ).fadeToggle( "slow" );
    
    $( "#view" ).each(function () {
      this.reset();
    });
  });

  /* update button */
  $( "#updateButton" ).click(function() {
    window.location = "modify_contact.php?id=" + $( "#updateButton" ).attr( "data-id" );
  });
});
