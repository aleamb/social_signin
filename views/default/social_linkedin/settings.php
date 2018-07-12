<div class="linkedin-reg-settings">
    <div>
      <label><?php echo elgg_echo('social_linkedin:settings:label:clientId'); ?></label>
      <input name="params[ln_clientId]" type="text" value="<?php echo $vars['entity']->ln_clientId ?>" required>
   </div>

   <div>
      <label><?php echo elgg_echo('social_linkedin:settings:label:clientSecret'); ?></label>
      <input name="params[ln_clientSecret]" type="text" value="<?php echo $vars['entity']->ln_clientSecret ?>" required>
   </div>
</div>
