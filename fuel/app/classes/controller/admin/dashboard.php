<?php 

class Controller_Admin_Dashboard extends Controller_Admin {

	public function action_index(){
		
		$this->template->title="Dashboard";
        
     	$this->template->title = "Items";


        $events=DB::query(" select year(now()) as year, month(now()) as month, count(*) as items, round(sum(price),2) as revenue, round(sum(profit),2) as profit from eventconsumptions where month(FROM_UNIXTIME(created_at)) = month(now()) and year(FROM_UNIXTIME(created_at)) = year(now())", DB::SELECT)->execute()->as_array();
        $users=DB::query(" select year(now()) as year, month(now()) as month, count(*) as items, round(sum(price),2) as revenue, round(sum(profit),2) as profit from consumptions where month(FROM_UNIXTIME(created_at)) = month(now()) and year(FROM_UNIXTIME(created_at)) = year(now())", DB::SELECT)->execute()->as_array();
        
        $data['month']=$events[0]['year'] . '-' . $events[0]['month'];

        $data['useritems']=$users[0]['items'];
        $data['usertotal']=$users[0]['revenue'];
        $data['userprofit']=$users[0]['profit'];
        $data['eventitems']=$events[0]['items'];
        $data['eventtotal']=$events[0]['revenue'];
        $data['eventprofit']=$events[0]['profit'];

       	
        $this->template->content=View::factory('admin/dashboard',$data);
	}
	
	private function get_sales($days){
	
		return $consumptions=DB::select()->from('consumptions')->as_object('Model_Consumption')->where('created_at','>=',(time()-$days*24*3600))->and_where('status','!=',0)->execute()->as_array();
		
	}
}

?>