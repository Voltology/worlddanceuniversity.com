var HOSTNAME = '';
var API_VERSION = 'v0.1';

if ("onhashchange" in window) {
  window.onhashchange = function () {
    nav.changePage(window.location.hash.substr(1, window.location.hash.length));
  }
} else {
  var storedHash = window.location.hash;
  window.setInterval(function () {
    if (window.location.hash != storedHash) {
      storedHash = window.location.hash;
      nav.changePage(storedHash.substr(1, window.location.hash.length));
    }
  }, 100);
}

$(document).ready(function() {
  nav.changePage(window.location.hash.substr(1, window.location.hash.length));
  $('#login-email, #login-password').bind('keypress', function(event) {
    if(event.which == 13) {
      User.Login();
    }
  });
  $('.viewed-overlay').each(function(key, value) {
    var id = $(this).attr('id');
    if ($.cookie(id) == 'true') {
      $('#' + id).show();
    }
  });
});
