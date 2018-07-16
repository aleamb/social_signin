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

abstract class SocialModule
{
    private $_socialPluginModule = null;
    private $_id = null;

    function __construct($id, $socialPluginModule)
    {
        $this->_socialPluginModule = $socialPluginModule;
        $this->_id = $id;
    }
    /**
     * Initializes social module
     *
     * @return void
     *
     */
    abstract public function init();
    /**
     * Inti values for social module.
     *
     * @return void
     */
    abstract public function initValues();
    /**
     *
     * Returns name of attribute where pugin saves OAuth app id for each social module.
     *
     * @return string name of attribute.
     */
    abstract public function getAppClientIdSettingName();
    /**
     *
     * Returns name of attribute where pugin saves OAuth secret id for each social module.
     *
     * @return string name of attribute.
     */
    abstract public function getAppClientSecretIdSettingName();
    /**
     * Register elgg views for each module.
     *
     * @return void
     */
    abstract public function registerElggViews();
    /**
     * Retrieves social profile from social network.
     *
     * @return SocialUser objet with some data from social user profile
     */
    abstract public function retrieveUserSocialProfile($attributtes);

    /**
     * Get module id;
     *
     * @return string Module identifier
     */
    public function getId()
    {
        return $this->_id;
    }
    /**
     * Checks if module is enabled.
     *
     * @return boolean true if enabled
     *
     */

    public function isEnabled()
    {
        return elgg_get_plugin_setting(
            $this->getEnabledAttribName(),
            $this->getSocialSignInPluginName()) === '1';
    }

    /**
     * Check if module is first init
     *
     * @return boolean true if first init.
     */
    public function isFirstInit()
    {
        $value = elgg_get_plugin_setting(
            $this->getEnabledAttribName(),
            $this->getSocialSignInPluginName());
        return $value === null || $value === false;
    }

    /**
     * Get elgg user that matches with social profile.
     *
     * @param $profile SocialUser profile.
     *
     * @return ElggUser or null if not matching user.
     */
    public function getElggUser($socialUser)
    {
        $userId = $socialUser->id;
        $email = $socialUser->email;
        $entities = elgg_get_entities_from_metadata(array(type => 'user', metadata_name_value_pairs => array(name => 'socialId', value => $userId)));
        if (count($entities) > 1) {
            throw new \Exception('Multiple Facebook accounts');
        }
        $entities = get_user_by_email($email);
        return $entities[0];
    }


    public function generateUsername($socialUser)
    {
        $name = trim($socialUser->firstName);
        $lastName =  trim($socialUser->lastName);
        $username = strtolower($name  . '.' . $lastName);

        $users = elgg_get_entities_from_attributes(array(type => 'user', attribute_name_value_pairs => array(name => 'username', value => $username.'%', operand => 'like')));
        if (count($users) === 0) {
            return $username;
        }
        $greater = $this->extractMaxUsernameSuffix($users);
        if ($greater != -1) {
            $suffix = $greater + 1;
            $username .= (string)$suffix;
        }
        return $username;
    }

    /**
     * Register user in elgg system from google profile.
     *
     * @param Object $profile array object with google data
     *
     * @return Object user. null if register error.
     */
    public function registerUser($username, $socialUser)
    {

        $guid = register_user($username, generate_random_cleartext_password(), $socialUser->firstName . ' ' . $socialUser->lastName,  $socialUser->email);
        if (!$guid) {
            throw new \Exception('Error registering user from Google');
        }
        $user = get_entity($guid);
        // set google id for detect user in subsequent logins.
        $user->socialId = $socialUser->$id;
        $user->save();

        // set profile image if any
        $this->buildProfileImage($user, $socialUser);

        return $user;
    }

     /**
     * Obtains client ID
     *
     * @return string OAuth client id.
     */
    public function getAppId()
    {
        return elgg_get_plugin_setting(
            $this->getAppClientIdSettingName(),
             $this->getSocialSignInPluginName());
    }
    /**
     * Sets Client id
     *
     * @param string $value value
     *
     * @return void
     */
    public function setAppId($value)
    {
        elgg_set_plugin_setting(
            $this->getAppClientIdSettingName(),
            $value,
            $this->getSocialSignInPluginName());
    }

    /**
     * Obtains secret id
     *
     * @return string OAuth client secret id
     */
    public function getAppSecretId()
    {
        return elgg_get_plugin_setting(
            $this->getAppClientSecretIdSettingName(),
            $this->getSocialSignInPluginName());
    }

    /**
     * Sets secret id
     *
     * @param string $value value
     *
     * @return void
     */
    public function setAppSecretId($value)
    {
        elgg_set_plugin_setting(
            $this->getAppClientSecretIdSettingName(),
            $value,
            $this->getSocialSignInPluginName());
    }

    /**
     * Enable/disable social module.
     *
     * @param bool $value true/flase for enabled/disable
     *
     * @return void
     */

    protected function setEnabled($value)
    {
        elgg_set_plugin_setting(
            $this->getEnabledAttribName(),
            $value ? '1' : '0',
            $this->getSocialSignInPluginName());
    }

    /**
     * Get name for enabled attribute
     *
     * @return string Name.
     */
    protected function getEnabledAttribName()
    {
        return $this->getId() . '_enabled';
    }

    /**
     * Extract number in user.lastnameNUMBER combination form users list
     *
     * @param Array $users users list
     *
     * @return int last index
     */
    protected function extractMaxUsernameSuffix($users)
    {
        $greater = -1;
        $exist = false;
        foreach ($users as $user) {
            $exist = true;
            $suffix = $this->extractUsernameSuffix($user->username);
            if ($suffix !== null && $suffix > $greater) {
                $greater = $suffix;
            }
        }
        if ($greater == -1 && $exist) {
            $greater = 1;
        }
        return $greater;
    }

    /**
     * Extract suffix number for an username.
     *
     * @param string $username Username.
     *
     * @return int suffix
     */
    function extractUsernameSuffix($username)
    {
        $suffix = array();
        preg_match('/[^0-9]+(\d+)$/', $username, $suffix);
        return $suffix[1] ? intval($suffix[1]) : null;
    }
    /**
     * Write profile image from google profile
     *
     * @param ElggUser $user         Elgg user object.
     * @param SocialUser $socialUser User profile from social network
     *
     * @return void
     */
    protected function buildProfileImage($user, $socialUser)
    {
        $pictureUrl = $socialUser->pictureUrl;
        if ($pictureUrl) {

            $content = file_get_contents($pictureUrl);

            $icon_sizes = elgg_get_config('icon_sizes');

            foreach ($icon_sizes as $name => $size_info) {
                $file = new \ElggFile();
                $file->owner_guid = $user->guid;
                $file->setFilename("profile/{$user->guid}{$name}.jpg");
                $file->open('write');
                $file->write($content);
                $file->close();
            }
            $user->saveIconFromElggFile($file);
            $user->save();
        }
    }

    protected function getSocialSignInPluginName()
    {
        return $this->_socialPluginModule->getSocialSignInPluginName();
    }
}



