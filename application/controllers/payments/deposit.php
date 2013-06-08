<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit extends CI_Controller {
/* Default homepage function */
	
	public function index()
	{
		$data['main_content']='payments/deposit';
		$this->load->view('client/includes/template',$data);
	}
	public function withdraw()
	{
		$data['main_content']='payments/withdraw';
		$this->load->view('client/includes/template',$data);
	}
	public function send()
	{
		$data['main_content']='payments/send';
		$this->load->view('client/includes/template',$data);
	}
	/* End of homepage function */
	public function paypaldeposit()
	{
		$this->load->library('merchant');
        $this->merchant->load('paypal_express');

        $amount=$this->input->post('amount');
        $settings = $this->merchant->default_settings();
        

		$params = array(
		    'amount' => $amount,
		    'currency' => 'USD',
		    'return_url' => 'http://localhost/projects/fundremitters/index.php/client/home',
		    'cancel_url' => 'http://localhost/projects/fundremitters/index.php/client/home');

		$response = $this->merchant->purchase($params);

		if ($response->success())
			{
			    $data['main_content']='payments/deposit';
		        $this->load->view('client/includes/template',$data);
			}
			else
			{
				$data['main_content']='payments/deposit';
		        $this->load->view('client/includes/template',$data);
			}

		
	}
	/* End of homepage function */
}


