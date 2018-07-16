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

class GoogleModule extends SocialModule
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
            $this->setAppId('Create application and browse to OAuth credentials for getting client id');
        }
        if (empty($this->getAppSecretId())) {
            $this->setAppSecretId('Create application and browse to OAuth credentials for getting secret id');
        }
    }

    /**
     * Register elgg views.
     *
     * @return void
     */
    public function registerElggViews()
    {
        elgg_register_js('google-sdk', 'https://apis.google.com/js/platform.js?onload=startGoogle');
    }

    public function getAppClientIdSettingName()
    {
        return 'gl_clientId';
    }

    public function getAppClientSecretIdSettingName()
    {
        return 'gl_clientId';
    }
    
    public function retrieveUserSocialProfile($attributes)
    {
        $client = new \Google_Client(['client_id' => $this->getAppId()]);
        $payload = $client->verifyIdToken($attributes['code']);
        if (!$payload) {
            throw new \Exception('Error retrieving Google profile');
        }
        $socialUser = new SocialUser();
        $socialUser->id = $payload['sub'];
        $socialUser->email = $payload['email'];
        $socialUser->firstName = $payload['given_name'];
        $socialUser->lastName = $payload['family_name'];
        $socialUser->pictureUrl = $payload['picture'];
        return $socialUser;
    }
}
