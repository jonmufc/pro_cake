
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
        

        
	oTable = $('#tbl').dataTable({
            'bJQueryUI': true,
            "aLengthMenu": [10,50,100,250,500],
            "iDisplayLength" : 100,
            'sPaginationType': 'full_numbers',
            'aoColumns': [
            null,null,null,null,{'bSortable': false},
            null,null,{'bSortable': false},{'bSortable': false},{'bSortable': false}
            ]
        });
    });
    
</script>
<br/><br/>
<?php
echo $this->Form->create('Survey', array('inputDefaults' => array('div'=>false),'action'=>'show'));


echo "&nbsp;&nbsp;" . $this->Form->button('&nbsp;แสดงข้อมูลเจ้าหน้าที่สำรวจ&nbsp;', 
                        array('div'=>false,'type'=>'submit','name'=>'showofficerbtn','value'=>'show','class'=>'large blue awesome'));

?>
<br/>
<br/>
<table width="100%">
    <tr>
        <td width="50%" valign="top">
            

            <table width="70%" cellpadding="5" cellspacing="3" border="0" align="center">
                <tr>
                    <td colspan="2">&nbsp;&nbsp;<b>ค้นหาข้อมูลจากฐานข้อมูลโดย</b></td>
                </tr>
                <tr>
                    <td width="20%" align="right">
                        เงื่อนไข :&nbsp;
                    </td>
                    <td>
                        <?php
                            $tmp = array();
                            $tmp["1"] = "ชื่อของกลุ่มเป้าหมาย";
                            $tmp["4"] = "ชื่อของผู้สำรวจ";
                            $tmp["2"] = "นามสกุลของกลุ่มเป้าหมาย";
                            $tmp["5"] = "นามสกุลของผู้สำรวจ";
                            $tmp["3"] = "อายุของกลุ่มเป้าหมาย";
                            echo $this->Form->input('search_type', array(
                                'options' => $tmp,
                                'label' => false,
                                'div' => false,
                                'value' => $search_type
                                )
                            );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="right">คำค้นหา :&nbsp;</td>
                    <td>
                        <?php
                        echo $this-> Form -> input('keyword', array(
                           'label' => false,
                           'size' => '50',
                           'div' => false,
                           'value' => $keyword
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <?php
                        $url = Router::url($this->here, true);
                        echo $this->Form->button('&nbsp;ค้นหา&nbsp;', 
                        array('type'=>'submit','name'=>'searchbtn','value'=>'search','class'=>'css3button'));
                        ?>
                        <?php
                        echo $this->Form->button('&nbsp;รีเฟรชหน้านี้&nbsp;', 
                        array('div'=>false,'type'=>'submit','name'=>'refreshbtn','value'=>'refresh','class'=>'css3button'));
                        ?>
                        
                    </td>
                </tr>
            </table>
        </td>
         <td valign="top">
            <b>หมายเหตุ</b>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td><span style="color:#4d5356">
                       ** สำหรับเงื่อนไขการค้นหาแบบอายุ สามารถใส่เครื่องหมาย > หรือ < ได้ ดังนี้<br/>
                       - < 10<br/>
                       - > 20<br/>
                       - 10 < X < 20
                       </span>
                    </td>
                </tr>
            </table>
         </td>
    </tr>
</table>
<br/><br/>
<?php
if(empty($result)){
    echo "&nbsp;&nbsp;&nbsp;<b>ไม่พบข้อมูลในฐานข้อมูล</b>";
}else{
?>
<b><span style="color:#151bb2;">&nbsp;&nbsp;รายชื่อของกลุ่มเป้าหมายทั้งหมดในระบบ</span></b><br/>
<center>
<table id="tbl" width="99%" border="1" cellpadding="0" cellspacing="1" align="center" class="display" bgcolor="#e7e9eb" style="border-color:#e7e9eb;font-family:tahoma">
    <thead>
        <tr>
        <th width="5%">เลขที่</th>
        <th width="5%"># รหัส</th>
        <th width="15%">ชื่อ</th>
        <th width="15%">นามสกุล</th>
        <th width="10%">วัน/เดือน/ปี</th>
        <th width="5%">อายุ</th>
        <th width="20%">ผู้สำรวจ</th>
        <th>แก้ไขข้อมูล<br/>ผู้สำรวจ</th>
        <th>แก้ไขข้อมูล<br/>กลุ่มเป้าหมาย</th>
        <th>แก้ไขข้อมูล<br/>ครอบครัว</th>
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
            echo "<td align='center'><span style='color:#8f1339;font-weight:bold;'>" . $t["Target"]["tid"] . "</span></td>";
            echo "<td style='padding-left:5px;'>" . $t["TargetPeople"]["prefix"] . " " . $t["TargetPeople"]["name"] . "</td>";
            echo "<td style='padding-left:5px;'>" . $t["TargetPeople"]["last_name"] . "</td>";
            if($t["TargetPeople"]["age"] != ""){           
                echo "<td style='padding-left:5px;' align='center' >" . $t["TargetPeople"]["birthday"] . "</td>";
                echo "<td align='center'>" . $t["TargetPeople"]["age"] . "</td>";
            }else{
                echo "<td style='padding-left:5px;' align='center'>-</td>";
                echo "<td align='center'>-</td>";
            }
            echo "<td style='padding-left:5px;'>" . $t["Officer"]["name"] . " " . $t["Officer"]["last_name"] . "</td>";
            //#Edit [officer]
            echo "<td align='center'>" . $this->Html->link($this->Html->image('edit.png',array('alt'=>'แก้ไขข้อมูลผู้สำรวจ')),array('action'=>'edit',0,2,$t["Target"]["officer_id"]), array('target'=>'_blank','escape'=>false)) . "</td>"; 
            //#Edit [target]
            echo "<td align='center'>" . $this->Html->link($this->Html->image('edit_target.png',array('alt'=>'แก้ไขข้อมูลกลุ่มเป้าหมาย')),array('action'=>'edit',2,1,$t["Target"]["people_id"], $t["Target"]["officer_id"]), array('target'=>'_blank','escape'=>false)) . "</td>"; 
            //#Edit [Family Details]
            echo "<td align='center'>" . $this->Html->link($this->Html->image('family.png',array('alt'=>'แก้ไขรายละเอียดครอบครัว')),array('action'=>'edit',3,$t["Target"]["people_id"]), array('target'=>'_blank','escape'=>false)) . "</td>"; 
            
            echo "</tr>";            
            $count = $count + 1;
        endforeach;
        ?>
    </tbody>
</table>
</center>
<?php } ?>