<?php
class Member extends AppModel
{
		
    var $hasMany = array(
        'Transaction' => array(
            'conditions' => array(
                'Transaction.valid' => 1
                )
            )
        );

}