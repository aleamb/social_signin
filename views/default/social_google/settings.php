 <?php elgg_echo('social_google:settings:title'); ?>

 <div class="google-reg-settings">
    <div>
      <label><?php echo elgg_echo('social_google:settings:label:clientId'); ?></label>
      <input name="params[gl_clientId]" type="text" value="<?php echo $vars['entity']->gl_clientId ?>" required>
   </div>

   <div>
      <label><?php echo elgg_echo('social_google:settings:label:clientSecret'); ?></label>
      <input name="params[gl_clientSecret]" type="text" value="<?php echo $vars['entity']->gl_clientSecret ?>" required>
   </div>

</div>
