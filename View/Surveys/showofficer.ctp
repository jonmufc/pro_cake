<?php
        echo $this->Html->css('DataTables-1.9.4/media/css/demo_table_jui');
	echo $this->Html->script('DataTables-1.9.4/media/js/jquery.dataTables');
?>
<script type="text/javascript">
	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	    "date-uk-pre": function ( a ) {
		var ukDatea = a.split('/');
		return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
	    },
	 
	    "date-uk-asc": function ( a, b ) {
		return ((a < b) ? -1 : ((a > b) ? 1 : 0));
	    },
	 
	    "date-uk-desc": function ( a, b ) {
		return ((a < b) ? 1 : ((a > b) ? -1 : 0));
	    }
	} );
        
      
           
    $(document).ready(function(){
        

        
	oTable = $('#tblofficer').dataTable({
            'bJQueryUI': true,
            "aLengthMenu": [10,50,100,250,500],
            "iDisplayLength" : 100,
            'sPaginationType': 'full_numbers',
            'aoColumns': [
            null,null,null,null,null,null,null,null,{'bSortable': false},

            ]
        });
    });
    
</script>
<br/>
<?php

echo $this->Form->create('Survey', array('inputDefaults' => array('div'=>false),'action'=>'showofficer'));

echo "&nbsp;&nbsp;" . $this->Form->button('&nbsp;เพิ่มเจ้าหน้าที่สำรวจ&nbsp;',
                        array('div'=>false,'type'=>'submit','name'=>'addnewbtn','value'=>'add','class'=>'large blue awesome'));

			echo "&nbsp;&nbsp;" . $this->Form->button('&nbsp;ข้อมูลกลุ่มเป้าหมาย&nbsp;', 
                        array('div'=>false,'type'=>'submit','name'=>'showbtn','value'=>'show','class'=>'large blue awesome'));
?>
<br/>
<br/>

<?php
if(empty($result)){
    echo "&nbsp;&nbsp;&nbsp;<b>ไม่พบข้อมูลในฐานข้อมูล</b>";
}else{
?>
<b><span style="color:#151bb2;">&nbsp;&nbsp;รายชื่อของผู้สำรวจข้อมูลทั้งหมดในระบบ</span></b><br/>
<center>
<table id="tblofficer" width="100%" border="1" cellpadding="0" cellspacing="1" align="center" class="display" bgcolor="#e7e9eb" style="border-color:#e7e9eb;font-family:tahoma">
    <thead>
        <tr>
        <th width="5%">เลขที่</th>
        <th width="10%"># รหัสเจ้าหน้าที่</th>
        <th width="15%">ชื่อ</th>
        <th width="15%">นามสกุล</th>
	<th width="15%">ระดับ อพม.</th>
        <th width="10%">ตำบล</th>
        <th width="10%">อำเภอ</th>
	<th width="10%">จังหวัด</th>
        <th>แก้ไขข้อมูล</th>
        
        </tr>
    </thead>
    <tbody>
        <?php
        //print_r($result);
        $count = 1;
        foreach ($result as $t):
        
            //$bg = ($count%2 == 0)? '#ffffff': '#d5f0fb';

            echo "<tr class='row'>";

           
            echo "<td align='center'>" . $count. "</td>";
            echo "<td align='center'><span style='color:#8f1339;font-weight:bold;'>" . $t["Officer"]["officer_id"] . "</span></td>";
            echo "<td style='padding-left:5px;'>" . $t["People"]["prefix"] . " " . $t["People"]["name"] . "</td>";
            echo "<td style='padding-left:5px;'>" . $t["People"]["last_name"] . "</td>";
	    echo "<td style='padding-left:5px;' align='center'>" . $t["Officer"]["officer_type"] . "</td>";
            echo "<td style='padding-left:5px;' align='center'>" . $t["People"]["tambol"] . "</td>";
	    echo "<td style='padding-left:5px;' align='center'>" . $t["People"]["amphor"] . "</td>";
	    echo "<td style='padding-left:5px;' align='center'>" . $t["People"]["province"] . "</td>";
            //#Edit [officer]
            echo "<td align='center'>" . $this->Html->link($this->Html->image('edit.png',array('alt'=>'แก้ไขข้อมูล')),array('action'=>'edit',0,2,$t["Officer"]["officer_id"]), array('target'=>'_blank','escape'=>false)) . "</td>"; 
            
            //echo "<td align='center'>" . $this->Html->link($this->Html->image('family.png',array('alt'=>'แก้ไขรายละเอียดครอบครัว')),array('action'=>'edit',3,$t["Target"]["people_id"]), array('target'=>'_blank','escape'=>false)) . "</td>"; 
            
            echo "</tr>";            
            $count = $count + 1;
        endforeach;
        ?>
    </tbody>
</table>
</center>
<?php } ?>