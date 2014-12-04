<?php
class Controller_Admin_Items extends Controller_Admin {
	
	public function action_index()
	{
		$categories=Model_Category::find('all',array('order_by'=>'order','related'=>array('items' => array('order_by' => 'items.title'))));
		$items=Model_Item::find()->where('category_id', 0)->order_by('category_id', 'asc')->order_by('id', 'asc')->get();
		
		$uncategorized=Model_Category::factory(array(
			'label'=>'uncategorized'
		));
		$uncategorized->items=$items;
		$categories[]=$uncategorized;
				
		$data['categories']=$categories;
		
		$this->template->title = "Items";
		$this->template->content = View::factory('items/index', $data);
	}
	
	public function action_view($id = null)
	{
		$data['item'] = Model_Item::find($id);
		$this->template->title = "Item";
		$this->template->content = View::factory('items/view', $data);
	}
	
	public function action_create($id = null){
		if (Input::method() == 'POST'){
			$item = Model_Item::factory(array(
				'title' => Input::post('title'),
				'price' => Input::post('price'),
				'cost' => Input::post('cost'),
				'event_price' => Input::post('event_price'),
				'maxusage' => Input::post('maxusage'),
				'packing_unit' => Input::post('packing_unit'),
				'points' => 0,
				'category_id'=>Input::post('category_id'),
				'status'=>Input::post('status')
			));

			try{
				$item->save();
				Session::set_flash('notice', $this->flash_success('Added item #' . $item->id . '.'));

				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {

				Session::set_flash('notice', $this->flash_error('Could not save item.'.$e->getMessage()));
			}
		}
		$data['categories']=$this->get_categories_selection();
		$this->template->title = "Items";
		$this->template->content = View::factory('items/create',$data);

	}
	
	public function action_edit($id = null){
		$item = Model_Item::find($id);

		if (Input::method() == 'POST'){
		
			$item->title = Input::post('title');
			$item->price = Input::post('price');
			$item->cost = Input::post('cost');
			$item->event_price = Input::post('event_price');
			$item->maxusage = Input::post('maxusage');
			$item->category_id=Input::post('category_id');
			$item->status=Input::post('status');
			$item->packing_unit=Input::post('packing_unit');

			try{
				$item->save();
				Session::set_flash('notice', $this->flash_success('Updated item #' . $id));
				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {
				Session::set_flash('notice', $this->flash_error('Could not update item #' . $id));
			
			}
		}else{
			$this->template->set_global('item', $item, false);
		}
		$data['categories']=$this->get_categories_selection();
		$this->template->title = "Items";
		$this->template->content = View::factory('items/edit',$data);

	}
	
	public function action_delete($id = null){
		if ($item = Model_Item::find($id)){
			$item->delete();
			Session::set_flash('notice', $this->flash_success('Deleted item #' . $id));
		}else{
			Session::set_flash('notice', $this->flash_error('Could not delete item #' . $id));
		}
		Response::redirect('admin/items');
	}

	public function action_activate($id = null){
		if ($item = Model_Item::find($id)){
			try{
				$item->status=0;
				$item->save();
				Session::set_flash('notice', $this->flash_success('Updated item #' . $id));
				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {
				Session::set_flash('notice', $this->flash_error('Could not activate item #' . $id));
			
			}
		}else{
			Session::set_flash('notice', $this->flash_error('Could not activate item #' . $id));
		}
		Response::redirect('admin/items');
	}
	public function action_deactivate($id = null){
		if ($item = Model_Item::find($id)){
			try{
				$item->status=1;
				$item->save();
				Session::set_flash('notice', $this->flash_success('Updated item #' . $id));
				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {
				Session::set_flash('notice', $this->flash_error('Could not activate item #' . $id));
			
			}
		}else{
			Session::set_flash('notice', $this->flash_error('Could not activate item #' . $id));
		}
		Response::redirect('admin/items');
	}
	private function get_categories_selection(){
		$models=Model_Category::find('all');
		$categories=array();
		foreach($models as $model){
			$categories[$model->id]=$model->label;
		}
		return $categories;
	}
	
}

/* End of file items.php */
