$( document ).ready(function() {
  var MOST_PRIVILEGE_IDX  = 4;
  var value;
  
  $( "#slider" ).slider({
    min: 0,
    max: 4,
    range: "min",
    value: 1,
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

});
