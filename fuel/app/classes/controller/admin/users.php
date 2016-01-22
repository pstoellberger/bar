<?php
class Controller_Admin_Users extends Controller_Admin {



	public function action_index()
	{
		//$data['users'] = Model_User::find('all',array('order_by' => 'username'));
		$data['team'] = Model_User::find('all',array(
			'where' => array( array('status','<>',1), array('is_team', 1)),
			'order_by' => array('last_login' => 'desc')
		));

		$data['users'] = Model_User::find('all',array(
			'where' => array( array('status','<>',1), array('is_team','<>', 1)),
			'order_by' => array('last_login' => 'desc')
		));

		$data['inactiveusers'] = Model_User::find('all',array(
			'where' => array( array('status',1)),
			'order_by' => array('last_login' => 'desc')
		));

		$this->template->title = "Users";
		$this->template->content = View::factory('users/index', $data);

	}

	public function action_export(){
		header("Content-type: text/csv");
		header('Content-disposition: attachment; filename=maillist.csv');
		$users = Model_User::find('all',array('order_by' => array('last_login' => 'desc')));

		$body = "";
		foreach ($users as $user):

		echo $user->firstname.",".$user->lastname.",".$user->email.",".date("d.m.Y",$user->last_login)."\n";

		endforeach;

		exit();

	}

	public function action_exportconsumptions(){
		header("Content-type: text/csv");
		header('Content-disposition: attachment; filename=consumptions.csv');
		$users = Model_User::find('all',array('order_by' => array('last_login' => 'desc')));

		$body = "";
		foreach ($users as $user):

		echo $user->firstname.",".$user->lastname.",".$user->saldo."\n";

		endforeach;

		exit();

	}

	public function action_exportunpaid(){
		header("Content-type: text/html");
		header('Content-disposition: attachment; filename=unpaid.csv');

		$query=DB::query('select u.is_team, firstname,lastname, saldo, i.title, count(i.title), round(sum(c.price),2) as total  from users u, consumptions c, items i  where u.id = c.user_id and c.item_id = i.id and saldo > 0 and c.status = 1 group by 1,2,3,4,5');
		$rows=$query->execute()->as_array();
		
		echo "is_team;firstname;lastname;unpaid_saldo;item;item_count;item_total\n";
		foreach ($rows as $row):
			echo str_replace(".", ",", utf8_decode(implode(";", $row)))."\n";
		endforeach;

		exit();

	}

	public function action_markinactive(){

		$query=DB::query('update users set status = 1 where from_unixtime(last_login) < DATE_SUB(NOW(), INTERVAL 3 month) order by last_login;');
		$rows=$query->execute();

		Response::redirect('admin/users');

	}


	public function action_view($id = null)
	{
		$user= Model_User::find($id);
		$data['user'] =$user;
		$data['consumptions']=$this->get_consumptions($id);

		$this->template->title = "User: ".$user->username;
		$this->template->content = View::factory('users/view', $data);

	}

	public function action_updateall(){
		$users = Model_User::find('all');
		foreach($users as $user){
			$user->update_saldo();
		}
		Response::redirect('admin/users');

	}



