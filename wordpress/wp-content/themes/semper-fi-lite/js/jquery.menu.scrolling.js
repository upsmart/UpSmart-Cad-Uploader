$(document).ready(function() {

  $(window).scroll(function(){
    $('#header').addClass('hidden');
    switchHeader();
  });
   
});

function switchHeader(){
  if (document.documentElement.clientWidth <= 400){
    if ($(window).scrollTop() <= 65){
      $('#header').removeClass('hidden');
    }
  }
  else if (document.documentElement.clientWidth <= 600){
    if ($(window).scrollTop() <= 90){
      $('#header').removeClass('hidden');
    }
  }
  else if (document.documentElement.clientWidth <= 900){
    if ($(window).scrollTop() <= 118){
      $('#header').removeClass('hidden');
    }
  }
  else {
    if ($(window).scrollTop() <= 151){
      $('#header').removeClass('hidden');
    }
  }

}