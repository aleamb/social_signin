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
elgg_load_js('google-sdk');
$socialSigIn = new SocialSignInPlugin\SocialSignIn();
$googleModule = SocialSignInPlugin\SocialModuleFactory::create(SocialSignInPlugin\SocialModuleFactory::GOOGLE_MODULE_ID, $socialSigIn);

?>
<a id="google-signin" class="btn btn-social btn-google outlined" title="<?php echo elgg_echo('social_google:button:title')?>">
    <i class="fa fa-google"></i>
</a>
<script>
    var startGoogle = function() {
        gapi.load('auth2', function() {
            auth2 = gapi.auth2.init({
                client_id: '<?php echo $googleModule->getAppId(); ?>',
                cookiepolicy: 'single_host_origin',
            });
            var googleSignIns = document.getElementsByClassName('btn-google');
            for (var e = 0; e < googleSignIns.length; e++) {
                attachSignin(googleSignIns[e]);
            }

        });
    };
    function attachSignin(element) {
        auth2.attachClickHandler(element, {},
            function(googleUser) {
                document.location='<?php echo elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/account/social_signin'); ?>&code=' + googleUser.getAuthResponse().id_token + '&type=<?php echo SocialSignInPlugin\SocialModuleFactory::GOOGLE_MODULE_ID; ?>';
            },
            function(error) {
                elgg.register_error('<?php echo elgg_echo('social_google:actions:error')?>');
            });
    }
  </script>
