<div class="wrap">
    <?php 
        if(count($form_errors) > 0) {
            foreach($form_errors as $error) {
                echo $this->admin_message($error['message'], $error['type']);
            }
        }
    ?>

    <h1 class="wp-heading-inline">Accounts</h1>
    <a href="<?php echo esc_url( admin_url('admin.php?page=currency-exchange-create-account')); ?>" class="page-title-action">Add New</a>
    <hr class="wp-header-end">

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="manage-column column-primary">ID</th>
                <th class="manage-column">UUID</th>
                <th class="manage-column">Currency</th>
                <th class="manage-column">Balance</th>
                <th class="manage-column">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(count($accounts[0]->data) > 0):
                foreach($accounts[0]->data as $account): 
            ?>
            <tr class="is-expanded">
                <td><?php echo $account->id; ?></td>
                <td><?php echo $account->uuid; ?></td>
                <td><?php echo $mapping[$account->currency_id]; ?></td>
                <td><?php echo $account->balance; ?></td>
                <td><?php echo $account->created_at; ?></td>
            </tr>
            <?php 
                endforeach; 
                else:
            ?>
                 <tr class="is-expanded">
                    <td colspan="5">There are no accounts associated with your user. Go and create one.</td>
                 </tr>
                 <?php endif; ?>
        </tbody>
    </table>

</div>