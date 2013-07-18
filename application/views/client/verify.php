<?php $this->load->view('client/includes/header'); ?>

<div class ="row-fluid register">
    <hr/>
    <div class="well span5" id="verification">
      <form class="form-vertical" id="verification_form" method="post" action="<?php echo base_url();?>index.php/client/register/verify_sms">
        <?php if(isset($disp_error)){?>
        <div class="alert alert-error"><?php echo $disp_error; ?></div>
         <?php }?>

        <h4>Step 2: Verify your Mobile Number</h4>
        <small><i>Enter the verification code which was sent to your number</i></small>
        <hr/>
        <div class="control-group">
          <div class="controls"> 
          <label class="control-label">
            <strong>Verification code:</strong> 
          </label>
          <input type="text" class="input-medium" name="verify_sms">
        </div>
        </div>

          <div class="control-group">
            <div class="controls">
              <button type="submit" class="btn btn-primary">SUBMIT</button>
            </div>
          </div>
        </form>
  </div>
</div>

<?php $this->load->view('client/includes/footer'); ?>
  