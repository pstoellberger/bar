<?php

namespace Fuel\Migrations;

class Add_event_saldo {

	public function up()
	{
    \DBUtil::add_fields('events', array(
						'saldo' => array('type' => 'float')
    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('events', array(
			'saldo' 
    ));
	}
}