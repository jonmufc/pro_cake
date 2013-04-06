<?php
	echo $this->Html->script('add_tab2.js');
?>
<?php
//echo $this->Form->create('Post');
?>
<br/>
<input type="hidden" id="current_tab_no" value="<?php echo($tab_no);?>"/>
<input type="hidden" id="page" value="add"/>
<div id="container" style="margin:0 auto;width:90%">
    <center><span style="font-size:13pt;"><b>ระบบการจัดการฐานข้อมูลกลุ่มเป้าหมาย / ผู้ประสบปัญหาทางสังคมในหมู่บ้าน / ชุมชน</b></span></center>
    <br/>
    
    <?php if($tab_no == "0"){
		echo "&nbsp;&nbsp;" . $this->Form->button('&nbsp;แสดงข้อมูลเจ้าหน้าที่สำรวจ&nbsp;',
		array('div'=>false,'type'=>'submit','name'=>'showbtn','value'=>'show','class'=>'large blue awesome'));
		echo "<br/><br/>";
    }
    ?>
    
<span id="web_type" style="display:none">Add</span>
<div id="tabs" style="width:95%;">
		
    <ul>        
        <li><a href="#tab-1">ข้อมูลกลุ่มเป้าหมาย</a></li>
        <li><a href="#tab-2">ข้อมูลสมาชิกในครอบครัว</a></li>
    </ul>
	
    <!-- Begin Tab 1 เพิ่มข้อมูลกลุ่มเป้าหมาย -->
    <div id="tab-1" class="ui-tabs-panel ui-tabs-hide">
        <?php  
        		echo $this->Form->create('Formjon2');
        ?> 
    	<table width="100%" cellpadding="5" cellspacing="8" border="0" id="tbl_formjon2">
            <tr>
                <td>
                เลขประจำตัวประชาชน &nbsp;
               <?php
               echo $this->Form->input('id_number', array('type'=>'text',
                            'div' => false,'size'=>'25','style'=>'padding:1px 1px 1px 1px;text-align:center;font-size:1.3em;background-color:#FFFF99',
                            'label'=>false,'maxlength'=>13,
                            'class'=>'target_id_number',
                            'id' => 'id_number'
                            ));
                echo '&nbsp;';
                 /*for($i=1;$i<=13;$i++){
                    echo $this->Form->input('iddigit_'.$i, array('type'=>'text',
                            'div' => false,'size'=>'1','style'=>'border:1px solid #000;text-align:center',
                            'label'=>false,'maxlength'=>1,
                            'class'=>'target_id_number',
                            'id' => 'iddigit_'.$i
                            ));
                    echo '&nbsp;';
                 }*/
                 ?> 
                &nbsp;<span class="red">*</span><span id="check_id_result"></span>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <!-- Begin row -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="right">
                                คำนำหน้าชื่อ :   &nbsp;                  
                            </td>
                            <td>
                                <?php
                                $tmp = array();
                                $tmp["นาย"] = "นาย";
                                $tmp["นาง"] = "นาง";
                                $tmp["นางสาว"] = "นางสาว";
                                $tmp["เด็กหญิง"] = "เด็กหญิง";
                                $tmp["เด็กชาย"] = "เด็กชาย";
                                
                                if($mode == "1"){
                                    $tmp_default = "นาย";
                                }else{
                                    $tmp_default = $prefix;
                                }
                                echo $this->Form->input('prefix', array(
                                    'options'=>$tmp,
                                     'label' => false,
                                     'default' => $tmp_default,
                                     'div' => false,
                                     'class' => 'txt'));?> 
                            </td>
                            <td align="right">ชื่อ :&nbsp;</td>
                            <td>
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $firstname;
                                }
                                echo $this->Form->input('firstname',array('size'=>'40','default'=>$tmp_default,
                                                                          'label'=>false, 'class' => 'txt','div' => false));
                                ?>&nbsp;<span class="red">*</span>
                            </td>
                            <td align="right">นามสกุล :&nbsp;</td>
                            <td>
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('lastname',array('size'=>'40','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt','div'=>false));
                                ?>&nbsp;<span class="red">*</span>
                            </td>
                        </tr>
                    </table>
                    <!-- Finish row -->
                </td>
            </tr>
            <tr>
                <td>
                    <!-- Begin row -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="left" width="17%">
                                วัน / เดือน / ปี พ.ศ. เกิด&nbsp;                  
                            </td>
                            <td width="25%">
                            	<?php
                                	echo $this->Form->input('txt_birthday_cus', array('label'=>false,'div'=>false,'type'=>'text','class'=>'txt txt_calendar','size'=>15));
                                ?>
                                <?php
                                //echo $this->Form->input('birthday_date', array('label'=>false,'div'=>false,'type'=>'text','maxlength'=>2,'class'=>'txt','size'=>2)); ?>
                                
                                <?php
                                //echo $this->Form->input('birthday_month', array('label'=>false,'div'=>false,'type'=>'text','maxlength'=>2,'class'=>'txt','size'=>2)); ?>
                                
                                <?php
                                //echo $this->Form->input('birthday_year', array('label'=>false,'div'=>false,'type'=>'text','maxlength'=>4,'class'=>'txt','size'=>4)); ?>
                                &nbsp;<span class="red">*</span>
                            </td>
                            <td align="right" width="10%">สถานภาพ :&nbsp;</td>
                            <td width="30%">
                                <?php
                                $tmp = array();
                                $tmp["โสด"] = "&nbsp;โสด";
                                $tmp["สมรส"] = "&nbsp;สมรส";
                                $tmp["หย่าร้าง"] = "&nbsp;หย่าร้าง";
                                
                                 echo $this->Form->input('marital_status', array(
                                    'type'=>'radio',
                                     'separator'=>'&nbsp;&nbsp;&nbsp;',
                                    'before' => '&nbsp;',
                                    'after' => '&nbsp;',
                                    'between' => '',
                                    'legend' => false,
                                    'options'=>$tmp,
                                     'label' => false,
                                     'default' => "โสด",
                                     'div' => false,
                                     'class' => 'txt'));?>&nbsp;<span class="red">*</span>
                            </td>
                            <td align="right" width="40%">&nbsp;</td>
                            <td>&nbsp;
                            </td>
                        </tr>
                    </table>
                    <!-- Finish row -->
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td valign="top">
                    <!-- Begin row-->
                    <b>[[ ที่อยู่ ]]</b><br/><br/>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        	<td align="right" width="10%" valign="top">
                                บ้านเลขที่ :&nbsp;</td>
                            <td width="15%" valign="top">
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('txtbanno_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt','div'=>false));
                                ?>
                                &nbsp;
                            </td>
                            <td align="right" width="10%" valign="top">
                                ชื่ออาคาร :&nbsp;</td>
                            <td width="20%" valign="top">
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('txtbdname_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt','div'=>false));
                                ?>
                                &nbsp;
                            </td>
                            <td align="right" valign="top">
                                ชั้นที่ :&nbsp;
                            </td>
                            <td valign="top">
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('txtfloor_tab2',array('size'=>'5','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt','div'=>false));
                                ?></td>
                            <td align="right" valign="top">
                                หมู่บ้าน :&nbsp;
                            </td>
                            <td valign="top"> 
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('housegroup_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt','div'=>false));
                                ?></td>
                            <td align="right" valign="top">
                                 หมู่ที่ :&nbsp;
                            </td>
                            <td valign="top">
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('housename_tab2',array('size'=>'5','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt','div'=>false));
                                ?></td>
                        </tr>
                    </table>
                    <!-- Finish row -->
                </td>
            </tr>
            <tr>
                <td>
                    <!-- Begin row -->
                    <table width="100%" cellpadding="0" cellspacing="5" border="0">
                        <tr>
                            <td align="right">
                                ซอย :&nbsp;
                            </td>
                            <td>
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('txtsoi_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt'));
                                ?>
                            </td>
                            <td align="right">
                                ถนน :&nbsp;
                            </td>
                            <td>
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                echo $this->Form->input('txtstreet_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                         'label'=>false,'class'=>'txt'));
                                ?>
                            </td>
                            <td align="right">
                                อำเภอ :&nbsp;
                            </td>
                            <td>
                                <?php
                                if ($mode == '1'){
                                    $tmp_default = "";
                                }else{
                                    $tmp_default = $lastname;
                                }
                                
                                $tmp = $ary_amphor;
                                echo $this->Form->input('amphor_tab2', array(
                                    'options'=>$tmp,
                                     'label' => false,
                                     'default' => $tmp_default,
                                     'div' => false,
                                     'class' => 'txt'));
                                ?>
                                &nbsp;<span class="red">*</span>
                            </td>
                            <td align="right">
                               ตำบล :&nbsp;
                            </td>
                            <td>
                                <div id='find_tambon_tab2'>
								<?php
	                                if ($mode == '1'){
	                                    $tmp_default = "";
	                                }else{
	                                    $tmp_default = $lastname;
	                                }
	                                $tmp = $ary_tambol;
	                                echo $this->Form->input('tambol_tab2', array(
	                                    'options'=>$tmp,
	                                     'label' => false,
	                                     'default' => $tmp_default,
	                                     'div' => false,
	                                     'class' => 'txt'));
	                            ?>
								&nbsp;<span class="red">*</span>
								</div>
                            </td>
                            
                        </tr>
                    </table>
                    <!-- Finish row -->
                </td>
            </tr>
            <tr>
                <td>
                    จังหวัด <b><span id='province_detail_tab2'>อุบลราชธานี</span></b>&nbsp;&nbsp;&nbsp;
                    รหัสไปรษณีย์
                    <?php
                        if ($mode == '1'){
                            $tmp_default = "";
                        }else{
                            $tmp_default = $lastname;
                        }
                        
                        //$tmp = $ary_postal_code;
                        echo $this->Form->input('postal_code_tab2',array(
							'label' => false,
							'div' => false,
							'class' => 'txt',
							'readonly' => 'true',
							'size' => '6',
							'id' => 'postal_id_tab2'
						));
                        ?>
                        &nbsp;<span class="red">*</span>
                </td>
            </tr>
            <tr>
                <td>
                    หมายเลขโทรศัพท์ :&nbsp;
                    <?php
                        if ($mode == '1'){
                            $tmp_default = "";
                        }else{
                            $tmp_default = $lastname;
                        }
                        echo $this->Form->input('txtphone_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                 'label'=>false,'class'=>'txt','div'=>false));
                        ?>
                    หมายเลขโทรศัพท์มือถือ :&nbsp;
                    <?php
                        if ($mode == '1'){
                            $tmp_default = "";
                        }else{
                            $tmp_default = $lastname;
                        }
                        echo $this->Form->input('txtmobile_tab2',array('size'=>'20','default'=>$tmp_default,
                                                                 'label'=>false,'class'=>'txt','div'=>false));
                        ?>
                </td>
            </tr>
            <tr>
                <td>
                     <br/>
                    <b>[[ ข้อมูลทั่วไป ]]</b><br/><br/>
                    ปัจจุบันอาศัยอยู่กับ&nbsp;
                    <?php
                      echo $this->Form->input('t_live_with',array('size'=>'60','default'=>$tmp_default,
                                                                 'label'=>false,'class'=>'txt','div'=>false));
                    ?>
                </td>
            <tr>
                <td>
                    มีความสัมพันธ์เกี่ยวข้องเป็น&nbsp;
                    <?php
                      echo $this->Form->input('t_live_with_relation',array('size'=>'60','default'=>$tmp_default,
                                                                 'label'=>false,'class'=>'txt','div'=>false));
                    ?>
                </td>
            </tr>
            <tr>
                <td width="30%">
                    ฐานะครอบครัว :&nbsp;
                    <?php
                    $tmp = array();
                    $tmp["ยากจน"] = "&nbsp;ยากจน";
                    $tmp["ปานกลาง"] = "&nbsp;ปานกลาง";
                    $tmp["ดี"] = "&nbsp;ดี";
                    
                     echo $this->Form->input('economy_status', array(
                        'type'=>'radio',
                         'separator'=>'&nbsp;&nbsp;&nbsp;',
                        'before' => '&nbsp;',
                        'after' => '&nbsp;',
                        'between' => '',
                        'legend' => false,
                        'options'=>$tmp,
                         'label' => false,
                         'default' => 'ยากจน',
                         'div' => false,
                         'class' => 'txt'));?>&nbsp;<span class="red">*</span>
                </td>
            </tr>
	    <tr>
		<td>
		    รายได้&nbsp;
		    <?php
		    echo $this->Form->input('income_range_id', array(
                        'type'=>'radio',
                         'separator'=>'&nbsp;&nbsp;&nbsp;',
                        'before' => '&nbsp;',
                        'after' => '&nbsp;',
                        'between' => '',
                        'legend' => false,
                        'options'=>$ary_income_range,
                         'label' => false,
                         'default' => '1',
                         'div' => false,
                         'class' => 'txt'));?>&nbsp;<span class="red">*</span>
		</td>
	    </tr>
        <tr>
        <td>
            สุขภาพ&nbsp;
            <?php
            $arr_health = array("โปรดเลือก","ไม่เจ็บป่วย","เจ็บป่วย");
            echo $this->Form->input('health_status', array(
                        'options'=>$arr_health,
                        'label' => false,
                        'default' => '0',
                        'div' => false,
                        'class' => 'txt'));
            ?>
            &nbsp;<span class="red">*</span>
        </td>
        </tr>
        <tr>
        <td>
        	<div id="dv_health_detail">
	            อาการของโรค&nbsp;
	            <?php
	            //$arr_health = array("ไม่เจ็บป่วย","เจ็บป่วย");
	            echo $this->Form->input('health_symptoms', array(
	                        'multiple' => true,
	                        'options'=>$arr_health_detail,
	                        'label' => false,
	                        'default' => '0',
	                        'div' => false,
	                        'class' => 'txt'));
	            ?>
	            &nbsp;<span class="red">*</span>&nbsp;<span id="addmore_health_link" style="text-decoration:underline;cursor:pointer">เพิ่ม</span>
	            <span id="dv_addnew_heath" style="display:none">
	                รายละเอียด&nbsp;
	                <?php 
	                    echo $this->Form->input('new_heath_detail',array(
	                            'div' => false,
	                            'label' => false,
	                            'class' => 'txt'
	                        ));
	                    echo "&nbsp;";
	                    echo $this->Form->button('+',array(
	                            'type' => 'button',
	                            'div' => false,
	                            'label' => false,
	                            'class' => 'txt',
	                            'id' => 'bt_add_health_detail'
	                        ));
	                ?>
	            </span>
            </div>
        </td>
        </tr>
         <tr>
        <td>
            ร่างกาย&nbsp;
            <?php
            $arr_disbody = array("โปรดเลือก","ปกติ","พิการ");
            echo $this->Form->input('disbody_status', array(
                        'options'=>$arr_disbody,
                        'label' => false,
                        'default' => '0',
                        'div' => false,
                        'class' => 'txt'));
            ?>
            &nbsp;<span class="red">*</span>
        </td>
        </tr>
        <tr>
        <td>
        	<div id="dv_body_detail">
	            ความพิการ&nbsp;
	            <?php
	            //$arr_health = array("ไม่เจ็บป่วย","เจ็บป่วย");
	            echo $this->Form->input('disbody_detail', array(
	                        'multiple' => true,
	                        'options'=>$arr_disbody_detail,
	                        'label' => false,
	                        'default' => '0',
	                        'div' => false,
	                        'class' => 'txt'));
	            ?>
	            &nbsp;<span class="red">*</span>&nbsp;<span id="addmore_disbody_link" style="text-decoration:underline;cursor:pointer">เพิ่ม</span>
	            <span id="dv_addnew_disbody" style="display:none">
	                รายละเอียด&nbsp;
	                <?php 
	                    echo $this->Form->input('new_disbody_detail',array(
	                            'div' => false,
	                            'label' => false,
	                            'class' => 'txt'
	                        ));
	                    echo "&nbsp;";
	                    echo $this->Form->button('+',array(
	                            'type' => 'button',
	                            'div' => false,
	                            'label' => false,
	                            'class' => 'txt',
	                            'id' => 'bt_add_disbody_detail'
	                        ));
	                ?>
	            </span>
            </div>
        </td>
        </tr>
            <tr>
                <td>
                    <br/>
                    <?php
                    // เพื่อระบุว่าผู้ใช้ใหม่ หรือเคยมีข้อมูลใน Family Mapping อยู่แล้ว
                    echo $this->Form->input('hidden_type',array(
                    		'type' => 'hidden',
                    		'div' => false,
                    		'label' => false,
                    		'id' => 'hid_type_id',
                    		'value' => '1'
	                    	));
	                
	                // ถ้ามีข้อมูลอยู่แล้วให้นำค่า people_id มาใส่ไว้ที่นี่
	                echo $this->Form->input('hidden_people_id',array(
                    		'type' => 'hidden',
                    		'div' => false,
                    		'label' => false,
                    		'id' => 'hid_people_id'
	                    	));
	                    	
                    echo $this->Form->button('&nbsp;บันทึกข้อมูลกลุ่มเป้าหมาย&nbsp;', array('type' => 'button', 'id' => 'btnSaveOfficer_tab2'));
                    
                    ?>
                </td>
            </tr>
            <tr>
                <td>&nbsp;
                <?php
                echo $this->Form->input('officer_id',array('type'=>'hidden','value'=>$officer_id));
                echo $this->Form->input('officer_people_id',array('type' => 'hidden', 'value' => ''));                

                ?>
                </td>
            </tr>
         </table>
         <div id="tab1result"></div>
         <?php 
    		echo $this->Form->end();
    	?>
    </div>
    
    <!-- Begin Tab 2 เพิ่มข้อมูลสมาชิกในครอบครัว -->
    <div id="tab-2" class="ui-tabs-panel ui-tabs-hide">
        <b>สมาชิกในครอบครัวที่อาศัยอยู่ร่วมกันจริงในปัจจุบันมีทั้งหมด
	    <input type="text" size="3" id="txt_total_member"/>&nbsp;คน</b>
	    <br/>
	    <div id="member_0" style="display:none">
	    <?php
	    	echo $this->Form->create('Formnew',array('class'=>'testcls'));
	    ?>
		<table width="100%" cellpadding="5" cellspacing="8" border="0">
		<tr>
		    <td valign="top">
			<!-- Begin row -->
			<b>สมาชิกลำดับที่ &nbsp;<span id="member_no">xxxx</span></b><br/>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
				<td align="right">
				    คำนำหน้าชื่อ :   &nbsp;                  
				</td>
				<td>
				    <?php
				    $tmp = array();
				    $tmp["นาย"] = "นาย";
				    $tmp["นาง"] = "นาง";
				    $tmp["นางสาว"] = "นางสาว";
				    $tmp["เด็กหญิง"] = "เด็กหญิง";
				    $tmp["เด็กชาย"] = "เด็กชาย";
				    
				    if($mode == "1"){
					$tmp_default = "นาย";
				    }else{
					$tmp_default = $prefix;
				    }
				    echo $this->Form->input('prefix', array(
					'options'=>$tmp,
					 'label' => false,
					 'default' => $tmp_default,
					 'div' => false,
					 'class' => 'txt'));?> 
				</td>
				<td align="right">ชื่อ :&nbsp;</td>
				<td>
				    <?php
				    if ($mode == '1'){
					$tmp_default = "";
				    }else{
					$tmp_default = $firstname;
				    }
				    echo $this->Form->input('firstname',array('size'=>'40','default'=>$tmp_default,
									      'label'=>false, 'class' => 'txt_first_name','div' => false));
				    ?>&nbsp;<span class="red">*</span>
				</td>
				<td align="right">นามสกุล :&nbsp;</td>
				<td>
				    <?php
				    if ($mode == '1'){
					$tmp_default = "";
				    }else{
					$tmp_default = $lastname;
				    }
				    echo $this->Form->input('lastname',array('size'=>'40','default'=>$tmp_default,
									     'label'=>false,'class'=>'txt_last_name','div'=>false));
				    ?>&nbsp;<span class="red">*</span>
				</td>
			    </tr>
			</table>
			<!-- Finish row -->
		    </td>
		</tr>
		<tr>
		    <td>
			<!-- Begin row -->
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
				<td align="left" width="17%">
				    วัน / เดือน / ปี พ.ศ. เกิด&nbsp;                  
				</td>
				<td width="25%">
				    <?php
				    echo $this->Form->input('txt_birthday_cus', array('label'=>false,'div'=>false,'type'=>'text','class'=>'txt txt_calendar2','size'=>15)); ?>
				    
				    <?php
				    //echo $this->Form->input('birthday_date', array('label'=>false,'div'=>false,'type'=>'text','maxlength'=>2,'class'=>'txt','size'=>2)); ?>
				    
				    <?php
				    //echo $this->Form->input('birthday_month', array('label'=>false,'div'=>false,'type'=>'text','maxlength'=>2,'class'=>'txt','size'=>2)); ?>
				    
				    <?php
				    //echo $this->Form->input('birthday_year', array('label'=>false,'div'=>false,'type'=>'text','maxlength'=>4,'class'=>'txt','size'=>4)); ?>
				    &nbsp;<span class="red">*</span>
				</td>
				<td align="right" width="10%">การศึกษา :&nbsp;</td>
				<td width="30%">
				    <?php				    
				     echo $this->Form->input('f_highest_ed_level', array(
					'options'=>$ary_education_level,
					 'label' => false,
					 'default' => "",
					 'div' => false,
					 'class' => 'txt'));
					?>&nbsp;<span class="red">*</span>
				</td>
				<td align="right" width="40%">&nbsp;</td>
				<td>&nbsp;
				</td>
			    </tr>
			</table>
		    </td>
		</tr>
		<tr>
		    <td>
			<table width="100%" cellpadding="0" cellspacing="5" border="0">
			    <tr>
				<td align="left" width="7%">
				    อาชีพ :&nbsp;               
				</td>
				<td width="13%">
				    <?php				    
				     echo $this->Form->input('f_occupation', array(
					'options'=>$ary_occupation,
					 'label' => false,
					 'default' => "",
					 'div' => false,
					 'class' => 'txt'));
					?>&nbsp;<span class="red">*</span>
				</td>
				<td align="right" width="7%">รายได้ :&nbsp;</td>
				<td>
				    <?php
					echo $this->Form->input('f_income_range_id', array(
					    'type'=>'radio',
					     'separator'=>'&nbsp;&nbsp;&nbsp;',
					    'before' => '&nbsp;',
					    'after' => '&nbsp;',
					    'between' => '',
					    'legend' => false,
					    'options'=>$ary_income_range,
					     'label' => false,
					     'default' => '1',
					     'div' => false,
					     'class' => 'txt'));?>&nbsp;<span class="red">*</span>
				</td>
			    </tr>
			</table>
			<!-- Finish row -->
		    </td>
		</tr>
		<tr>
		    <td>
			<table width="100%" cellpadding="0" cellspacing="5" border="0">
			    <tr>
				<td align="left" width="13%">
				    สุขภาพร่างกาย :&nbsp;               
				</td>
				<td width="20%">
				    <?php
				    $ary_health_type = array();
				    $ary_health_type["0"] = "สุขภาพแข็งแรงปกติ";
				    $ary_health_type["1"] = "เจ็บป่วย / โรคประจำตัว" ;
				    $ary_health_type["2"] = "พิการ";
				    
				     echo $this->Form->input('f_heath_type', array(
					'options'=>$ary_health_type,
					 'label' => false,
					 'default' => "",
					 'div' => false,
					 'class' => 'txt'));
					?>&nbsp;<span class="red">*</span>
				</td>
				<td align="right" width="10%">รายละเอียด :&nbsp;</td>
				<td>
				    <?php
					echo $this->Form->input('f_heath_details', array(
					    'type'=>'text',
					    'size' => '40',
					    'label' => false,
					    'div' => false
					));
					?>&nbsp;
				</td>
			    </tr>
			</table>
			<!-- Finish row -->
		    </td>
		</tr>
		<tr>
		    <td>&nbsp;</td>
		</tr>
		</table>
		<!-- Finish row -->
		<?php
			echo $this->Form->end();
		?>
	    </div>
	    <div id="tab2_content">
	    </div>
	    <div id="tab2_result">
	    </div>
	    <?php
	    	echo $this->Form->button('&nbsp;บันทึกข้อมูล&nbsp;', array('type' => 'button', 'id' => 'btnSave_tab3','style'=>'display:none'));
	    	echo $this->Form->input('target_id',array('type'=>'hidden','value'=>'19'));            
	    ?>
	    <br><br>
    </div>


</div>
<center><?php //echo $this->Form->end('บันทึกทั้งหมด'); ?></center>
</div>


<?php       