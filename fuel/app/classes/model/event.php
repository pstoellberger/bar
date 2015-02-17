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
        'saldo' => array(
            'type' => 'float',
            'label' => 'Amount',
            'default' =>''),

        'sponsoring' => array(
            'type' => 'float',
            'label' => 'Sponsoring',
            'default' =>''),

        'created_at' => array('type' => 'int', 'label' => 'Created At'),
        'updated_at' => array('type' => 'int', 'label' => 'Updated At'),
    );

	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array('before_insert'),
		'Orm\\Observer_UpdatedAt' => array('before_save'),
		'Orm\\Observer_Validation' => array('before_save')
	);

    public function update_saldo(){

        $query = DB::query('SELECT *, SUM(price) as saldo FROM event_consumptions WHERE event_id='.$this->id.' AND STATUS=1');
        $result=$query->execute()->as_array();
        $saldo=$result[0]['saldo'];
        
        $this->saldo=$saldo;
        $this->save();
    }
}
