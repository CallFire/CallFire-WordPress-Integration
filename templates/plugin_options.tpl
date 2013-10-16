<div id="wrap">
	<h2>CallFire Options</h2>
<?php
if($settingsUpdated) {
?>
	<p>Settings have been updated.</p>
<?php
}
?>
	<form name="callfire_plugin_options" method="POST">
		<input type="hidden" name="callfire_options_submit_hidden" value="Y" />
<?php
foreach(static::$plugin_options as $option_name => $friendly_name) {
?>
		<p>
			<label for="option_<?php echo $option_name; ?>"><?php echo $friendly_name; ?></label>
			<input id="option_<?php echo $option_name; ?>" name="option_<?php echo $option_name; ?>" type="text" value="<?php echo get_option($option_name); ?>" />
		</p>
<?php
}
?>
		<input type="submit" class="button-primary" />
	</form>
</div>
