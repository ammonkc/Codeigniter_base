<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>        
        <?=$yield_meta;?>
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"><![endif]-->        
        <link rel="icon" type="image/x-icon" href="<?=base_url();?>assets/images/favicon.ico" />
        <title><?=$yield_page_title;?></title>        
        <?php
            echo '<script src="'.base_url().'assets/javascript/modernizr-1.5.min.js" type="text/javascript"></script>';
            echo '<!--[if lt IE 9]><script src="'.base_url().'assets/javascript/ie/html5.js"></script><![endif]-->';
            echo '<link href="'.base_url().'assets/css/styles.css" rel="stylesheet" media="screen" type="text/css" />';
            echo '<link rel="stylesheet" type="text/css" media="print" href="'.base_url().'assets/css/print.css" />';
            echo $this->assetlibpro->output('css');
            echo $this->assetlibpro->output('js');
            echo '<!--[if IE 6]><link rel="stylesheet" type="text/css" media="all" href="'.base_url().'assets/css/ie/ie6.css"/><![endif]-->';
            echo '<!--[if IE 7]><link rel="stylesheet" type="text/css" media="all" href="'.base_url().'assets/css/ie/ie7.css"/><![endif]-->';            
            echo '<!--[if IE 6]><script src="'.base_url().'assets/javascript/ie/IE-6.js"></script><![endif]-->';
            echo '<!--[if IE 7]><script src="'.base_url().'assets/javascript/ie/IE-7.js"></script><![endif]-->';
            echo $yield_head_block;
        ?>
        <script language="Javascript1.2">
          <!--
          function printpage() {
              window.print();
          }
          //-->
        </script>
    </head>
    <body onload="printpage()">
        <?=$yield?>
    </body>
</html>