/* Global js */
$(document).ready(function(e){

  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function delCookie(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  };

  // Hide loader
  setTimeout(function(){
    $('.loader-screen').fadeOut("fast", function(){});
  }, 100);

  // Check if logged in
  function checkLogin(){
    var cookie = getCookie("user");
    if(cookie != null && cookie.length > 0){
      var user = JSON.parse(cookie);
      var initial = user.username.charAt(0);
      $('.user .icon').text(initial.toUpperCase());
    } else {
      window.location.href = "index.html";
    }
  }
  checkLogin();

  // Minimize Nav
  $('.nav-opener').click(function(e){
    $('nav').css('width', '80px');
    $('nav').toggleClass('w-25');
    $('nav .nav-link .text').toggle();

    $('.content').css('width', 'calc(100% - 80px)');
    $('.content').toggleClass('w-75');

    var open = !(getCookie("nav") == 'true');
    if(open === null) {open = false;}
    document.cookie = "nav=" + open;
  });

  if(getCookie("nav") == 'true'){
    $('nav').css('width', '80px');
    $('nav').toggleClass('w-25');
    $('nav .nav-link .text').toggle();    

    $('.content').css('width', 'calc(100% - 80px)');
    $('.content').toggleClass('w-75');
  }

  // Logout Popup
  $('.user').click(function(e){
    $('.logout-popup').toggle();
      e.stopPropagation();
  });
  $(window).click(function() {
    $('.logout-popup').hide();
  });
  $('.logout-popup').click(function(e){
    e.stopPropagation();
  });

  // Logout
  $('#logout').click(function(e){
    e.preventDefault();

    delCookie("user");
    delCookie("nav");

    window.location.href="index.html";
  });

});