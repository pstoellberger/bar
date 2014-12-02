<?php

namespace Fuel\Migrations;

class Add_event_price {

	public function up()
	{
    \DBUtil::add_fields('items', array(
						'event_price' => array('type' => 'float'),
						'packing_unit' => array('constraint' => 11, 'type' => 'int'),


    ));
	}

	public function down()
	{
    \DBUtil::drop_fields('items', array(
			'event_price',
			'packing_unit'
    ));
	}
}