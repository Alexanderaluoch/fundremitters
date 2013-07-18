    <div class ="row-fluid register">
      <hr/>
      <div class="well span5" id="registration">
        <form class="form-vertical" id="registration_form" method="post" action="<?php echo base_url();?>index.php/client/register">
        <?php if(isset($disp_error)){?>
        <div class="alert alert-error"><?php echo $disp_error; ?></div>
         <?php }?>

        <legend>Fill in your details:</legend>
        <div class="control-group">
          <div class="controls"> 
          <label class="control-label">
            <b>Names:</b> 
          </label>
          <input type="text" class="input-medium" placeholder="First Name" name="first_name">
          <input type="text" class="input-medium" placeholder="Last Name" name="last_name">
        </div>
      </div>

      <div class="control-group">
          <div class="controls">   
          <label class="control-label">
            <b>Enter Your Email Address:</b> 
          </label>
          <input type="text" class="input-large popovers"  name="email" data-content="You will use your email address to login to your account. A verification email would be sent to activate the email." title="Why?">
        </div>
      </div>

      <div class="control-group">
         <div class="controls">
          <label class="control-label">
            <b>Create a password:</b> 
          </label>
          <input type="password" class="input-large popovers"  id="password1" name="password1" data-content="Your password should be greater than <b>8 characters</b>. This is to ensure security of your account." title="Best Practice:">
         </div>
       </div>
        
        <div class="control-group">
          <div class="controls"> 
          <label class="control-label">
            <b>Confirm the password:</b> 
          </label>
          <input type="password" class="input-large"  name="password2">
        </div>
       </div>

          <div class="control-group">
            <div class="controls">          
            <label class="control-label">
              <b>Mobile Phone:</b> 
            </label>         
            <div class="input-prepend">
              <div class="btn-group">
                 <button class="btn dropdown-toggle" data-toggle="dropdown"><img src="http://localhost/projects/fundremitters/img/flags/KE.png" id="set_country_flags">
                  <span class="caret"></span> 
                </button>
                <ul class="dropdown-menu">
                  <?php echo country_dropdown($countries,'countries',array('KE','UG','TZ'),'KE', TRUE, TRUE); ?>
                </ul>

              </div>
              
              <input class="input-block-level input-medium calling_code popovers" type="text" value="+254" name="mobile_number" id="prependedDropdownButton" data-content="A verification code will be sent to verify your mobile number" title="Notice:"> 
            </div>
        </div>
      </div>

        <div class="control-group">
          <div class="controls">       
            <label class="control-label">
              <b>Country:</b> 
            </label>
              <?php echo country_dropdown($countries,'countries',array('KE','UG','TZ'),'KE', TRUE, FALSE); ?>
         </div>
       </div>


          <div class="control-group">
            <div class="controls">
              <label class="checkbox">
                <input type="checkbox" value="on" name="policy_check"> 
                I Agree to PesaPay<a>Terms and Conditions</a> and <a>Privacy Policy</a>
              </label>
              <button type="submit" class="btn btn-primary">Register</button>
            </div>
          </div>
        </form>
      </div>
      
      <div class="span6">
        <h4>Step 1:Account Registration</h4>
          <hr/>
          <div class="">
            <h5><i class="icon-large icon-money"></i>&nbsp Affordable</h5>  
            <p>Only a small fee charged per transaction,free to sign up, no fixed fees. <a data-toggle="tooltip" title="" class="feature-1" href="#feature-1">Learn more</a> </p>
          </div>
          
          <div class="">
            <h5><i class="icon-large icon-lock"></i>&nbsp Secure</h5> 
            <p>Fundremitters operates within the trusted MPESA & Paypal platform hence secure. <a data-toggle="tooltip" title="Learn more about security" class="feature-1" href="#feature-1">Learn more</a></p>
          </div>
         
          <div class="">
            <h5><i class="icon-large icon-mobile-phone"></i>&nbsp Convinient</h5>
            <p>1-click money transfer to & from your PayPal OR MPESA Account with no-hassles involved. <a data-toggle="tooltip" title="" class="feature-1" href="#feature-1">Learn more</a></p>
          </div>
      </div>
  </div>
   


    <script src="<?php echo base_url() ?>bootstrap/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/jquery.validate.js"></script>
    
    <script type="text/javascript">
    
    $(document).ready(function(){
      //Pop-overs:
      $('.popovers').popover({
        html:'true',
        trigger:'focus',
      });

      //Function to validate forms
        $("#registration_form").validate({
        rules:{
            //first_name:"required",
            last_name:"required",
            email:{
            required:true,
            email: true
            },
            password1:{
            required:true,
            minlength:8
            },
            password2:{
            required:true,
            equalTo: "#password1"
            },
            phone_number:{
              required:true,
              minlength:12
            },

            policy_check:{
            }
        },
      
        highlight: function(element) {
           $(element).closest('.control-group').removeClass('success').addClass('error');
        },
         
        success: function(element) {
            element
            .text('OK!').addClass('valid')
            .closest('.control-group').removeClass('error');
        }
    });

    //Function to change the calling code and the flag
    $(".dropdown-menu a").click(function(){
        var code = $(this).attr("id"); 
        var img1 = $(this).find('img'); //Returns the image after the anchor tag
        var img  = $(img1).attr("src");
        $('.calling_code').val(code); //change the value of the input box
        $('#set_country_flags').attr('src',img);
    });

    });//End document .ready
    </script>
