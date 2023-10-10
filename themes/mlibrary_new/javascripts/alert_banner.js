$(document).ready(function() {
  var cname = "SurveyBannerCookie";
  if (CheckCookie(cname)) {
    $('#survey-alert-banner').show();
  } else {
    $('#survey-alert-banner').hide();
  }

  function CheckCookie(cname) {
    var c = parseInt(getCookie(cname));
    if(isNaN(c)){
      c = 0;
    }
    setCookie(cname,c+1);
    return(c<2);
  }

  function setCookie(cname, cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + (7 * 24 * 60 * 60 * 1000)); /* 7 days */
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
  }

  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1);
      if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
  }
});
