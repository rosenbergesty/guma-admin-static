/* Global js */
$(document).ready(function(e){

  function getCookie(name){
    var cookie = document.cookie;
    var prefix = name + "=";
    var begin = cookie.indexOf("; " + prefix);
    if(begin == -1) {
      begin = cookie.indexOf(prefix);
      if(begin != 0) return null;
    } else {
      begin += 2;
      var end = document.cookie.indexOf(";", begin);
      if(end == -1) {
        end = cookie.length;
      }
    }
    return decodeURI(cookie.substring(begin + prefix.length, end));
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