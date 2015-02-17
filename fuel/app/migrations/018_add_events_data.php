<?php

namespace Fuel\Migrations;

class Add_events_data {

	public function up()
	{
    \DBUtil::create_table('event_consumptions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'event_id' => array('constraint' => 11, 'type' => 'int'),
			'item_id' => array('constraint' => 11, 'type' => 'int'),
			'price' => array('type' => 'float'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
			'status' => array('constraint' => 11, 'type' => 'int'),
			'title' => array('constraint' => 255, 'type' => 'varchar'),
			'order_id' => array('constraint' => 11, 'type' => 'int'),
			'cost' => array('type' => 'float'),
			'profit' => array('type' => 'float'),
			'category' => array('constraint' => 255, 'type' => 'varchar'),
		), array('id'));

		\DBUtil::create_table('events', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'title' => array('constraint' => 255, 'type' => 'varchar'),
			'event_date' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
			'status' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));

		\DBUtil::create_table('event_sponsorings', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'event_id' => array('constraint' => 11, 'type' => 'int'),
			'sponsor' => array('constraint' => 255, 'type' => 'varchar'),
			'amount' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
			
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('events');
		\DBUtil::drop_table('event_consumptions');
		\DBUtil::drop_table('event_sponsorings');



	}
}