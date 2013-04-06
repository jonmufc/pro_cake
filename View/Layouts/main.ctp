<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
            <?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        echo $this->Html->charset();
		/*echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');*/
		
		//echo $this->Html->css('tabs');
		echo $this->Html->css('redmond/jquery-ui-1.10.0.custom.css');
		//echo $this->Html->script('jquery-1.3.2.min');
		echo $this->Html->script('jquery-1.9.0');
		//echo $this->Html->script('jquery-ui-1.7.custom.min');
		echo $this->Html->script('jquery-ui-1.10.0.custom');
		echo $this->Html->css('main');
	?>
</head>
<body>
    <div id="header">
        <?php echo $this->Html->image('header.png', array('alt' => 'Header Image')); ?>
    </div>
    <div id="center">
        <?php echo $this->Session->flash(); ?>

	<?php echo $this->fetch('content'); ?>
    </div>
    <div id="footer">
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>