<div class="wrap">
    <h1>Currencies</h1>
    <hr class="wp-header-end">

    <?php foreach($currencies->data as $currency): ?>
    <h2><?php echo $currency->code; ?></h2>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="manage-column column-primary">From Currency</th>
                <th class="manage-column">To Currency</th>
                <th class="manage-column">Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($currency->exchange_rate as $rate): ?>
            <tr class="is-expanded">
                <td><?php echo $mapping[$rate->from_currency_id]; ?></td>
                <td><?php echo $mapping[$rate->to_currency_id]; ?></td>
                <td><?php echo $rate->rate; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endforeach; ?>
</div>