var nav = {
  curpage : 1,
  maxpage : 16,
  changePage : function(tab) {
    if (tab !== '') {
      isShowingVideo = false;
      autoHideNav(false);
      $('.tab').css('display', 'none');
      $('.content').css({'display' : 'block'});
      $('.content').animate({opacity:'+=100'}, 200);
      $('#' + tab).css('display', 'block');
      nav.close();
    }
  },
  close : function() {
    $('.login-box').fadeOut(200);
    $('.nav').animate({height:'100'}, 200);
  },
  closeMobile : function() {
  },
  closeTab : function() {
      $('.tab').css('display', 'none');
      $('.content').css({'display' : 'none'});
  },
  filterVideos : function() {
    nav.curPage = 1;
    var $videos = $('#dance-type');
    var $playlist = $('#playlist');
    var $placeholder = $('#playlist-placeholder');
    $playlist.show();
    $placeholder.hide();
    $playlist.children('a').each(function () {
      if ($(this).data('album') == $videos.val()) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  },
  next : function() {
    nav.curpage++;
    if (nav.curpage > nav.maxpage) {
      nav.curpage = nav.maxpage;
    }
    $('#navpage').html(nav.curpage);
  },
  open : function() {
    $('.nav').animate({height:'+=80'}, 500, function(){
      $('.login-box').fadeIn('slow');
    });
    $('.content').animate({opacity:'0'}, 500);
  },
  openMobile : function() {
  },
  prev : function() {
    nav.curpage--;
    if (nav.curpage < 1) {
      nav.curpage = 1;
    }
    $('#navpage').html(nav.curpage);
  },
  viewed : function(id) {
    $('#viewed-overlay-' + id).show();
    $.cookie('viewed-overlay-' + id, 'true', { expires: 1200 });
  }
}

var isTouch = Modernizr.touch,
autoHideTimer,
navIsHidden = false,
isDefaultBackground = false,
isShowingVideo = false;

function autoHideNav(enable) {
  if (!isTouch) {
    if (enable) {
      navIsHidden = true;
      $('body').on('mousemove.autohide', function(event) {
        if (navIsHidden) {
          navIsHidden = false;
          showNav();
        }
        clearTimeout(autoHideTimer);
        autoHideTimer = setTimeout(function() {
          navIsHidden = true;
          hideNav();
        }, 1000);
      });
    } else {
      clearTimeout(autoHideTimer);
      $('body').off('mousemove.autohide');
      showNav();
    }
  }
}
function showNav() {
  $('.nav').removeClass('transparent');
  $('.logo').removeClass('transparent');
}

function hideNav() {
  $('.nav').addClass('transparent');
  $('.logo').addClass('transparent');
}
