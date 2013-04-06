<?php
        $tmp = $arr_tambol;
        if ($mode == "tab1") {
	        echo $this->Form->input('tambol', array(
	            'options'=>$tmp,
	            'name' => 'data[Formjon1][tambol]',
	             'label' => false,
	             'div' => false,
	             'class' => 'txt'));	
        } else if ($mode == "tab2") {
        	echo $this->Form->input('tambol_tab2', array(
	            'options'=>$tmp,
	            'name' => 'data[Formjon2][tambol_id]',
	             'label' => false,
	             'div' => false,
	             'class' => 'txt'));	
        }
        
?>
        &nbsp;<span class="red">*</span>