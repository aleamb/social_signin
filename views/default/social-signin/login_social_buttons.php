<?php $socialSigIn = new SocialSignInPlugin\SocialSignIn(); ?>

<div class="login-social">
<?php
foreach (SocialSignInPlugin\SocialModuleFactory::MODULES as $moduleName) {
    if ($socialSigIn->moduleIsEnabled($moduleName)) {
        echo elgg_view("$moduleName/social_button");
    }
}
?>
</div>
