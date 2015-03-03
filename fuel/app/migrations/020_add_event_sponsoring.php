<?php

namespace Fuel\Migrations;

class Add_event_sponsoring {

	public function up()
	{
    \DBUtil::add_fields('events', array(
						'sponsoring' => array('type' => 'float')
    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('events', array(
			'sponsoring' 
    ));
	}
}