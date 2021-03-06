<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Home controller of the system.
     * 	
     */
    function __construct() {
        parent::__construct();
        $this->layout->set_layout('main_layout');
        $this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		// Load MongoDB library instead of native db driver if required
		$this->config->item('use_mongodb', 'ion_auth') ?
			$this->load->library('mongo_db') :
			$this->load->database();	
    }

    public function index() {
    	if($this->ion_auth->logged_in()){
    		/* Load ACL only if user is logged in */
	    	$config = array('userID'=>$this->session->userdata['user_id']);
			$this->load->library('acl',$config);
    	}
		$current_action = $this->router->class.'/'.$this->router->method ;  
		$this->data['current_action'] = $current_action;
		if (!$this->ion_auth->logged_in())
		{	//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin())
		{ 	//redirect them to the home page because they must be an administrator to view this
			redirect($this->config->item('base_url'), 'refresh');
		}
		else
		{ //Admin user area
        	$this->layout->view('home', $this->data);
		}
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */