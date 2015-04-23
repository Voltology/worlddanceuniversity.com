var Contact = {
  Delete : function(id) {
    if (confirm("Are you sure you want to archive this message?")) {
      document.location = '?p=contact&a=archive&id=' + id;
    }
  }
}

var User = {
  Cancel : function(id) {
    if (confirm("Are you sure you want to cancel this user\'s Paypal token?  Revoking the Paypal token will permanently block them from logging in and viewing videos.")) {
      document.location = '?p=edit-user&a=cancel&id=' + id;
    }
  },
  Delete : function(id) {
    if (confirm("Are you sure you want archive this user?")) {
      document.location = '?p=users&a=archive&id=' + id;
    }
  },
  Reactivate : function(id) {
    if (confirm("You are about to reactivate this user\'s account.  Are you sure you want to do this?")) {
      document.location = '?p=edit-user&a=reactivate&id=' + id;
    }
  },
  Suspend : function(id) {
    if (confirm("Are you sure you want to suspend this user\'s Paypal token?  Suspending the Paypal token will temporarily block them from logging in and viewing videos. It can be reactivated later.")) {
      document.location = '?p=edit-user&a=suspend&id=' + id;
    }
  }
}

var video = {
  addKey : function(vimeo_id) {
    $('#add-key-' + vimeo_id).before('<div id="add-key-container-' + vimeo_id + '"><input type="text" id="add-key-text-' + vimeo_id + '" /> <button type="button" class="button" onclick="video.saveKey(\'' + vimeo_id + '\');">Add</button><button type="button" class="button" onclick="video.cancelKey(\'' + vimeo_id + '\');">Cancel</button></div>');
    $('#add-key-' + vimeo_id).hide();
    $('#add-key-text-' + vimeo_id).focus();
    $('#add-key-text-' + vimeo_id).bind('keypress', function(e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if (code == 13) {
        video.saveKey(vimeo_id);
      }
    });
  },
  cancelKey : function(vimeo_id) {
    $('#add-key-' + vimeo_id).show();
    $('#add-key-container-' + vimeo_id).remove();
  },
  editKey : function(vimeo_id) {
    $('#add-key-' + vimeo_id).before('<div id="add-key-container-' + vimeo_id + '"><input type="text" id="add-key-text-' + vimeo_id + '" /> <button type="button" class="button" onclick="video.saveKey(\'' + vimeo_id + '\');">Save</button><button type="button" class="button" onclick="video.cancelKey(\'' + vimeo_id + '\');">Cancel</button></div>');
    $('#add-key-' + vimeo_id).hide();
    $('#add-key-text-' + vimeo_id).focus();
    $('#add-key-text-' + vimeo_id).bind('keypress', function(e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if (code == 13) {
        video.saveKey(vimeo_id);
      }
    });
  },
  saveKey : function(vimeo_id) {
    var key = $('#add-key-text-' + vimeo_id).val();
    if (key.match(/=/g)) {
      key = key.split('=')[1];
    }
    ajax.get('/api/' + API_VERSION + '/', '&Method=SaveKey&vimeo_id=' + vimeo_id + '&definition=' + $('#definition-' + vimeo_id).val() + '&key=' + key, function(response) {
      if (response.result === 'success') {
        $('#add-key-' + vimeo_id).html('<div id="add-key-' + vimeo_id + '">' + key  + ' <i class="icon-ok" style="color: #0d0"></i> [<a href=\"#\" onclick=\"video.editKey(' + vimeo_id + ');\"><i class=\"icon-pencil\"></i> Edit</a>]</div>');
        $('#add-key-container-' + vimeo_id).remove();
        $('#add-key-' + vimeo_id).show();
        alert('The video key was successfully saved!');
      } else {
        var errors = '';
        $.each(response.errors, function(key, value) {
          errors += value + '\n';
        });
        alert(errors);
      }
    });
  }
};
