<div class="row-fluid">
  <div class="span3" id="userbar">
  	<a href="<?php echo site_url('payments/deposit') ?>"><div class="bar"><i class="icon-user icon-large"></i> DEPOSIT MONEY</div></a>

  	<a href="<?php echo site_url('payments/deposit/withdraw') ?>"><div class="bar"><i class="icon-user icon-large"></i> WITHDRAW MONEY</div></a>

  	<a href="<?php echo site_url('payments/deposit/send') ?>"><div class="bar"><i class="icon-user icon-large"></i> SEND MONEY</div></a>

  	<a href=""><div class="bar"><i class="icon-user icon-large"></i> MY PROFILE</div></a>

  	<a href=""><div class="bar"><i class="icon-off icon-large"></i> LOGOUT</div></a>
  </div>
  <div class="span9">
  	<table class="table table-striped">
	  <span class="label label-success">Withdrawal Transactions</span>
	  <thead>
	    <tr>
	      <th>Date</th>
	      <th>Amount</th>
	       <th>Status</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td>...</td>
	      <td>...</td>
	       <td>...</td>
	    </tr>
	  </tbody>
	</table>

	<table class="table table-striped">
	  <span class="label label-success">Transfer Transactions</span>
	  <thead>
	    <tr>
	      <th>Date</th>
	      <th>Amount</th>
	      <th>Receiver</th>
	      <th>Status</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td>...</td>
	      <td>...</td>
	      <td>...</td>
	       <td>...</td>
	    </tr>
	  </tbody>
	</table>
  </div>
</div>
