var ajax = {
  get : function(url, query, callback) {
    jQuery.ajax({
      type: 'POST',
      url: url,
      data: query,
      dataType: 'json',
      success: function(response) {
        callback(response);
      }
    });
  },
  send : function(url, query) {
    jQuery.ajax({
      type: 'GET',
      url: url,
      data: query,
      dataType: 'json'
    });
  }
};
