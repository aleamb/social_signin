<?php
/**
 * SocialSignIn Elgg plugin.
 *
 * @category  ElggPlugin
 * @package   SocialSignIn
 * @author    Alejandro Ambroa <jandroz@gmail.com>
 * @license   MIT
 * @version   $Id$
 * @link      https://github.com/aleamb/social_signin
 */

require __DIR__ . '/vendor/autoload.php';

elgg_register_event_handler('init', 'system', 'socialsn_init');

/**
 * Init plugin handler.
 *
 * Initialize all social systems (Facebook, Google, Linkedin)
 *
 * @return void
 */
function socialsn_init()
{
    // "submodules" to register and use

    $socialSignin = new SocialSignInPlugin\SocialSignIn();

    socialsn_init_views();
    socialsn_register_actions();

    foreach (SocialSignInPlugin\SocialModuleFactory::MODULES as $moduleName) {
        init_socialsn_module($socialSignin, $moduleName);
    }
}

/**
 * Register views
 *
 * @return void
 */
function socialsn_init_views()
{
    elgg_extend_view('admin.css', 'social-signin.css');
    elgg_extend_view('elgg.css', 'social-signin.css');
    elgg_extend_view('forms/register', 'social-signin/login_social_buttons', 800);
    elgg_extend_view('forms/login', 'social-signin/login_social_buttons', 800);
}

/**
 * Build social module.
 *
 * @param SocialSignIn $socialSignIn Social main module
 * @param string            $moduleName        Name of social module.
 *
 * @return void
 */
function init_socialsn_module($socialSignIn, $moduleName)
{
    $module = SocialSignInPlugin\SocialModuleFactory::create($moduleName, $socialSignIn);
    $module->init();
    $module->initValues();
    if ($module->isEnabled()) {
        $module->registerElggViews();
    }
}

function socialsn_register_actions()
{
    $actionsDir = __DIR__ . '/actions';
    elgg_register_action('account/social_signin', "$actionsDir/social_signin.php", 'public');
}
