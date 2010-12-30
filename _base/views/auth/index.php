<div id="content-box" class="">
    <div class='user-list-container'>    
    	<h2 class="typeset">Users</h2>
    	<h3 class="typeset">Below is a list of the users.</h3>
    	
    	<div id="infoMessage"><?php echo $message;?></div>
    	
    	<ul class="tableview round">
    	    <li class="header-row">
    	        <span><strong>First Name</strong></span>
    	        <span><strong>Last Name</strong></span>
    	        <span class="big"><strong>Email</strong></span>
    	        <span><strong>Group</strong></span>
    	        <span class="small"><strong>Status</strong></span>
    	    </li>
    		<?php $k=0; foreach ($users as $user):?>
    			<li class="<?=($k % 2 == 0 ? '' : 'odd');?>">
    				<span><?php echo $user['first_name']?></span>
    				<span><?php echo $user['last_name']?></span>
    				<span class="big"><?php echo $user['email'];?></span>
    				<span><?php echo $user['group_description'];?></span>
    				<span class="small"><?php echo ($user['active']) ? secure_anchor("auth/deactivate/".$user['id'], 'Active', array('class' => 'deactivate-button')) : secure_anchor("auth/activate/". $user['id'], 'Inactive', array('class' => 'activate-button'));?></span>
    			</li>
    			<?php $k++;?>
    		<?php endforeach;?>
    	</ul>
    	<p><a href="<?php echo secure_site_url('auth/create_user');?>">Create a new user</a></p>
    	
    </div>
</div>