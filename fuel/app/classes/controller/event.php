<?php

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 * 
 * @package  app
 * @extends  Controller
 */
class Controller_Event extends Controller_Template {
	 public $template = 'template_event';
	/**
	 * The index action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{	
		$items=DB::select("*")->from('items')->where('status','<>',1)->order_by('category_id', 'asc')->order_by('title', 'asc')->execute();
    	$categories=DB::select("*")->from('categories')->order_by('order','asc')->execute();
		$data['items']=Format::factory( $items->as_array() )->to_json();
		$data['categories']=Format::factory($categories->as_array())->to_json();
		$this->template->title ="Events Bar";
		$this->template->content = View::factory('event/index',$data,false);
	}
	public function action_test(){
		$this->response->body="test";
	}
	
	
	/**
	 * The 404 action for the application.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_404()
	{
		$messages = array('Aw, crap!', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');
		$data['title'] = $messages[array_rand($messages)];

		// Set a HTTP 404 output header
		$this->response->status = 404;
		$this->response->body = View::factory('event/404', $data);
	}
}

/* End of file welcome.php */