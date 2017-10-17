/* Global js */
$(document).ready(function(e){

  // Check if logged in
  function getCookie(name){
    var cookie = document.cookie;
    var prefix = name + "=";
    var begin = cookie.indexOf("; " + prefix);
    if(begin == -1) {
      begin = dc.indexOf(prefix);
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

  function checkLogin(){
    // var cookie = getCookie("")
  }
});