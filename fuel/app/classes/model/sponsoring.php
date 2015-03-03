<?php

class Model_Sponsoring extends Orm\Model {
	protected static $_belongs_to = array('event');
	protected static $_properties = array(
        'id' => array('type' => 'int'),
        'company' => array(
            'type' => 'varchar',
            'label' => 'Company',
            'validation' => array('required', 'min_length' => array(3), 'max_length' => array(200))
        ),
        'amount' => array(
            'type' => 'int',
            'label' => 'Sponsor Amount',
            'validation' => array('required')
        ),
        'created_at' => array('type' => 'int', 'label' => 'Created At'),
        'updated_at' => array('type' => 'int', 'label' => 'Updated At')
    );

	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array('before_insert'),
		'Orm\\Observer_UpdatedAt' => array('before_save'),
		'Orm\\Observer_Validation' => array('before_save')
	);
}

/* End of file item.php */