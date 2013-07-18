<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller {
/* Default homepage function */
	
	//Constructor to check if you are logged in at the time of entrance;
	function Company(){
	  	parent::__construct();
	    $this->load->model('EzAuth_Model','ezauth');	
	    $this->ezauth->program='fundremitters';

		//if user has a cookie hash saved, ezauth can use it to login automatically
		//must have cookie helper enabled
		$this->ezauth->auto_login();

	    $this->ezauth->protected_pages = array(
	        'client'    	 	=>    	'user',			     //	user must be logged in to view page
	        'admin'   			=>    	'admin',			//	user must be administrator to view page
	        'custom_page'  		=>    	'store_manager',	//	user defined value
			'changepw'			=>		'user'
	    );
	}

	//	new remap function in 0.6, method is called including arguments now.	
	//  Used authorizes all activities by redirecting all functions to one common function called authorize
	function _remap($method) {
	        $auth = $this->ezauth->authorize($method, true);
	        if ($auth['authorize'] == true) {
				//	redirect with method arguments
				//	by marlar on CodeIgniter forums
				$segments = array_slice($this->uri->segment_array(),2);
				call_user_func_array(array(&$this, $method), $segments);
	        } else {
	            // user login information incorrect, so show login screen again
	            redirect('client/company/login');
	        }
	}

	function login($data = array()) {              	
            $login_ok = $this->ezauth->login();    // $login_ok is true or false depending on user login information
            if ($login_ok['authorize'] == true) {
				$this->ezauth->remember_user();	 // store cookie hash for auto-login
				redirect('client/company/userpage');
			} else {
				$data['error_string'] = $login_ok['error'];	
				$this->load->view('client/profile/login_view',$data);
			}
	}

	function logout() {
	$this->ezauth->logout();
	redirect('client/company/login');
	}


	public function index()
	{
		$data['main_content']='client/about';
		$this->load->view('client/includes/template',$data);
	}
	/* End of homepage function */
	public function faqs()
	{
		$data['main_content']='client/faqs';
		$this->load->view('client/includes/template',$data);
	}
	public function services()
	{
		$data['main_content']='client/services';
		$this->load->view('client/includes/template',$data);
	}
	public function userpage()
	{
		$this->load->view('client/userpage/transactions');
	}
	public function register()
	{

		$data['main_content']='client/register';		

		// Loading the Country model
		$this->load->model('countries','countries');
		// Sending the drop down list to the view
		$data['countries'] = $this->countries->get_dropdownlist();

		$this->load->view('client/includes/template',$data);
	}
	public function contact()
	{
		$data['main_content']='client/contact';
		$this->load->view('client/includes/template',$data);
	}
}


