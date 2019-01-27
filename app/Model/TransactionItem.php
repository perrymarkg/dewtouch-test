<?php
class TransactionItem extends AppModel
{
    var $belongsTo = array('Member');
    var $hasMany = array(
        'Transaction' => array(
            'conditions' => array(
                'Transaction.valid' => 1
                )
            )
        );

}