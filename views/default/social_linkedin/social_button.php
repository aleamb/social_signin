<?php
/**
 * Elgg view for Linkedin button
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */
    $socialSigIn = new SocialSignInPlugin\SocialSignIn();
    $linkedinModule = SocialSignInPlugin\SocialModuleFactory::create(SocialSignInPlugin\SocialModuleFactory::LINKEDIN_MODULE_ID, $socialSigIn);

    $clientId = $linkedinModule->getAppId();

    $redirect_uri = urlencode(elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/account/social_signin') . '&type=' . SocialSignInPlugin\SocialModuleFactory::LINKEDIN_MODULE_ID . '&XDEBUG_SESSION_START');
    $url = 'https://www.linkedin.com/oauth/v2/authorization?redirect_uri=';
    $url .= $redirect_uri;
    $url .= '&state=' . time() .'&response_type=code&client_id=' . $clientId;
?>
<a href="<?php echo $url ?>" class="btn btn-social btn-linkedin outlined" title="<?php echo elgg_echo('social_linkedin:button:title')?>">
  <i class="fa fa-linkedin"></i>
</a>
