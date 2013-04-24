$( document ).ready(function() {
  /* Search button */
  $( "#search" ).submit(function() {
    $( "#search" ).hide();
    
    $( "#selectTable" ).load( "load_select_shop_profile.php?" + $( "#search" ).serialize(), function() {
      $( ".shop" ).click(function() {
        $( "#select" ).hide();

        $( "#shopInfo" ).load( "load_shop_profile.php?id=" + $( this ).attr( "data-wid" ) );
        $( "#updateButton" ).attr( "data-id", $( this ).attr( "data-wid" ) );
        $( "#view" ).fadeToggle( "slow" );
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
    $( "#view" ).hide();
    $( "#select" ).fadeToggle( "slow" );
    
    $( "#view" ).each(function () {
      this.reset();
    });
  });

  /* update button */
  $( "#updateButton" ).click(function() {
    window.location = "modify_shop_profile.php?id=" + $( "#updateButton" ).attr( "data-id" );
  });
});
