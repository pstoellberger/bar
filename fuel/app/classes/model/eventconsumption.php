<?php

class Model_Eventconsumption extends Orm\Model {
	//protected static $_has_one = array('user','item','event');
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);
}

/* End of file eventconsumption.php */