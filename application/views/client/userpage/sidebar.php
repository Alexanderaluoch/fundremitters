<div class="span2" id="sidebar">
	<ul class="nav nav-pills pull-right">

	  	<li <?php if($this->uri->segment(3)==""){echo 'class="active"';}?>><a href="<?php echo site_url('userpage/main/') ?>"><div class="bar"><i class="icon-suitcase icon-large"></i>&nbsp My Transactions</div></a></li>
	  	<li <?php if($this->uri->segment(3)=="deposit"){echo 'class="active"';} ?>><a href="<?php echo site_url('userpage/main/deposit') ?>"><div class="bar"><i class="icon-arrow-down icon-large"></i>&nbsp Deposit Money</div></a></li>
	  	<li <?php if($this->uri->segment(3)=="transfer"){echo 'class="active"';} ?>><a href="<?php echo site_url('userpage/main/transfer') ?>"><div class="bar"><i class="icon-mail-forward icon-large"></i>&nbsp Transfer Money</div></a></li>
	  	<li <?php if($this->uri->segment(3)=="changepw"){echo 'class="active"';} ?>><a href="<?php echo site_url('client/register/changepw') ?>"><div class="bar"><i class="icon-user icon-large"></i>&nbsp Profile Settings</div></a></li>  	
	 </ul>
</div>
