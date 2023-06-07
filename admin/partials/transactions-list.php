<div class="wrap">
    <?php 
        if(count($form_errors) > 0) {
            foreach($form_errors as $error) {
                echo $this->admin_message($error['message'], $error['type']);
            }
        }
    ?>

    <h1 class="wp-heading-inline">Transactions</h1>
    <a href="<?php echo esc_url( admin_url('admin.php?page=currency-exchange-transfer-money')); ?>" class="page-title-action">Transfer Money</a>
    <hr class="wp-header-end">

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="manage-column column-primary">ID</th>
                <th class="manage-column">Account</th>
                <th class="manage-column">Reference</th>
                <th class="manage-column">Amount</th>
                <th class="manage-column">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(count($transactions->data) > 0):
                foreach($transactions->data as $transaction): 
            ?>
            <tr class="is-expanded">
                <td><?php echo $transaction->id; ?></td>
                <td><?php echo $mapping[$transaction->account_id]; ?></td>
                <td><?php echo $transaction->reference; ?></td>
                <td><?php echo $transaction->amount; ?></td>
                <td><?php echo $transaction->created_at; ?></td>
            </tr>
            <?php 
                endforeach; 
                else:
            ?>
             <tr class="is-expanded">
                <td colspan="5">There are no recent transactions. Go and create one.</td>
             </tr>
             <?php endif; ?>
        </tbody>
    </table>

</div>