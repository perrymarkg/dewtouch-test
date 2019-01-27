<?php
    echo $this->Form->create('Migration', array('type' => 'file'));
    echo $this->Form->input('file', array('label' => 'Import File', 'type' => 'file'));
    echo $this->Form->submit('Upload', array('class' => 'btn btn-primary'));
    echo $this->Form->end();
?>

<hr>

<?php if (!empty($members)): ?>
<h3>Member Transactions</h3>
<table class="table striped table-bordered">
    <thead>
        <tr>
            <th>Member Name</th>
            <th>Type</th>
            <th>No</th>
            <th>Company</th>
            <th>Member Pay Type</th>
            <th>Date</th>
            <th>Month</th>
            <th>Ref No</th>
            <th>Receipt No</th>
            <th>Payment Method</th>
            <th>Batch No</th>
            <th>Cheque No</th>
            <th>Renewal Year</th>
            <th>Subtotal</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $member): ?>
            <tr>
                <td><?php echo $member['Member']['name']; ?></td>
                <td><?php echo $member['Member']['type']; ?></td>
                <td><?php echo $member['Member']['no']; ?></td>
                <td><?php echo $member['Member']['company']; ?></td>
                <td><?php echo $member['Transaction']['member_paytype']; ?></td>
                <td>
                    <?php echo DateTime::createFromFormat(
                        'Y-m-d', 
                        $member['Transaction']['date'])
                        ->format('d/m/Y');?>
                </td>
                <td>
                    <?php echo !empty($member['Transaction']['month']) ? DateTime::createFromFormat(
                        '!m', 
                        $member['Transaction']['month'])
                        ->format('F') : false;?>
                </td>
                <td><?php echo $member['Transaction']['ref_no']?></td>
                <td><?php echo $member['Transaction']['receipt_no']?></td>
                <td><?php echo $member['Transaction']['payment_method']?></td>
                <td><?php echo $member['Transaction']['batch_no']?></td>
                <td><?php echo $member['Transaction']['cheque_no']?></td>
                <td><?php echo $member['Transaction']['renewal_year']?></td>
                <td><?php echo $member['Transaction']['subtotal']?></td>
                <td><?php echo $member['Transaction']['tax']?></td>
                <td><?php echo $member['Transaction']['total']?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<hr>
<?php endif; ?>

<div class="pagination pagination-centered">
    <ul>
        <?php 
            echo $this->Paginator
                ->numbers(
                    array(
                        'modulus' => 2,
                        'tag' => 'li',
                        'separator' => false,
                        'currentTag' => 'a',
                        'first' => 5,
                        'last' => 5,
                        'ellipsis' => '<li><a>...</a></li>'
                    )
                );
        ?>
    </ul>
</div>
<hr>


<style type="text/css">
    .pagination .current a,
    .pagination .current a:hover {
        background: #eeeeee;
        color:#555555;
        cursor:disabled;
    }
</style>