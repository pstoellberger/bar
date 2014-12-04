<?php

namespace Fuel\Migrations;

class Add_team_flag {

	public function up()
	{
    \DBUtil::add_fields('users', array(
						'is_team' => array('type' => 'boolean')


    ));
	}

	public function down()
	{
    \DBUtil::drop_fields('users', array(
			'is_team'
    ));
	}
}