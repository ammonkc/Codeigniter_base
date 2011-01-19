<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>        
        <?php echo $yield_meta;?>
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"><![endif]-->
        
        <link rel="icon" type="image/x-icon" href="<?php echo base_url();?>assets/images/favicon.ico" />
        <title><?php echo $yield_page_title;?></title>
        
        <?php
            $this->carabiner->display();
            echo '<!--[if IE 6]>' . $this->carabiner->display_string('ie6') . '<![endif]-->';
            echo '<!--[if IE 7]>' . $this->carabiner->display_string('ie7') . '<![endif]-->';
            echo '<!--[if IE 8]>' . $this->carabiner->display_string('ie8') . '<![endif]-->';
            echo $yield_head_block;
        ?>
    </head>
    <body id="<?php echo $yield_bodyid;?>" class="<?php echo $yield_user_agent;?>">
        <?php echo $yield?>
    </body>
</html>