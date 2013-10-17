<?php
$shortcode_id = static::shortcode_id($attributes);
$list_name = 'List';
$contact_list_id = $attributes['list_id'];
if($contact_list_id) {
    $contact_list_name = CallFireClickToSubscribe::contact_list_name($contact_list_id);
    if($contact_list_name) {
        $list_name = $contact_list_name;
    }
}

ob_start();
?>
<div class="click-to-subscribe">
    <strong>Subscribe to <?php echo $list_name; ?></strong>
    <form method="post">
        <input type="hidden" name="clicktosubscribe_submit_hidden" value="Y" />
        <input type="hidden" name="clicktosubscribe_shortcode_id" value="<?php echo $shortcode_id; ?>">
        <p>
            <label for="clicktosubscribe_phone_number">Phone Number</label>
            <input type="text" name="clicktosubscribe_phone_number" id="clicktosubscribe_phone_number" />
        </p>
        <input type="submit" class="button-primary" />
    </form>
</div>
<?php
return ob_get_clean();
