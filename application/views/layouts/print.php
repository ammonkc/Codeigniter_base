<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>        
        <?php echo $yield_meta;?>
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1"><![endif]-->        
        <link rel="icon" type="image/x-icon" href="<?=base_url();?>assets/images/favicon.ico" />
        <title><?php echo $yield_page_title;?></title>        
        <?php
            $this->carabiner->display();
            echo '<!--[if IE 6]>' . $this->carabiner->display_string('ie6') . '<![endif]-->';
            echo '<!--[if IE 7]>' . $this->carabiner->display_string('ie7') . '<![endif]-->';
            echo '<!--[if IE 8]>' . $this->carabiner->display_string('ie8') . '<![endif]-->';
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
        <?php echo $yield; ?>
    </body>
</html>