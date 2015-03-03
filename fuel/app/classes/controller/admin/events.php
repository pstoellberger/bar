<?php
class Controller_Admin_Events extends Controller_Admin {



	public function action_index()
	{
		
		$data['events'] = Model_Event::find('all',array(
			'where' => array( array('status','<>',1), array('saldo','>=', 0)),
			'order_by' => array('updated_at' => 'desc')
		));

		$data['inactiveevents'] =Model_Event::find('all',array(
			'where' => array( array('status','=',1), array('saldo','>=', 0)),
			'order_by' => array('updated_at' => 'desc')
		));

		$this->template->title = "Events";
		$this->template->content = View::factory('events/index', $data);

	}

	public function action_export(){
		header("Content-type: text/csv");
		header('Content-disposition: attachment; filename=maillist.csv');
		$events = Model_Event::find('all',array('order_by' => array('last_login' => 'desc')));

		$body = "";
		foreach ($events as $event):

		echo $event->title.",".$event->event_date.",".$event->status.",".$event->saldo.",".$event->sponsoring.",".date("d.m.Y",$event->created_at)."\n";

		endforeach;

		exit();

	}

	public function action_exportconsumptions(){
		header("Content-type: text/csv");
		header('Content-disposition: attachment; filename=consumptions.csv');
		$events = Model_Event::find('all',array('order_by' => array('last_login' => 'desc')));

		$body = "";
		foreach ($events as $event):

		echo $event->title.",".$event->event_date.",".$event->status.",".$event->saldo.",".$event->sponsoring.",".date("d.m.Y",$event->created_at)."\n";

		endforeach;

		exit();

	}


	public function action_view($id = null)
	{
		$event= Model_Event::find($id);
		$data['event'] =$event;
		$data['consumptions']=$this->get_consumptions($id);

		$this->template->title = "Event: ".$event->title;
		$this->template->content = View::factory('events/view', $data);

	}

	public function action_updateall(){
		$events = Model_Event::find('all');
		foreach($events as $event){
			$event->update_saldo();
		}
		Response::redirect('admin/events');

	}



	
	public function action_removeconsumption($event_id,$consumption_id){
		$event =Model_Event::find($event_id);
			$query = DB::update('eventconsumptions');
			$query->where('status',1);
			$query->where('event_id',$event->id);
			$query->where('id',$consumption_id);
			$query->value('status',0);
			$query->execute();
			$event->update_saldo();
			 Session::set_flash('notice', $this->flash_success('Removed consumption #'.$consumption_id));
			Response::redirect('admin/events/view/'.$event->id);

	}
	public function action_payconsumption($id=null,$consumption_id=null){
		$event = Model_Event::find($id);


			$query = DB::update('eventconsumptions');
			$query->where('id',$consumption_id);
			$query->where('status',1);
			$query->where('event_id',$event->id);
			$query->value('status',time());
			$query->execute();
			$event->update_saldo();
			Session::set_flash('notice', $this->flash_success('Paid consumption #'.$consumption_id));

		Response::redirect('admin/events/view/'.$event->id);
	}
	public function action_payment($id = null,$sum =null){
		$event = Model_Event::find($id);
		if(Input::method() == 'POST'){
			$query=DB::select('id')->from('categories')->where('ignore',1);
			$categories=$query->execute()->as_array();
			$query=DB::select('id')->from('items')->where('category_id','in',$categories);
			$items=$query->execute()->as_array();

			//exit();
			$query = DB::update('eventconsumptions');
			foreach($items as $item){
				$query->where('item_id','!=',$item['id']);
			}
			$query->where('status',1);
			$query->where('event_id',$event->id);
			$query->value('status',time());
			$query->execute();
			$event->update_saldo();
			Response::redirect('admin/events/view/'.$event->id);

		}else{
		$consumptions=$this->get_consumptions($id,true);
		$sum=0;
		$total=sizeof($consumptions);
		foreach($consumptions as $consumption){
			$sum+=$consumption->price;
		}
		$data['sum']=$sum;
		$data['total']=$total;
		$data['event']=$event->title;
		$data['return']='admin/events/view/'.$event->id;
		$this->template->title="Payment";
		$this->template->content = View::factory('events/payment',$data);
		}
	}

	public function action_create($id = null)
	{
		if (Input::method() == 'POST')
		{


			$event = Model_Event::factory(array(
				'title' => Input::post('title'),
				'event_date' => Input::post('event_date'),
				'sponsoring' => Input::post('sponsoring'),
				'status'	=> Input::post('status'),
			));

			try
            {
                $event->save();
                Session::set_flash('notice', $this->flash_success('Create event #' . $event->id));
                Response::redirect('admin/events');
            }
            catch (Orm\ValidationFailed $e) {

                Session::set_flash('notice', $this->flash_error('Could not create event! ' . $e->getMessage()));
            }
		}

		$this->template->title = "Events";
		$this->template->content = View::factory('events/create');

	}
	public function action_edit($id = null)
	{
		$event = Model_Event::find($id);

		if (Input::method() == 'POST'){
			$event->title = Input::post('title');
			$event->event_date = Input::post('event_date');
			$event->sponsoring = Input::post('sponsoring');
			$event->status = Input::post('status');

			try{
                $event->save();
                Session::set_flash('notice', $this->flash_success('Updated event #' . $id));
                Response::redirect('admin/events');
            }
            catch (Orm\ValidationFailed $e) {
                Session::set_flash('notice', $this->flash_error('Could not update event! Error: ' . $e->getMessage()));
            }
		}else{
			$this->template->set_global('event', $event, false);
		}

		$this->template->title = "Events";
		$this->template->content = View::factory('events/edit');

	}

	public function action_delete($id = null)
	{
		if ($event = Model_Event::find($id))
		{
			$event->delete();

			Session::set_flash('notice', $this->flash_success('Deleted event #' . $id));
		}

		else
		{
			Session::set_flash('notice', $this->flash_error('Could not delete event! Error: ' . $e->getMessage()));
		}

		Response::redirect('admin/events');

	}
	/* simpe method to fetch unpaid consumptions for a event */
	private function get_consumptions($id,$ignored=false){
	// WARUM GEHT AS MODEL NICHT???
	 $query=DB::select()->from('eventconsumptions')->as_object('Model_Eventconsumption')->where('event_id',$id)->where('status',1);
		
	//	$query=DB::select()->from('event_consumptions')->where('event_id',$id)->where('status',1);

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

/* End of file events.php */
