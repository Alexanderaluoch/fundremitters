<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
/* Default homepage function */
	function Main(){
	parent::__construct();
	$this->load->model('transactions','transaction');	
	$this->load->library('merchant');
    }

	public function index()
	{
		$this->load->view('client/userpage/transactions');
	}
	public function deposit()
	{
		$this->load->view('client/userpage/deposit');
	}
	public function transfer()
	{
		$this->load->view('client/userpage/transfer');
	}

	
	/* End of homepage function */
	public function paypaldeposit()
	{
        $this->merchant->load('paypal_express');
        $settings = $this->merchant->default_settings();
        $amount=$this->input->post('amount');
        
        $parameters=array();
        $parameters['amount']= $amount;
        $parameters['recipient_id']=1;
        $parameters['transaction_type']='deposit';
       	
       	$this->transaction->record_transaction($parameters);

		$params = array(
		    'amount' => $amount,
		    'currency' => 'USD',
		    'return_url' => 'http://localhost/projects/fundremitters/index.php/payments/deposit',
		    'cancel_url' => 'http://localhost/projects/fundremitters/index.php/client/home');

		$response = $this->merchant->purchase($params);


		
		if ($response->status()==Merchant_response::COMPLETE)
		{
			var_dump($response);
			$this->transaction->update_transaction();
			echo "reached here";
		}
		else
		{
			echo "Merchant response not complete";
		}
	}
	/* End of homepage function */
}


