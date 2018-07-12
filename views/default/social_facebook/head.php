<?php
$socialSignIn = new SocialSignInPlugin\SocialSignIn();
$facebookModule = SocialSignInPlugin\SocialModuleFactory::create(SocialSignInPlugin\SocialModuleFactory::FACEBOOK_MODULE_ID, $socialSignIn);
?>
<script>
 (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/<?php echo get_current_language(); ?>/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  window.fbAsyncInit = function() {
    FB.init({
      appId: '<?php echo $facebookModule->getAppId();  ?>',
      cookie: false,
      xfbml: true,
      version: 'v2.8'
  });
 }
</script>
