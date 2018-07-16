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

/**
 * SocialSignIn class.
 *
 * This class describe SocialSignIn plugin and provides utils.
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */
class SocialSignIn
{

    const SOCIALSN_PLUGIN_NAME = 'social_signin';

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Returns name of SocilaSigIn plugin for registering in Elgg.
     *
     * @return string Name of plugin.
     */
    public function getSocialSignInPluginName()
    {
        return self::SOCIALSN_PLUGIN_NAME;
    }

    /**
     * Returns if social module is enabled o no
     */
    public function moduleIsEnabled($moduleName)
    {
        $module = SocialModuleFactory::create($moduleName, $this);
        return $module->isEnabled();
    }
}

