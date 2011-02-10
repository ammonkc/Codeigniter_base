<div id="" class="">
    <div id="forgot-password-container">
        <h2 class="typeset">Forgot Password</h2>
        <h3 class="typeset">Please enter your email address so we can send you an email to reset your password.</h3>
        
        <div id="infoMessage"><?php echo $message;?></div>
        
        <?php echo secure_form_open("auth/forgot_password", array('id' => 'forgot-password-form'));?>
            <ol>
              
              <li class="input">
                  <?php echo form_label('Email Address:', 'email');?>
                  <?php echo form_input($email);?>
              </li>              
              <li class="submit"><?php echo form_submit('submit', 'Submit', 'class="blueBtn"');?></li>
            </ol>
        <?php echo form_close();?>
    </div>
</div>