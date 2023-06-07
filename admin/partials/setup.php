<?php if(!Currency_Exchange_User::has_remote_account()): ?>
<h1>Welcome</h1>

<h2><?php _e("It is time to configure your account!", $this->plugin_name); ?></h2>
<p>
    To start using Currency Exchange, you need to sign up for a free account. The process is quick and painless, you just need to click one button!
</p>

<form method="post" action="<?php echo esc_url( admin_url('admin-post.php')); ?>" id="ce_setup_form">
    <input type="hidden" name="action" value="ce_setup_save">
    <?php wp_nonce_field('ce_setup_save', 'ce_setup_form_nonce'); ?>
    <div class="row g-5">
        <div>
            <label for="user_agreement">
                <input name="user_agreement" type="checkbox" id="user_agreement" value="1" checked="checked">
                Agree to Terms and Conditions. Unchecking this will make no change whatsoever. We will still steal your data and sell it to a highest bidder.
            </label>
        </div>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="I have no choice then, please steal my data"></p>
    </div>
</form>
<?php else: ?>
<h1>You already signed up</h1>

<p>
    You already signed up for a free account. Now go and exchange some money!
</p>

<?php endif; ?>