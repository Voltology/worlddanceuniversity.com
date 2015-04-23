<?php
include(".local.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>World Dance University</title>
    <meta name="description" content="World Dance University" />
    <meta name="author" content="World Dance University, developed by Populous Digital" />
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/font-awesome.css">
    <link rel="stylesheet" href="./js/vendor/BigVideo/css/bigvideo.css">
  </head>
  <body>
    <div class="page">
      <img src="img/ico-play.png" id="play-button" alt="Play Video" />
      <div class="logo" onclick="document.location='./'">
        <img src="img/logo.png" alt="World Dance University" />
      </div>
      <div class="nav">
        <ul>
          <li onclick="document.location='#about'; nav.changePage('about');">About Us</li>
          <li onclick="document.location='#dances'; nav.changePage('dances');">Dances</li>
          <li onclick="document.location='#contact'; nav.changePage('contact');">Contact Us</li>
          <?php if ($user->getIsLoggedIn()) { ?>
          <li onclick="document.location='#account'; nav.changePage('account');">My Account</li>
          <?php if ($user->getRole() == "2") { ?>
          <li onclick="document.location='/admin/'">Admin Area</li>
          <?php } ?>
          <li onclick="document.location='logout.php';">Sign Out</li>
          <?php } else { ?>
          <li onclick="document.location='#join'; nav.changePage('join');">Join Us</li>
          <li onclick="document.location='#'; nav.open()">Sign In</li>
          <?php } ?>
        </ul>
        <div class="login-box">
          <button onclick="User.Login()" type="submit" tabindex="3">Sign In</button>
          <input type="password" name="login-password" id="login-password" tabindex="2" placeholder="Password" />
          <input type="text" name="login-email" id="login-email" tabindex="1" placeholder="Email" />
          <div style="float: right;">
            <h2>Welcome Back!&nbsp;&nbsp;</h2>
          </div>
          <div style="position: absolute; top: 70px; right: 5px;">Don't have an account?  <span class="link" onclick="nav.close(); document.location='#join'">Click here</span> to sign up!</div>
        </div>
      </div>
      <div class="nav-button">
        <i class="icon-chevron-down"></i>
      </div>
      <div class="content">
        <div class="tab-close" onclick="nav.closeTab();"><i class="icon-remove"></i></div>
        <div class="tab" id="about">
          <h2>About Us</h2>
          <p>World Dance University is the premier online dance education program focused on making high quality dance education accessible, convenient, and fun. Build your confidence, enhance your existing skills, and learn new dances with our highly personalized, flexible approach.</p>
          <p>You choose the dance, the time, and the place. Self guided and paced video lessons featuring award-winning master dancers offer the quality of studio classes at the best possible value.</p>
          <p>Our exclusive virtual education program utilizes our Core Step method. Each dance is broken down, filmed, and explained at its fundamental Core Steps so that you scaffold skills and the routine. An innovative jump-back feature on each video allows you to take a step back, not start all over, whenever you need it.</p>
          <p>World Dance University brings world-class dance education to you.</p>
        </div>
        <div class="tab" id="dances">
          <h2>Featured Dances</h2>
          <p>Select from any of World Dance University’s ever-growing roster of dances. Watch expert instructors explain each step using our Core Steps teaching method. Learn a new dance anytime and anywhere.</p>
          <p>
            Select a Dance Category: <select id="dance-type" onchange="nav.filterVideos();">
              <option>Categories</option>
              <?php
              $albums = Album::getAll();
              foreach ($albums as $album) {
                if ($album['title'] != "Cover Video") {
                  echo "<option value=\"" . $album['vimeo_id'] . "\">" . $album['title'] . "</option>";
                }
              }
              ?>
            </select>
            <div class="playlist-placeholder" id="playlist-placeholder">
              <p>To view the dance videos, please select a dance category from the dropdown menu above.</p>
            </div>
            <div class="playlist" id="playlist">
              <?php
              $videos = Video::getAll();
              $count = 0;
              foreach ($videos as $video) {
                if ($video['key'] !== "") {
                  if ($user->getIsLoggedIn()) {
                  ?>
                  <a href="//player.vimeo.com/external/<?php echo $video['vimeo_id']; ?>.<?php echo $video['definition']; ?>.mp4?s=<?php echo $video['key']; ?>" class="playlist-btn" data-album="<?php echo $video['album_id']; ?>" onclick="nav.viewed('<?php echo $video['id']; ?>');"><img src="<?php echo $video['thumbnail']; ?>" height="105" width="180" alt="<?php echo $video['title']; ?>" /><div style="vertical-align: top;"><?php echo $video['title']; ?></div><div class="viewed-overlay" id="viewed-overlay-<?php echo $video['id']; ?>" style="display: none;">Viewed</div></a>
                <?php
                    $count++;
                } else {
                ?>
                  <div onclick="alert('You must be logged in to view videos!')" data-album="<?php echo $video['album_id']; ?>" class="playlist-btn-inactive"><img src="<?php echo $video['thumbnail']; ?>" height="105" width="180" alt="<?php echo $video['title']; ?>" /><div class="playlist-title" style="cursor: pointer; margin: 5px 14px; vertical-align: top;"><?php echo $video['title']; ?></div></div>
                <?php
                }
//                  echo "<strong>" . $video['title'] . "</strong>";
                }
              }
              ?>
              <div class="playlist-pagination" id="playlist-pagination">
                &laquo; <a href="javascript:nav.prev()">Previous</a>&nbsp;&nbsp;&nbsp;Page <span id="navpage">1</span> of <span id="maxpage">16</span>&nbsp;&nbsp;&nbsp;<a href="javascript:nav.next();">Next</a> &raquo;
              </div>
            </div>
        </div>
        <?php if (!$user->getIsLoggedIn()) { ?>
        <div class="tab" id="join">
          <h2>Join Us - Step 1</h2>
          <form>
            <label for="register-firstname">First Name</label>
            <input type="text" name="register-firistname" id="register-firstname" />
            <label for="register-lastname">Last Name</label>
            <input type="text" name="register-lastname" id="register-lastname" />
            <label for="register-email">Email</label>
            <input type="text" name="register-email" id="register-email" />
            <label for="register-password">Password</label>
            <input type="password" name="register-password" id="register-password" />
            <label for="register-password2">Re-enter Password</label>
            <input type="password" name="register-password2" id="register-password2" />
            <button type="button" onclick="User.Register()">Complete Step 1</button>
          </form>
        </div>
        <div class="tab" id="payment">
          <h2>Payment - Step 2</h2>
          <form>
            <p>Thank you for your interest in World Dance University! Please click the button below to begin the payment process.  Upon completion of this next step, you will be automatically logged into the site with full access to our entire library of videos!</p>
            <?php $paypal->print_buy_button(); ?>
          </form>
        </div>
        <?php } ?>
        <div class="tab" id="contact">
          <h2>Contact Us</h2>
          <form>
            <label for="contact-fullname">Full Name</label>
            <input type="text" name="contact-fullname" id="contact-fullname" value="<?php if ($user->getIsLoggedIn()) { echo $user->getFirstName() . " " . $user->getLastName(); } ?>" />
            <label for="contact-email">Email</label>
            <input type="text" name="contact-email" id="contact-email" value="<?php if ($user->getIsLoggedIn()) { echo $user->getEmail(); } ?>" />
            <label for="contact-message">Message</label>
            <textarea name="contact-message" id="contact-message"></textarea>
            <button type="button" onclick="User.Contact();">Contact Us</button>
          </form>
        </div>
        <div class="tab" id="account">
          <h2>My Account</h2>
          <form>
            <label for="account-firstname">First Name</label>
            <input type="text" name="account-firstname" id="account-firstname" value="<?php echo $user->getFirstName(); ?>" />
            <label for="account-lastname">Last Name</label>
            <input type="text" name="account-lastname" id="account-lastname" value="<?php echo $user->getLastName(); ?>" />
            <label for="account-email">Email</label>
            <input type="text" name="account-email" id="account-email" value="<?php echo $user->getEmail(); ?>" />
            <label for="account-password1">New Password</label>
            <input type="password" name="account-password1" id="account-password1" />
            <label for="account-password2">Re-enter Password</label>
            <input type="password" name="account-password2" id="account-password2" />
            <button type="button" onclick="User.Update();">Update Info</button>
            <p style="text-align: center;"><a href="#subscription" style="text-style: default;">Click Here to View Subscription Information</a></p>
          </form>
        </div>
        <div class="tab" id="subscription">
          <h2>My Subscription</h2>
          <p style="text-align: center;"><strong>Member Since:</strong> <?php echo date("F j, Y, g:i a", $user->getCreation()); ?></p>
          <p style="text-align: center;">To cancel your subscription, type in your password below.<br />Or <a href="#account">click here</a> to go back to your account settings.</p>
          <form>
            <label for="subscription-password">Password</label>
            <input type="password" name="subscription-password" id="subscription-password" />
            <button type="button" onclick="User.Unsubscribe();">Cancel Subscription</button>
          </form>
        </div>
      </div>
    </div>
    <footer>
      <span class="copyright">&copy; Copyright <?php echo date("Y"); ?>. Word Dance University. All rights reserved.</span>
    </footer>
    <script src="/js/vendor/modernizr/modernizr-2.5.3.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="/js/vendor/jquery/jquery.cookie.js"></script>
    <script src="/js/vendor/jquery-ui/ui/jquery-ui.js"></script>
    <script src="/js/vendor/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="http://vjs.zencdn.net/4.0/video.js"></script>
    <script src="/js/vendor/BigVideo/lib/bigvideo.js"></script>
    <script src="/js/globals.js"></script>
    <script src="/js/ajax.js"></script>
    <script src="/js/nav.js"></script>
    <script src="/js/user.js"></script>
    <script>
    $(function() {
      var BV = new $.BigVideo();
      var vids = [
        <?php
        $video = Video::getFeatured();
        echo "'//player.vimeo.com/external/" . $video['vimeo_id'] . "." . $video['definition'] . ".mp4?s=" . $video['key'] . "',";
        ?>
      ];
      vids.sort(function() { return 0.5 - Math.random() });
      BV.init();
      BV.show(vids,{ambient:true});

      $('.playlist-btn').on('click', function(e) {
          isShowingVideo = true;
          $('.content').css('display', 'none');
          e.preventDefault();
          BV.show($(this).attr('href'));
      });
      $('.content')
        .on('mouseover', function() {
          if (isShowingVideo) autoHideNav(false);
        })
        .on('mouseout', function() {
          if (isShowingVideo) autoHideNav(true);
        });
        $('#play-button').on('click', function() {
          BV.getPlayer().play();
          $('#play-button').fadeOut('slow');
        });
    });
    </script>
    <script src ="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
    <script>var dg = new PAYPAL.apps.DGFlow({trigger: "paypal-submit"});</script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', '<?php echo GA_CODE; ?>', 'auto');
      ga('send', 'pageview');
    </script>
  </body>
</html>
