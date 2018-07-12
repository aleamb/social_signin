<?php
/**
 * This file is part of SocialSigInPlugin for Elgg.
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */
namespace SocialSignInPlugin;

class LinkedinModule extends SocialModule
{
    /**
     *
     */
    public function init()
    {
    }

    /**
     * Write elgg plugin configuration with default values.
     *
     * @return void
     */
    public function initValues()
    {
        if ($this->isFirstInit()) {
            $this->setEnabled(true);
        }
        if (empty($this->getAppId())) {
            $this->setAppId('Create application and get client id');
        }
        if (empty($this->getAppSecretId())) {
            $this->setAppSecretId('Create application and get secret id');
        }
    }


    /**
     * Register elgg views.
     *
     * @return void
     */
    public function registerElggViews()
    {
    }

    public function getAppClientIdSettingName()
    {
        return 'ln_clientId';
    }

    public function getAppClientSecretIdSettingName()
    {
        return 'ln_clientSecret';
    }

    public function retrieveUserSocialProfile($attributes)
    {
        $code_autorized = array(
            "grant_type" => "authorization_code",
            "code" => $attributes['code'],
            "redirect_uri" => elgg_get_site_url() . "action/account/social_signin?__elgg_ts=" . $attributes['__elgg_ts'] . "&__elgg_token=" . $attributes['__elgg_token']. '&type=social_linkedin&XDEBUG_SESSION_START',
            "client_id" => $this->getAppId(),
            "client_secret" => $this->getAppSecretId()
        );
        $post_fields = http_build_query($code_autorized);

        $httpheaders =  array();
        $httpheaders[] = 'Content-Type: application/x-www-form-urlencoded';
        $httpheaders[] = 'Host: www.linkedin.com';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.linkedin.com/oauth/v2/accessToken');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        if (!$result) {
            curl_close($ch);
            error_log(_export($result));
            throw new \Exception(curl_errno($ch), curl_strerror($ch));
        }
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_status != 200) {
            error_log('Error obtaining Linkedin access token: ' . curl_strerror($ch));
            curl_close($ch);
            throw new \Exception('Error obtaining Linkedin access token.');
        }
        $token = json_decode($result, true);
        curl_close($ch);

        $linkedinProfile = $this->retrieveLinkedinUserProfile($token['access_token']);

        $socialUser = new SocialUser();
        $socialUser->id = $linkedinProfile['id'] ;
        $socialUser->email = $linkedinProfile['emailAddress'] ;
        $socialUser->firstName = $linkedinProfile['firstName'] ;
        $socialUser->lastName = $linkedinProfile['lastName'] ;
        $socialUser->pictureUrl = $linkedinProfile['pictureUrl'];

        return $socialUser;
    }

   /**
     * Retrieve information of linkedin user profile
     *
     * @param string $token Linkedin token
     *
     * @return json
     */
    function retrieveLinkedinUserProfile($token)
    {

        $jsonProfile = null;

        $header = array();

        $header[] = 'Connection: Keep-Alive';
        $header[] = 'Host: api.linkedin.com';
        $header[] = 'Authorization: Bearer ' . $token;

        $ch = curl_init();

        if (!$ch) {
            throw new \Exception('Failed to initialize curl');
        }

        curl_setopt($ch, CURLOPT_URL, 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address,headline,location,picture-url)?format=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $resultProfile = curl_exec($ch);

        if ($resultProfile === false) {
            curl_close($ch);
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status !== 200) {
            throw new \Exception(curl_error($ch));
        }
        $jsonProfile = json_decode($resultProfile, true);
        return $jsonProfile;
    }
}
