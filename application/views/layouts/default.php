<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>        
        <?php echo $yield_meta;?>
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"><![endif]-->
        
        <link rel="icon" type="image/x-icon" href="<?php echo base_url();?>assets/images/favicon.ico" />
        <title><?php echo $yield_page_title;?></title>
        
        <?php
            echo '<script src="'.base_url().'assets/javascript/modernizr-1.6.min.js" type="text/javascript"></script>';
            echo '<link href="'.base_url().'assets/css/styles.css" rel="stylesheet" media="screen" type="text/css" />';
            echo '<link rel="stylesheet" type="text/css" media="print" href="'.base_url().'assets/css/print.css" />';
            echo $this->assetlibpro->output('css');
            echo $this->assetlibpro->output('js');
            echo '<!--[if lt IE 7]><script src="'.base_url().'assets/javascript/ie/DD_belatedPNG_0.0.8a-min.js" type="text/javascript"></script><![endif]-->';
            echo '<!--[if lte IE 7]><script src="'.base_url().'assets/javascript/ie/DD_roundies_0.0.2a-min.js" type="text/javascript"></script><![endif]-->';
            echo '<!--[if IE 6]><link href="'.base_url().'assets/css/ie/ie6.css" rel="stylesheet" type="text/css" media="all"/><![endif]-->';
            echo '<!--[if IE 7]><link href="'.base_url().'assets/css/ie/ie7.css" rel="stylesheet" type="text/css" media="all"/><![endif]-->'; 
            echo '<!--[if IE 8]><link href="'.base_url().'assets/css/ie/ie8.css" rel="stylesheet" type="text/css" media="all"/><![endif]-->';            
            echo '<!--[if IE 6]><script src="'.base_url().'assets/javascript/ie/IE-6.js" type="text/javascript"></script><![endif]-->';
            echo '<!--[if IE 7]><script src="'.base_url().'assets/javascript/ie/IE-7.js" type="text/javascript"></script><![endif]-->';
            echo '<!--[if IE 8]><script src="'.base_url().'assets/javascript/ie/IE-8.js" type="text/javascript"></script><![endif]-->';
            echo $yield_head_block;
        ?>
    </head>
    <body id="<?php echo $yield_bodyid;?>" class="<?php echo $yield_user_agent;?>">
        <?php echo $yield?>
    </body>
</html>