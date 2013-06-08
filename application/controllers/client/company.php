<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller {
/* Default homepage function */
	
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
	public function contact()
	{
		$data['main_content']='client/contact';
		$this->load->view('client/includes/template',$data);
	}
}


