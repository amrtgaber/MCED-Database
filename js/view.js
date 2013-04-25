$( document ).ready(function() {
  $( "#generate-list" ).click(function() {
    window.location = "generate_list.php";
  });

  $( "#view-shop-profile" ).click(function() {
    window.location = "search_shop_profile.php";
  });
  
  $( "#view-actions" ).click(function() {
    window.location = "search_actions.php";
  });
  
  $( "#search-contact" ).click(function() {
    window.location = "search_contact.php";
  });
  
  $( "#view-contact-sheet" ).click(function() {
    window.location = "view_contact_sheet.php";
  });
});
