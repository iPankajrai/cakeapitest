<?php
App::uses('AppController','Controller');
class ProductsController extends AppController{
	public $helpers = array('Html', 'Form');

	public function index() {
		$this->layout = false;
		print_r($this->Product->find('all'));
		die;
			// passing to view, in $products variable
	        $this->set('products', $this->Product->find('all'));
	        
	    }


	// CREATE API
	public function add(){
		// preventing the default layout to use
		$this->layout = false;
		// set the default response status as failed
		$response = array('status' => 'failed', 'message' => 'HTTP method not allowed');

		// check if request header is 'post'
		if($this->request->is('post')){
			// get data from request object
			$data = $this->request->input('json_decode', true);
			if(empty($data)){
				$data = $this->request->data;
			}

			// response if post data or form data is not passed
			$response = array('status' => 'failed', 'message' => 'Please provide form data');

			if(!empty($data)){
				// call the model's save function
				if($this->Product->save($data)){
					// return success
					$response = array('status' => 'success',  'message' => 'Product successfully created');
				}
				else{
					$response = array('status' => 'failed', 'message' => 'Failed to save data');
				}
			}
		}

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
		return $this->response->send();	
	}

	// READ API
	public function view($id = null){
		$this->layout = false;

		$response =array('status' => 'failed', 'message' => 'failed to process request');

		// check if $id was passed to this function
		if(!empty($id)){
			// find data by id
			$result = $this->Product->findById($id);
			if(!empty($result)){
				$response = array('status' => 'success', 'data' => $result);
			}
			else{
				$response['message'] = 'Found no matching data';
			}
		}
		else{
			$response['message'] = 'Please provide ID';
		}

		$this->response->type('application/json');
		$this->response->body(json_encode($response));

		return $this->response->send();
	}
}
?>