if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false) {
  $('#play-button').show();
}

var User = {
  Contact : function() {
    ajax.get('/api/' + API_VERSION + '/', 'Method=UserContact&FullName=' + $('#contact-fullname').val() + '&Email=' + $('#contact-email').val() + '&Message=' + $('#contact-message').val(), function(response) {
      if (response.result === 'success') {
        $('#contact-fullname').val('');
        $('#contact-email').val('');
        $('#contact-message').val('');
        alert('Thank you for contacting us! We will get back to you as soon as we can!');
      } else {
        var errors = '';
        $.each(response.errors, function(key, value) {
          errors += value + '\n';
        });
        alert(errors);
      }
    });
  },
  Login : function() {
    ajax.get('/api/' + API_VERSION + '/', 'Method=UserLogin&Email=' + $('#login-email').val() + '&Password=' + $('#login-password').val(), function(response) {
      if (response.result === 'success') {
        document.location = './login.php';
      } else {
        var errors = '';
        $.each(response.errors, function(key, value) {
          errors += value + '\n';
        });
        alert(errors);
      }
    });
  },
  Register : function() {
    ajax.get('/api/' + API_VERSION + '/', 'Method=UserRegister&FirstName=' + $('#register-firstname').val() + '&LastName=' + $('#register-lastname').val() + '&Email=' + $('#register-email').val() + '&Password=' + $('#register-password').val() + '&Password2=' + $('#register-password2').val(), function(response) {
      if (response.result === 'success') {
        $('#register-firstname').val('');
        $('#register-lastname').val('');
        $('#register-email').val('');
        $('#register-password').val('');
        $('#register-password2').val('');
        nav.changePage('payment');
      } else {
        var errors = '';
        $.each(response.errors, function(key, value) {
          errors += value + '\n';
        });
        alert(errors);
      }
    });
  },
  Unsubscribe : function() {
    ajax.get('/api/' + API_VERSION + '/', 'Method=UserUnsubscribe&Password=' + $('#subscription-password').val(), function(response) {
      if (response.result === 'success') {
        alert('Your subscription has been removed.  We\'re sad to see you go!  If you would like to reinstate your account at any time, please email us at Worlddanceu@gmail.com.');
        document.location = './logout.php';
      } else {
        var errors = '';
        $.each(response.errors, function(key, value) {
          errors += value + '\n';
        });
        alert(errors);
      }
    });
  },
  Update : function() {
    ajax.get('/api/' + API_VERSION + '/', 'Method=UserUpdate&Password1=' + $('#account-password1').val(), function(response) {
      if (response.result === 'success') {
        alert('Your account settings have been updated.');
      } else {
        var errors = '';
        $.each(response.errors, function(key, value) {
          errors += value + '\n';
        });
        alert(errors);
      }
    });
  }
}
