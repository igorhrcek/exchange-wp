<div class="wrap">
    <?php 
        if(count($form_errors) > 0) {
            foreach($form_errors as $error) {
                echo $this->admin_message($error['message'], $error['type']);
            }
        }
    ?>

    <h1>Create a new account</h1>

    <p>
        To create an account you need to select a currency for that account.
    </p>

    <p>
        Please note that it is not possible to have two accounts with the same currency and developer of this plugin was too lazy to prevent you from selecting currency that was previously used.
    </p>

    <form method="post" action="<?php echo esc_url( admin_url('admin-post.php')); ?>" id="ce_create_account_form">
        <input type="hidden" name="action" value="ce_create_account">
        <input type="hidden" name="redirect" value="<?php echo esc_url( admin_url('admin.php?page=currency-exchange')); ?>">
        <?php wp_nonce_field('ce_create_account', 'ce_create_account_form_nonce'); ?>
        
        <label for="currency_id"><strong>Select currency *</strong></label><br />
        <select name="currency_id" id="currency_id">
            <option value="-1" selected="selected">Select desired currency</option>
            <?php foreach($currencies->data as $currency): ?>
                <option value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
            <?php endforeach; ?>
        </select>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Create Account"></p>
    </form>
</div>