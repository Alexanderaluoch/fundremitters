<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
/* Default homepage function */	
	public function index()
	{
		$data['main_content']='client/home';
		$this->load->view('client/includes/template',$data);
	}
	/* End of homepage function */
}


