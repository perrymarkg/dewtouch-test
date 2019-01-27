<?php
class Transaction extends AppModel
{
    var $belongsTo = array('Member');
    var $hasMany = array(
        'TransactionItem' => array(
            'conditions' => array(
                'TransactionItem.valid' => 1
                )
            )
        );

}