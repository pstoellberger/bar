<?php

namespace Fuel\Migrations;

class Add_custom_code {

	public function up()
	{
     \DBUtil::add_fields('users', array(
            'custom_code' => array('constraint' => 255, 'type' => 'varchar'),
    ));

     \DBUtil::add_fields('items', array(
            'custom_category' => array('constraint' => 255, 'type' => 'varchar'),
            'custom_code' => array('constraint' => 255, 'type' => 'varchar'),
    ));
	}

	public function down()
	{
    \DBUtil::drop_fields('users', array(
			'custom_code' 
    ));

    \DBUtil::drop_fields('items', array(
      'custom_code', 'custom_category' 
    ));


	}
}