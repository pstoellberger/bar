<?php

class Model_Event extends Orm\Model {
	protected static $_properties = array(
        'id' => array('type' => 'int'),
        'title' => array(
            'type' => 'varchar',
            'label' => 'Label',
            'validation' => array('required', 'min_length' => array(3), 'max_length' => array(200))
        ),
        'event_date' => array(
            'type' => 'int',
            'label' => 'Date'
        ),
        'status' => array(
            'type' =>'varchar',
            'label' => 'Status',
            'default' =>''
        ),

        'created_at' => array('type' => 'int', 'label' => 'Created At'),
        'updated_at' => array('type' => 'int', 'label' => 'Updated At'),
    );

	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array('before_insert'),
		'Orm\\Observer_UpdatedAt' => array('before_save'),
		'Orm\\Observer_Validation' => array('before_save')
	);
}
