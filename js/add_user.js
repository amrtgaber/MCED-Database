$( document ).ready(function() {
  $( "#slider" ).slider({
    min: 1,
    max: 3,
    range: "max",
    value: 3,
    animate: "slow"
  });
});
