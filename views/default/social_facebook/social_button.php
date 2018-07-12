<?php
/**
 * Elgg view for Google button
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */
?>
<script>
  function fb_login() {
      FB.login(function(response) {
          if (response.authResponse) {
              document.location='<?php echo elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/account/social_signin'); ?>&type=<?php echo SocialSignInPlugin\SocialModuleFactory::FACEBOOK_MODULE_ID; ?>&token=' + response.authResponse.accessToken;
            }
        });
  }
</script>
<a onclick="fb_login()" class="btn btn-social btn-facebook outlined" title="<?php echo elgg_echo('social_linkedin:button:title')?>">
  <i class="fa fa-facebook"></i>
</a>