	public function action_create($id = null)
	{
		if (Input::method() == 'POST')
		{


			$user = Model_User::factory(array(
				'username' => Input::post('username'),
				'firstname' => Input::post('firstname'),
				'lastname' => Input::post('lastname'),
				'password' => \Auth::instance()->hash_password(Input::post('password') || md5(time())),
				'email' => Input::post('email'),
				'pin' => Input::post('pin'),
				'profile_fields' => Input::post('profile_fields'),
				'group' => Input::post('group') || '',
				'last_login' => Input::post('last_login') || '',
				'login_hash' => Input::post('login_hash') || '',
				'is_team'	=> Input::post('is_team') || false,
			));

			try
            {
                $user->save();
                Session::set_flash('notice', $this->flash_success('Create user #' . $user->id));
                Response::redirect('admin/users');
            }
            catch (Orm\ValidationFailed $e) {

                Session::set_flash('notice', $this->flash_error('Could not create user! ' . $e->getMessage()));
            }
		}

		$this->template->title = "Users";
		$this->template->content = View::factory('users/create');

	}
	public function action_removeconsumption($user_id,$consumption_id){
		$user =Model_User::find($user_id);
			$query = DB::update('consumptions');
			$query->where('status',1);
			$query->where('user_id',$user->id);
			$query->where('id',$consumption_id);
			$query->value('status',0);
			$query->execute();
			$user->update_saldo();
			 Session::set_flash('notice', $this->flash_success('Removed consumption #'.$consumption_id));
			Response::redirect('admin/users/view/'.$user->id);

	}
	public function action_payconsumption($id=null,$consumption_id=null){
		$user = Model_User::find($id);


			$query = DB::update('consumptions');
			$query->where('id',$consumption_id);
			$query->where('status',1);
			$query->where('user_id',$user->id);
			$query->value('status',time());
			$query->execute();
			$user->update_saldo();
			Session::set_flash('notice', $this->flash_success('Paid consumption #'.$consumption_id));

		Response::redirect('admin/users/view/'.$user->id);
	}
	public function action_payment($id = null,$sum =null){
		$user = Model_User::find($id);
		if(Input::method() == 'POST'){
			$query=DB::select('id')->from('categories')->where('ignore',1);
			$categories=$query->execute()->as_array();
			$query=DB::select('id')->from('items')->where('category_id','in',$categories);
			$items=$query->execute()->as_array();

			//exit();
			$query = DB::update('consumptions');
			foreach($items as $item){
				$query->where('item_id','!=',$item['id']);
			}
			$query->where('status',1);
			$query->where('user_id',$user->id);
			$query->value('status',time());
			$query->execute();
			$user->update_saldo();
			Response::redirect('admin/users/view/'.$user->id);

		}else{
		$consumptions=$this->get_consumptions($id,true);
		$sum=0;
		$total=sizeof($consumptions);
		foreach($consumptions as $consumption){
			$sum+=$consumption->price;
		}
		$data['sum']=$sum;
		$data['total']=$total;
		$data['user']=$user->username;
		$data['return']='admin/users/view/'.$user->id;
		$this->template->title="Payment";
		$this->template->content = View::factory('users/payment',$data);
		}
	}

	public function action_edit($id = null)
	{
		$user = Model_User::find($id);

		if (Input::method() == 'POST'){
			$user->username = Input::post('username');
			$user->firstname = Input::post('firstname');
			$user->lastname = Input::post('lastname');
			if(Input::post('password')!=""){
				$user->password = \Auth::instance()->hash_password(Input::post('password'));
			}
			$user->email = Input::post('email');
			$user->pin =  Input::post('pin');
			$user->profile_fields = Input::post('profile_fields');
			$user->group = Input::post('group');
			//$user->last_login = Input::post('last_login');
			$user->login_hash = Input::post('login_hash');

			$user->status = Input::post("status");
			$user->is_team = Input::post("is_team");

			try{
                $user->save();
                Session::set_flash('notice', $this->flash_success('Updated user #' . $id));
                Response::redirect('admin/users');
            }
            catch (Orm\ValidationFailed $e) {
                Session::set_flash('notice', $this->flash_error('Could not update user! Error: ' . $e->getMessage()));
            }
		}else{
			$this->template->set_global('user', $user, false);
		}

		$this->template->title = "Users";
		$this->template->content = View::factory('users/edit');

	}

	public function action_delete($id = null)
	{
		if ($user = Model_User::find($id))
		{
			$user->delete();

			Session::set_flash('notice', $this->flash_success('Deleted user #' . $id));
		}

		else
		{
			Session::set_flash('notice', $this->flash_error('Could not delete user! Error: ' . $e->getMessage()));
		}

		Response::redirect('admin/users');

	}
	/* simpe method to fetch unpaid consumptions for a user */
	private function get_consumptions($id,$ignored=false){
		$query=DB::select()->from('consumptions')->as_object('Model_Consumption')->where('user_id',$id)->where('status',1);

		if($ignored){
		 	$cquery=DB::select('id')->from('categories')->where('ignore',1);
			$categories=$cquery->execute()->as_array();
			$iquery=DB::select('id')->from('items')->where('category_id','in',$categories);
			$items=$iquery->execute()->as_array();

			foreach($items as $item){
				$query->where('item_id','!=',$item['id']);
			}

		}
		$consumptions=$query->execute()->as_array();
		return $consumptions;
	}


}

/* End of file users.php */
