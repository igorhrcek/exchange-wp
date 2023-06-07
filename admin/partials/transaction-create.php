<div class="wrap">
    <?php 
        if(count($form_errors) > 0) {
            foreach($form_errors as $error) {
                echo $this->admin_message($error['message'], $error['type']);
            }
        }
    ?>

    <h1>Create a new transaction</h1>

    <p>
        To create a new transaction, please select both source and destination accounts, as well as amount that you wish to transfer.
    </p>

    <p>
        Here was a perfect chance for developer to demonstrate his superior data manipulation and validation skills. However, he just ran out of the icecream and decided not to bother with any of that. So, you will see just a bunch of messages that came from API as errors. Sorry. 
    </p>

    <form method="post" action="<?php echo esc_url( admin_url('admin-post.php')); ?>" id="ce_create_transaction_form">
        <input type="hidden" name="action" value="ce_create_transaction">
        <input type="hidden" name="redirect" value="<?php echo esc_url( admin_url('admin.php?page=currency-exchange-transactions')); ?>">
        <?php wp_nonce_field('ce_create_transaction', 'ce_create_transaction_form_nonce'); ?>
        
        <fieldset class="ce-fieldset">
            <label for="source_account_id"><strong>Select Source Account *</strong></label>
            <select name="source_account_id" id="source_account_id">
                <option value="-1" selected="selected">Select source account</option>
                <?php foreach($accounts[0]->data as $account): ?>
                    <option value="<?php echo $account->id; ?>"><?php echo $account->uuid . " (" . $account->balance . " " . $mapping[$account->currency_id] . ")"; ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <fieldset class="ce-fieldset">
            <label for="destination_account_id"><strong>Select Destination Account *</strong></label>
            <select name="destination_account_id" id="destination_account_id">
                <option value="-1" selected="selected">Select destination account</option>
                <?php foreach($accounts[0]->data as $account): ?>
                    <option value="<?php echo $account->id; ?>"><?php echo $account->uuid . " (" . $account->balance . " " . $mapping[$account->currency_id] . ")"; ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>

        <fieldset class="ce-fieldset">
        <label for="amount">Enter Amount</label>
        <input type="number" name="amount" step="0.01" required>
        </fieldset>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Create Transaction"></p>
    </form>
</div>