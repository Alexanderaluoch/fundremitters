<?php $this->load->view('client/userpage/content-top'); ?>
<?php $this->load->view('client/userpage/sidebar'); ?>

 <div class="span10" id="userpage-content">
  	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th>Date:</th>
	      <th>Transaction Type:</th>
	      <th>Amount:</th>
	      <th>Status:</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td>27th July 2013</td>	
	       <td>Withdraw</td>
	      <td>KES 5,000</td>
	       <td><span class="label label-default">Pending</span></td>
	    </tr>
	     <tr>
	      <td>27th July 2013</td>	
	       <td>Withdraw</td>
	       <td>KES 5,000</td>
	       <td><span class="label label-warning">Cancelled</span></td>
	    </tr>
	     <tr>
	      <td>27th July 2013</td>	
	       <td>Withdraw</td>
	      <td>KES 5,000</td>
	       <td><span class="label label-default">Pending</span></td>
	    </tr>
	     <tr>
	      <td>27th July 2013</td>	
	       <td>Withdraw</td>
	      <td>KES 5,000</td>
	       <td><span class="label label-success">Complete</span></td>
	    </tr>
	  </tbody>
	</table>
  </div>

  </div>
<?php $this->load->view('client/includes/footer'); ?>
