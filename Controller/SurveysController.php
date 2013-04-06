<?php
class SurveysController extends AppController {
    public $helpers = array('Html', 'Form');
    public $uses = array('Amphor','Tambol','Province','Income_range','Education','Occupation','Postal','Address','People','Officer','Target','Family','Health','Health_mapping');
    
    
    //#Function to calculate age
    //#Create by Saranya K. on Feb 27, 2013
    public function calculate_age($birthday = null){
        //$birthday => thai birthday
        //echo($birthday . "<br/>");
        $tmp = explode("-",$birthday);
        //#get year
        $year = intval($tmp[0]) - 543;
        //echo($year . "<br/>");
        
        $eng_birthday = $tmp[1] . "/" . $tmp[2] . "/" . $year;
        $tmp = explode("/",$eng_birthday);
        
        $age = (date("md", date("U", mktime(0,0,0, $tmp[0],$tmp[1], $tmp[2]))) > date("md") ? ((date("Y") - $tmp[2]) - 1): (date("Y") - $tmp[2]));
        return $age;
    }
    
    
    
    public function showofficer(){
        $this->layout = 'main';
        
        //print_r($this->data);
        
        if(!empty($this->data)){
            
            
            if(isset($this->params['data']['showbtn'])){
                if($this->params['data']['showbtn'] == 'show') {
                    $this->redirect( array('controller' => 'surveys','action' => 'show'));
                }
            }
            
            if(isset($this->params['data']['addnewbtn'])){
                if($this->params['data']['addnewbtn'] == 'add') {
                    $this->redirect( array('controller' => 'surveys','action' => 'add',0));
                }
            }
            
        }
        
        
        $ary_option_join_people = array();
        $ary_option_join_people[] = 'People.people_id= Officer.people_id';
        
        $options = array();
        
        $options["order"] = array('Officer.officer_id desc');
        
        $ary_joins = array(
            array(
                'table' => 'tbl_peoples',
                'alias' => 'People',
                'type' => 'inner',
                'conditions' => $ary_option_join_people            
            )
        );
        $options['joins'] = $ary_joins;
        $options['fields'] = array('Officer.*','People.*');
        
        
        $tmp = $this->Officer->find('all',$options);
        $result = array();
        if(!empty($tmp)){
            $i = 0;
            foreach($tmp as $t):

                $ary = array();
                $ary["Officer"]["officer_id"] = $t["Officer"]["officer_id"];
                
                if($t["Officer"]["officer_type"] == "0"){
                    $ary["Officer"]["officer_type"] = "หมู่บ้าน";
                }elseif ($t["Officer"]["officer_type"] == "1"){
                    $ary["Officer"]["officer_type"] = "ตำบล";;
                }else{
                    $ary["Officer"]["officer_type"] = "อำเภอ";
                }
                
                $ary["Officer"]["people_id"] = $t["Officer"]["people_id"];
                
                $ary["People"]["prefix"] = $t["People"]["prefix"];
                $ary["People"]["name"] = $t["People"]["name"];
                $ary["People"]["last_name"] = $t["People"]["last_name"];
                
                //#Address
                if(!empty($t["People"]["address_id"])){
                    $ad_options = array();
                    $t2 = array();
                    $t2[] = "Address.address_id = " . $t["People"]["address_id"];
                    $ad_options["conditions"] = $t2;
                    $tmpAddress = $this->Address->find('first',$ad_options);
                    if(!empty($tmpAddress)){
                        //#Find detail of [tambol_id, amphor_id, province_id]
                        $tambol_id = $tmpAddress["Address"]["tambol_id"];
                        $amphor_id = $tmpAddress["Address"]["amphor_id"];
                        $province_id = $tmpAddress["Address"]["province_id"];
                        
                        //###Tambol
                        if(empty($tambold_id) || ($tambol_id == "0")){
                            $ary["People"]["tambol"] = "-";
                        }else{
                            $tmpTambol = $this->Tambol->find('first',array(
                                "conditions" => array("Tambol.tambol_id = " . $tambol_id)
                                )
                            );
                            if(!empty($tmpTambol)){
                                $ary["People"]["tambol"] = $tmpTambol["Tambol"]["description"];
                            }else{
                                $ary["People"]["tambol"] = "-";
                            }
                        }
                        
                        //##Province
                        if(empty($amphor_id) || ($amphor_id == "0")){
                            $ary["People"]["amphor"] = "-";
                        }else{
                            $tmpAmphor = $this->Amphor->find('first',array(
                                "conditions" => array("Amphor.amphor_id = " . $amphor_id)
                                )
                            );
                            if(!empty($tmpAmphor)){
                                $ary["People"]["amphor"] = $tmpAmphor["Amphor"]["description"];
                            }else{
                                $ary["People"]["amphor"] = "-";
                            }
                        }
                        
                        //##Province
                        if(empty($province_id) || ($province_id == "0")){
                            $ary["People"]["province"] = "-";
                        }else{
                            $tmpProvince = $this->Province->find('first',array(
                                "conditions" => array("Province.province_id = " . $province_id)
                                )
                            );
                            if(!empty($tmpProvince)){
                                $ary["People"]["province"] = $tmpProvince["Province"]["description"];
                            }else{
                                $ary["People"]["province"] = "-";
                            }
                        }
                       $ary["People"]["kkk"] = "ss"; 
                    }
                    
                }

                $result[$i] = $ary;
                 $i = $i + 1;
            endforeach;
        }
        
        $this->set('result',$result);
        //print_r($result);
        
    }
    
    
    //#List all records to show/search
    public function show(){
        $this->layout = 'main';
        
        //print_r($this->params);
        if(!empty($this->data['showbtn'])){
            $this->data = null;
        }
 
        if(!empty($this->data)){
            if(isset($this->params['data']['refreshbtn'])){
                if($this->params['data']['refreshbtn'] == 'refresh') {
                    $this->redirect( array('controller' => 'surveys','action' => 'show'));
                }
            }
            if(isset($this->params['data']['addnewbtn'])){
                if($this->params['data']['addnewbtn'] == 'add') {
                    $this->redirect( array('controller' => 'surveys','action' => 'add'));
                }
            }
            
            if(isset($this->params['data']['showofficerbtn'])){
                if($this->params['data']['showofficerbtn'] == 'show') {
                    $this->redirect( array('controller' => 'surveys','action' => 'showofficer'));
                }
            }
            
        }
        
        
        $ary_option_join_people = array();
        $ary_option_join_people[] = 'Target.people_id= TargetPeople.people_id';
        //#Data from search
        //print_r($this->data);
        if(!empty($this->data)){
            //print_r($this->data);
            $search_type = $this->data['Survey']['search_type'];
            $keyword = $this->data['Survey']['keyword'];
            if(!empty($keyword)){
                if($search_type == "1"){
                    $ary_option_join_people[] = array('TargetPeople.name like ' => '%' . $keyword . '%');
                }elseif($search_type == "2"){
                    $ary_option_join_people[] = array('TargetPeople.last_name like ' => '%' . $keyword . '%');
                }
            }
        }else{
            $search_type = '';
            $keyword = '';
        }
        $this->set('search_type',$search_type);
        $this->set('keyword',$keyword);
        //################################
    
        //#Option for join table
        $options = array();
        
        $options["order"] = array('Target.tid desc');
        
        
        
        
        $ary_joins = array(
            array(
                'table' => 'tbl_peoples',
                'alias' => 'TargetPeople',
                'type' => 'inner',
                'conditions' => $ary_option_join_people            
            )
        );
        $options['joins'] = $ary_joins;
        $options['fields'] = array('Target.*','TargetPeople.*');
        
        $ary_conditions = array();
        if($search_type == "1" || $search_type == "2"){
            $ary_conditions[] = "Target.people_id is not null ";
        }elseif($search_type == "4" || $search_type == "5"){
            $ary_conditions[] = "Target.officer_id is not null ";
        }

        $options["conditions"] = $ary_conditions;
        
        //echo("<br/>");
        
        $tmp = $this->Target->find('all',$options);
        $result = array();
        if(!empty($tmp)){
            $i = 0;
            $not_added = 0;
            foreach($tmp as $t):
                $ary = array();
                $ary["Target"]["tid"] = $t["Target"]["tid"];
                $ary["Target"]["officer_id"] = $t["Target"]["officer_id"];
                $ary["Target"]["people_id"] = $t["Target"]["people_id"];
                
                $ary["TargetPeople"]["prefix"] = $t["TargetPeople"]["prefix"];
                $ary["TargetPeople"]["name"] = $t["TargetPeople"]["name"];
                $ary["TargetPeople"]["last_name"] = $t["TargetPeople"]["last_name"];

                if(!empty($t["TargetPeople"]["birthday"])){
                    if($t["TargetPeople"]["birthday"] != "0000-00-00"){
                        $age = $this->calculate_age($t["TargetPeople"]["birthday"]);
                        $tmp = explode("-",$t["TargetPeople"]["birthday"]);
                        $ary["TargetPeople"]["birthday"] = $tmp[2] . "/" . $tmp[1] . "/" . $tmp[0];
                    }else{
                        $age = "";
                    }
                }else{
                    $age = "";
                }
                $ary["TargetPeople"]["age"] = $age;
                /*
                 *< 10<br/>
                    > 20<br/>
                    10 < X < 20
                */
                if($search_type == "3" && $keyword != ""){
                    $tmp_more = explode(">",$keyword);
                    $total_more = substr_count($keyword, ">");
                    
                    $tmp_less = explode("<",$keyword);
                    $total_less = substr_count($keyword, "<");
                    $total_x = substr_count(strtolower($keyword),"x");
                    
                    if(($total_x == 1 && $total_less == 2) || ($total_more <= 1 && $total_less <= 1)){
                        if($total_less == 2){
                            if(intval($age) > intval(trim($tmp_less[0])) && intval($age) < intval(trim($tmp_less[2]))){
                                $not_added = 0;
                            }else{
                                $not_added = 1;
                            }
                        }else{
                           if($total_more == 1){
                                if(intval($age) > $tmp_more[1]){
                                    $not_added = 0;
                                }else{
                                    $not_added = 1;
                                }
                           }else{
                                if(intval($age) < $tmp_less[1]){
                                    $not_added = 0;
                                }else{
                                    $not_added = 1;
                                }
                           }
                        }
                    }
                    
                }
                
                
                //##filter for [name, last_name] of officer
                $ary_option_condition_officer = array();
                $ary_option_condition_officer[] ="Officer.officer_id = ". $t["Target"]["officer_id"];
                if($search_type == "4"){
                    $ary_option_condition_officer[] = array('People.name like ' => '%' . $keyword . '%');
                    $ary_option_condition_officer[] = array('People.name is not null');
                }elseif($search_type == "5"){
                    $ary_option_condition_officer[] = array('People.last_name like ' => '%' . $keyword . '%');
                    $ary_option_condition_officer[] = array('People.last_name is not null');
                }
                
                //print_r($ary_option_join_officer);
                //echo("<br/><br/>");
                //#Find details of officer
                $tmpofficer = $this->Officer->find("first",array(
                   "conditions" => $ary_option_condition_officer,
                   "fields" => array(
                        "People.name",
                        "People.last_name"
                   ),
                   "joins" => array(
                        array(
                            'table' => "tbl_peoples",
                            'alias' => "People",
                            'type' => "left",
                            'conditions' => "Officer.people_id = People.people_id"
                        )
                   )
                ));
                //print_r($tmpofficer);
                //echo("<br/><br/>");
                if(!empty($tmpofficer)){
                    $ary["Officer"]["name"] = $tmpofficer["People"]["name"];
                    $ary["Officer"]["last_name"] = $tmpofficer["People"]["last_name"];
                }else{
                    $ary["Officer"]["name"] = "";
                    $ary["Officer"]["last_name"] = "";
                    if($search_type == "4" || $search_type == "5"){
                        $not_added = 1;
                    }
                }
            
                //#Filter in some case that cannot where in SQL statement
                if($not_added != 1){
                    $result[$i] = $ary;
                }else{
                    $not_added = 0;
                }
                
                $i = $i + 1;
            endforeach;
        }
        
        
        
        $this->set("result",$result);
        //print_r($tmp);
        
    }
    
    

    
    //##show from view page
    //##$tab_no will support for already added in tab1 [sent with $officer_id]
    //##$tab_no => 0, it means add only officer
    
    // Main Add New data ############################################################
    public function add($tab_no = null, $officer_id = null) {
    	//$this->autoRender = false;
        $this->layout = 'main';
        $mode = "1";
        
        if(!empty($this->data)){
            if(isset($this->params['data']['showbtn'])){
                if($this->params['data']['showbtn'] == 'show') {
                    $this->redirect( array('controller' => 'surveys','action' => 'showofficer'));
                }
            }
        }
        
        //#In case of have $officer_id it will show data instead of add in the first tab
        if(!empty($tab_no)){
            $tab_no = intval($tab_no)-1;
        }else{
            if($tab_no != "0"){
                $tab_no = "1";
            }
        }
        

        
        $this->set("tab_no",$tab_no);
        $this->set("officer_id", $officer_id);
       
        if(empty($this->data)){
            
            //#Load amphor
            $tmp_amphor = $this->Amphor->find('all',array(
            	"fields" => array("Amphor.amphor_id, Amphor.description"),
            	"conditions" => array("Amphor.province_id"=>"1"),
            	"order" => array("Amphor.description")
            	));
            //print_r($tmp_amphor);
        	$ary_amphor = array();
            $ary_amphor[0] = "โปรดเลือก";
            foreach($tmp_amphor as $amphor):
                $ary_amphor[$amphor["Amphor"]["amphor_id"]] = $amphor["Amphor"]["description"];
            endforeach;
            //print_r($ary_amphor);
            $this->set('ary_amphor',$ary_amphor);
            
            //#Find tambol
            if($mode == "1"){
                $ary_tambol = array();
                $ary_tambol[0] = "โปรดเลือกอำเภอ" ;
                
                $ary_postal_code = array();
                $ary_postal_code[0] = "โปรดเลือกอำเภอ" ;
            }
            $this->set("ary_tambol",$ary_tambol);
            $this->set("ary_postal_code",$ary_postal_code);
            
            //#Find income_range
            $tmp = $this->Income_range->find('all',array(
            	"fields" => array("Income_range.range_id","Income_range.description"),
            	"order" => array("Income_range.range_id")
            	));
           	//print_r($tmp);
            $ary_income_range = array();
            foreach($tmp as $income_range):
                $ary_income_range[$income_range["Income_range"]["range_id"]] = "&nbsp;&nbsp;" . $income_range["Income_range"]["description"];
            endforeach;
            //print_r($ary_income_range);
            $this->set('ary_income_range',$ary_income_range);
            
            //#Find education level
            $tmp = $this->Education->find('all',array(
            	"fields" => array("Education.level_id","Education.description"),
            	"order" => "orderby"
            	));
            	
            $ary_education_level = array();
            $ary_education_level[0] = "กรุณาเลือก";
            foreach($tmp as $education_level):
                $ary_education_level[$education_level["Education"]["level_id"]] = $education_level["Education"]["description"];
            endforeach;
            //print_r($ary_education_level);
            $this->set('ary_education_level',$ary_education_level);
            
            //#Find occupation
            $tmp = $this->Occupation->find('all',array(
            	"fields" => array("Occupation.occupation_id","Occupation.description"),
            	"order" => "description"
            	));
            
        	$ary_occupation = array();
            $ary_occupation[0] = "กรุณาเลือก";
            foreach($tmp as $occupation):
                $ary_occupation[$occupation["Occupation"]["occupation_id"]] = $occupation["Occupation"]["description"];
            endforeach;
            //print_r($ary_occupation);
            $this->set('ary_occupation',$ary_occupation);

            // Find detail of Health ###############3
             $tmp = $this->Health->find('all',array(
                "fields" => array("Health.hstatus_id","Health.details"),
                "conditions" => array("Health.type" => "1"),
                "order" => "Health.details"
                ));
            
            //print_r($tmp);
            $arr_health_detail = array();
            //$arr_health_detail[0] = "กรุณาเลือก";
            foreach($tmp as $health):
                $arr_health_detail[$health["Health"]["hstatus_id"]] = $health["Health"]["details"];
            endforeach;
            
            $this->set('arr_health_detail',$arr_health_detail);
        }
         
         
         $this->set('title_for_layout', 'Test');
         $this->set('mode','1');
         $this->set('officer_type','');
         $this->set('prefix','');
         $this->set('firstname','');
         $this->set('lastname','');
        //$this->set('posts', $this->Post->find('all'));
    }
    
    public function add_tab2($tab_no = null, $officer_id = null) {
    	//$this->autoRender = false;
        $this->layout = 'main';
        $mode = "1";
        
        if(!empty($this->data)){
            if(isset($this->params['data']['showbtn'])){
                if($this->params['data']['showbtn'] == 'show') {
                    $this->redirect( array('controller' => 'surveys','action' => 'showofficer'));
                }
            }
        }
        
        //#In case of have $officer_id it will show data instead of add in the first tab
        if(!empty($tab_no)){
            $tab_no = intval($tab_no)-1;
        }else{
            if($tab_no != "0"){
                $tab_no = "1";
            }
        }
        
        $this->set("tab_no",$tab_no);
        $this->set("officer_id", $officer_id);
       
        if(empty($this->data)){
            
            //#Load amphor
            $tmp_amphor = $this->Amphor->find('all',array(
            	"fields" => array("Amphor.amphor_id, Amphor.description"),
            	"conditions" => array("Amphor.province_id"=>"1"),
            	"order" => array("Amphor.description")
            	));
            //print_r($tmp_amphor);
        	$ary_amphor = array();
            $ary_amphor[0] = "โปรดเลือก";
            foreach($tmp_amphor as $amphor):
                $ary_amphor[$amphor["Amphor"]["amphor_id"]] = $amphor["Amphor"]["description"];
            endforeach;
            //print_r($ary_amphor);
            $this->set('ary_amphor',$ary_amphor);
            
            //#Find tambol
            if($mode == "1"){
                $ary_tambol = array();
                $ary_tambol[0] = "โปรดเลือกอำเภอ" ;
                
                $ary_postal_code = array();
                $ary_postal_code[0] = "โปรดเลือกอำเภอ" ;
            }
            $this->set("ary_tambol",$ary_tambol);
            $this->set("ary_postal_code",$ary_postal_code);
            
            //#Find income_range
            $tmp = $this->Income_range->find('all',array(
            	"fields" => array("Income_range.range_id","Income_range.description"),
            	"order" => array("Income_range.range_id")
            	));
           	//print_r($tmp);
            $ary_income_range = array();
            foreach($tmp as $income_range):
                $ary_income_range[$income_range["Income_range"]["range_id"]] = "&nbsp;&nbsp;" . $income_range["Income_range"]["description"];
            endforeach;
            //print_r($ary_income_range);
            $this->set('ary_income_range',$ary_income_range);
            
            //#Find education level
            $tmp = $this->Education->find('all',array(
            	"fields" => array("Education.level_id","Education.description"),
            	"order" => "orderby"
            	));
            	
            $ary_education_level = array();
            $ary_education_level[0] = "กรุณาเลือก";
            foreach($tmp as $education_level):
                $ary_education_level[$education_level["Education"]["level_id"]] = $education_level["Education"]["description"];
            endforeach;
            //print_r($ary_education_level);
            $this->set('ary_education_level',$ary_education_level);
            
            //#Find occupation
            $tmp = $this->Occupation->find('all',array(
            	"fields" => array("Occupation.occupation_id","Occupation.description"),
            	"order" => "description"
            	));
            
        	$ary_occupation = array();
            $ary_occupation[0] = "กรุณาเลือก";
            foreach($tmp as $occupation):
                $ary_occupation[$occupation["Occupation"]["occupation_id"]] = $occupation["Occupation"]["description"];
            endforeach;
            //print_r($ary_occupation);
            $this->set('ary_occupation',$ary_occupation);

            // Find detail of Health (สุขภาพ) ###############3
             $tmp = $this->Health->find('all',array(
                "fields" => array("Health.hstatus_id","Health.details"),
                "conditions" => array("Health.type" => "1"),
                "order" => "Health.hstatus_id"
                ));
            
            //print_r($tmp);
            $arr_health_detail = array();
            //$arr_health_detail[0] = "กรุณาเลือก";
            foreach($tmp as $health):
                $arr_health_detail[$health["Health"]["hstatus_id"]] = $health["Health"]["details"];
            endforeach;
            
            $this->set('arr_health_detail',$arr_health_detail);
            
             // Find detail of body fail (พิการ) ############### 
             $tmp = $this->Health->find('all',array(
                "fields" => array("Health.hstatus_id","Health.details"),
                "conditions" => array("Health.type" => "2"),
                "order" => "Health.hstatus_id"
                ));
            
            //print_r($tmp);
            $arr_disbody_detail = array();
            //$arr_health_detail[0] = "กรุณาเลือก";
            foreach($tmp as $disbody):
                $arr_disbody_detail[$disbody["Health"]["hstatus_id"]] = $disbody["Health"]["details"];
            endforeach;
            
            $this->set('arr_disbody_detail',$arr_disbody_detail);
        }
         
         
         $this->set('title_for_layout', 'Test');
         $this->set('mode','1');
         $this->set('officer_type','');
         $this->set('prefix','');
         $this->set('firstname','');
         $this->set('lastname','');
        //$this->set('posts', $this->Post->find('all'));
    }
    
    //#Add by Saranya K. on Feb 27, 2013 to get people_id from given id from other table.
    public function get_people_id($option = null, $tmpid = null){
        $people_id = null;
        
        if ($option == "1"){
            //#$Get [people_id] from officer_id in table tbl_officer
            //#$tmpid => officer_id
            
            if(!empty($tmpid)){
                $options = array();
                
                $options["conditions"] = array("Officer.officer_id = " . $tmpid);
                $options["fields"] = array("People.people_id");
                $options["joins"] = array(
                    array(
                        'table' => 'tbl_peoples',
                        'alias' => 'People',
                        'type' => 'left',
                        'conditions' => array(
                            'Officer.people_id = People.people_id'
                        )
                    )
                );
                
                $tmp = $this->Officer->find("first",$options);
                if(!empty($tmp)){
                    $people_id = $tmp["People"]["people_id"];
                }
                return $people_id;
            }
        }
    }
    
    
    public function edit($type="", $option = "", $tmpid="",$oid="") {
    	
    	// URL for tab2
    	// http://localhost/cake/surveys/edit/1/2#tab-1
    	
    	// URL for tab2
    	// http://localhost/cake/surveys/edit/2/18/1#tab-2
        
        // URL for tab3
        // http://localhost/cake/surveys/edit/3/22/5#tab-3
        
        //#Modified by Saranya K. on Feb 27, 2013
        //#$option => 1 $tmpid will be $people_id
        //#$option => 2 $tmpid will be $officer_id
        
        //#if $type => 0 it means edit only officer
        
        if($option == "2"){
            $pid = $this->get_people_id("1",$tmpid);
        }else{
            $pid = $tmpid;
        }
    	
        //#Convert [number of tab] to support jquery tab ui
        //#Add by Saranya K. on Feb 27, 2013
        if($type == "1" || $type == "2" || $type== "3"){
            $tab_no = intval($type)-1;
            //echo($tab_no);
            $this->set('tab_no',$tab_no);
        }else if($type == "0"){
            $tab_no = "0";
            $this->set('tab_no',$tab_no);
        }
        
        $header_title = "แก้ไขข้อมูล";
        
    	//$this->autoRender = false;
        $this->layout = 'main';
        $mode = "1";
        
        if(empty($this->data)){
            
        	//#Load amphor
            $tmp_amphor = $this->Amphor->find('all',array(
            	"fields" => array("Amphor.amphor_id, Amphor.description"),
            	"conditions" => array("Amphor.province_id"=>"1"),
            	"order" => array("Amphor.description")
            	));
            //print_r($tmp_amphor);
            $ary_amphor = array();
            $ary_amphor[0] = "โปรดเลือก";
            foreach($tmp_amphor as $amphor):
                $ary_amphor[$amphor["Amphor"]["amphor_id"]] = $amphor["Amphor"]["description"];
            endforeach;
            //print_r($ary_amphor);
            $this->set('ary_amphor',$ary_amphor);
            
            //#Find tambol
            if($mode == "1"){
                $ary_tambol = array();
                $ary_tambol[0] = "โปรดเลือกอำเภอ" ;
                
                $ary_postal_code = array();
                $ary_postal_code[0] = "โปรดเลือกอำเภอ" ;
            }
            
            $this->set("ary_postal_code",$ary_postal_code);
            
            //#Find income_range
            $tmp = $this->Income_range->find('all',array(
            	"fields" => array("Income_range.range_id","Income_range.description"),
            	"order" => array("Income_range.range_id")
            	));
           	//print_r($tmp);
            $ary_income_range = array();
            foreach($tmp as $income_range):
                $ary_income_range[$income_range["Income_range"]["range_id"]] = "&nbsp;&nbsp;" . $income_range["Income_range"]["description"];
            endforeach;
            //print_r($ary_income_range);
            $this->set('ary_income_range',$ary_income_range);
            
            //#Find education level
            $tmp = $this->Education->find('all',array(
            	"fields" => array("Education.level_id","Education.description"),
            	"order" => "orderby"
            	));
            	
            $ary_education_level = array();
            $ary_education_level[0] = "กรุณาเลือก";
            foreach($tmp as $education_level):
                $ary_education_level[$education_level["Education"]["level_id"]] = $education_level["Education"]["description"];
            endforeach;
            //print_r($ary_education_level);
            $this->set('ary_education_level',$ary_education_level);
            
            //#Find occupation
            $tmp = $this->Occupation->find('all',array(
            	"fields" => array("Occupation.occupation_id","Occupation.description"),
            	"order" => "description"
            	));
            
            $ary_occupation = array();
            $ary_occupation[0] = "กรุณาเลือก";
            foreach($tmp as $occupation):
                $ary_occupation[$occupation["Occupation"]["occupation_id"]] = $occupation["Occupation"]["description"];
            endforeach;
            //print_r($ary_occupation);
            $this->set('ary_occupation',$ary_occupation);
        }
         
         
         $this->set('title_for_layout', 'Test');
         $this->set('mode','1');
         $this->set('officer_type','');
         $this->set('prefix','');
         $this->set('firstname','');
         $this->set('lastname','');
     	
         //echo $type;
         
         // Prepare data for tab 1
         if ($type == "1" || $type == "0") { 
         	$header_title = "แก้ไขข้อมูลผู้สำรวจ";
     		$people_arr = $this->People->read(null,$pid);
     		//print_r($people_arr);
     		// Get people data ****************************
     		foreach ($people_arr as $key => $value) {
     			$data['Formjon1']['prefix'] = $value['prefix'];
        		$data['Formjon1']['firstname'] = $value['name'];
        		$data['Formjon1']['lastname'] = $value['last_name'];
     			$aid = $value['address_id'];
     		}
     		// find officer type id ***********************
     		$officer_arr = $this->Officer->find('all',array(
	     				"conditions" => array("Officer.people_id"=>$pid)
	     			));
	     	foreach ($officer_arr as $key => $value) {
	     		$data['Formjon1']['officer_type'] = $value['Officer']['officer_type'];
	     		$oid = $value['Officer']['officer_id'];
	     	}
	     	// find Address *******************************
	     	$address_arr = $this->Address->read(null,$aid);
	     	foreach ($address_arr as $key => $value) {
	     		$data['Formjon1']['txtbdname'] = $value['building'];
	     		$data['Formjon1']['txtfloor'] = $value['level'];
		        $data['Formjon1']['txthousegroup'] = $value['moo'];
		        $data['Formjon1']['txthousename'] = $value['mooban'];
		        $data['Formjon1']['txtsubstreet'] = $value['soi'];
		        $data['Formjon1']['txtstreet'] = $value['road'];
		        $data['Formjon1']['txtprovince'] = $value['province_id'];
		        $data['Formjon1']['tambol'] = $value['tambol_id'];
		        $data['Formjon1']['amphor'] = $value['amphor_id'];
		        
		        $tmp_tambol = $this->Tambol->find('all',array(
            		"fields" => array("Tambol.tambol_id","Tambol.description"),
            		"conditions" => array("Tambol.amphor_id"=>$value['amphor_id']),
            		"order" => "description"
            		));
                foreach($tmp_tambol as $tambol):
                    $ary_tambol[$tambol["Tambol"]["tambol_id"]] = $tambol["Tambol"]["description"];
                endforeach;
		        
		        $data['Formjon1']['postal_code'] = $value['postal_code'];
		        $data['Formjon1']['txttel'] = $value['home_tel'];
		        $data['Formjon1']['txtmobile'] = $value['mobile_tel'];
	     	}

		$data['Formjon1']['people_id'] = $pid;
		$data['Formjon1']['address_id'] = $aid;
		$data['Formjon1']['officer_id'] = $oid;
     		$this->data = $data;
     		//print_r($this->data);

         }
         
         // Prepare data for tab 1
         if ($type == "2") { 
         	$header_title = "แก้ไขข้อมูลกลุ่มเป้าหมาย";
     		$people_arr = $this->People->read(null,$pid);
     		//print_r($people_arr);
     		// Get people data ****************************
     		foreach ($people_arr as $key => $value) {
     			$data['Formjon2']['iddigit_1'] = substr($value['id_number'],0,1);
     			$data['Formjon2']['iddigit_2'] = substr($value['id_number'],1,1);
     			$data['Formjon2']['iddigit_3'] = substr($value['id_number'],2,1);
     			$data['Formjon2']['iddigit_4'] = substr($value['id_number'],3,1);
     			$data['Formjon2']['iddigit_5'] = substr($value['id_number'],4,1);
     			$data['Formjon2']['iddigit_6'] = substr($value['id_number'],5,1);
     			$data['Formjon2']['iddigit_7'] = substr($value['id_number'],6,1);
     			$data['Formjon2']['iddigit_8'] = substr($value['id_number'],7,1);
     			$data['Formjon2']['iddigit_9'] = substr($value['id_number'],8,1);
     			$data['Formjon2']['iddigit_10'] = substr($value['id_number'],9,1);
     			$data['Formjon2']['iddigit_11'] = substr($value['id_number'],10,1);
     			$data['Formjon2']['iddigit_12'] = substr($value['id_number'],11,1);
     			$data['Formjon2']['iddigit_13'] = substr($value['id_number'],12,1);
     			
     			$data['Formjon2']['prefix'] = $value['id_number'];
     			$data['Formjon2']['prefix'] = $value['prefix'];
        		$data['Formjon2']['firstname'] = $value['name'];
        		$data['Formjon2']['lastname'] = $value['last_name'];
        		$data['Formjon2']['txt_birthday_cus'] = $value['birthday'];
        		$data['Formjon2']['marital_status'] = $value['marital_status'];
        		
     			$aid = $value['address_id'];
     			
     		}
	     	// find Address *******************************
	     	$address_arr = $this->Address->read(null,$aid);
	     	foreach ($address_arr as $key => $value) {
	     		
	     		$data['Formjon2']['txtbdname_tab2'] = $value['building'];
    			$data['Formjon2']['txtfloor_tab2'] = $value['level'];
		    	$data['Formjon2']['housegroup_tab2'] = $value['moo'];
    			$data['Formjon2']['housename_tab2'] = $value['mooban'];
    			$data['Formjon2']['txtsoi_tab2'] = $value['soi'];
    			$data['Formjon2']['txtstreet_tab2'] = $value['road'];
    			$data['Formjon2']['amphor_tab2'] = $value['amphor_id'];
    			$data['Formjon2']['tambol_id'] = $value['tambol_id'];
    			
    			$tmp_tambol = $this->Tambol->find('all',array(
                            "fields" => array("Tambol.tambol_id","Tambol.description"),
                            "conditions" => array("Tambol.amphor_id"=>$value['amphor_id']),
                            "order" => "description"
            		));
                    foreach($tmp_tambol as $tambol):
                        $ary_tambol[$tambol["Tambol"]["tambol_id"]] = $tambol["Tambol"]["description"];
                    endforeach;
    			
    			$data['Formjon2']['txtprovince'] = $value['province_id'];
    			$data['Formjon2']['postal_code_tab2'] = $value['postal_code'];
    			$data['Formjon2']['txtphone_tab2'] = $value['home_tel'];
    			$data['Formjon2']['txtmobile_tab2'] = $value['mobile_tel'];
    			
    			$tambol_name = $this->Tambol->read(NULL,$value['tambol_id']);
	     	}
	     	
	     	// Find Target detail by Office ID and People ID ***********************
     		$target_det_arr = $this->Target->find('all',array(
	     				"conditions" => array("Target.officer_id"=>$oid,"Target.people_id"=>$pid)
	     			));
	     	//print_r($target_det_arr);
	     	foreach ($target_det_arr as $key => $value) {
	     		$data['Formjon2']['t_live_with'] = $value['Target']['live_with'];
    			$data['Formjon2']['t_live_with_relation'] = $value['Target']['live_with_relation'];
    			$data['Formjon2']['economy_status'] = $value['Target']['eco_status'];
    			$data['Formjon2']['income_range_id'] = $value['Target']['income_range_id'];
    			$data['Formjon2']['target_id'] = $value['Target']['tid'];
	     	}
	     	
			
			$data['Formjon2']['people_id'] = $pid;
			$data['Formjon2']['address_id'] = $aid;
			$data['Formjon2']['officer_id'] = $oid;
			
     		$this->data = $data;
     		//print_r($this->data);
     		//$ary_tambol[1] = $value['tambol_id'];
			
         }
         
         if ($type == "3") {
                $header_title = "แก้ไขข้อมูลบุคคลในครอบครัว";;
                
                //echo $pid;
                
               $target_id_arr = $this->Target->find('all',array(
                 "fields" => array("Target.tid"),
                 "conditions" => array("Target.officer_id"=>$oid,"Target.people_id"=>$pid)
                 ));
               
                //print_r($target_id_arr);
            
               $tid = $target_id_arr[0]['Target']['tid'];
                $count_member = $this->Family->find('count',array(
                  "conditions" => array("Family.tid"=>$tid)
                  ));
                  
               $this->set("count_member",$count_member);
               $people_arr = $this->Family->find('all',array(
                 "fields" => array("Family.people_id,Family.highest_ed_level,Family.fm_id"),
                 "conditions" => array("Family.tid"=>$tid)
                 ));
            //print_r($people_arr);
            // Insert into table people if not exists in this table
           
            for ($i=1; $i <= $count_member ; $i++) { 
             
             $people_det = $this->People->find('all',array(
               "conditions" => array("People.people_id"=>$people_arr[$i-1]['Family']['people_id'])
               ));
               
             //print_r($people_det);
             
             $data['Formtab3'.$i]['prefix'] = $people_det[0]['People']['prefix'];
             $data['Formtab3'.$i]['firstname'] = $people_det[0]['People']['name'];
             $data['Formtab3'.$i]['lastname'] = $people_det[0]['People']['last_name'];
             $data['Formtab3'.$i]['txt_birthday_cus'] = $people_det[0]['People']['birthday'];
             $data['Formtab3'.$i]['f_highest_ed_level'] = $people_arr[$i-1]['Family']['highest_ed_level'];
             $data['Formtab3'.$i]['f_occupation'] = $people_det[0]['People']['occupation_id'];
             $data['Formtab3'.$i]['f_income_range_id'] = $people_det[0]['People']['income_range_id'];
             $data['Formtab3'.$i]['people_id'] = $people_arr[$i-1]['Family']['people_id'];
             $data['Formtab3'.$i]['family_id'] = $people_arr[$i-1]['Family']['fm_id'];
             $data['Formtab3'.$i]['target_id'] = $tid;
             //print_r($data);
             $this->data = $data;
            }
         }
         
         $this->set("header_title",$header_title);
         $this->set("ary_tambol",$ary_tambol); 
        //$this->set('posts', $this->Post->find('all'));
    }
    
    public function find_tambon(){
            $this->autoRender = false;
            $this->layout = false;
            
            //print_r($this->data);
            $amphor_id = $this->data['amphor_id'];
            $mode = $this->data['mode'];
            
            //echo $amphor_id;
            if ($amphor_id != "") {
            	
            	$tmp_tambol = $this->Tambol->find('all',array(
            		"fields" => array("Tambol.tambol_id","Tambol.description"),
            		"conditions" => array("Tambol.amphor_id"=>$amphor_id),
            		"order" => "description"
            		));
    
                //print_r($tmp_tambol);
                //$arr_tambol = array();
                //$arr_tambol[0] = "<option value='0'>โปรดเลือกอำเภอ</option>";
               //echo "<option value='0'>โปรดเลือกอำเภอ</option>";
                foreach($tmp_tambol as $tambol):
                    //echo $tambol["tbl_tambol"]["tambol_id"];
                    //echo $tambol["tbl_tambol"]["description"];
                    //$arr_tambol[$tambol["Tambol"]["tambol_id"]] = $tambol["Tambol"]["description"];
                    echo "<option value='".$tambol["Tambol"]["tambol_id"]."'>".$tambol["Tambol"]["description"]."</option>";
                    //print_r($tambol);
                endforeach;
            } //else {
                //$arr_tambol = array();
                //$arr_tambol[0] = "โปรดเลือกอำเภอ";
                //echo "<option value='0'>โปรดเลือกอำเภอ</option>";
            //}
            //$this->set('arr_tambol',$arr_tambol);
            //$this->set('mode',$mode);
    }
    
    public function find_postal(){
            $this->autoRender = false;
            //$this->layout = false;
            
            //print_r($this->data);
            $amphor_id = $this->data['amphor_id'];
            //echo $amphor_id;
            if ($amphor_id != "") {
                
            	$tmp_postal = $this->Postal->find('all',array(
            		"fields" => array("Postal.postal_code"),
            		"conditions" => array("Postal.amphor_id"=>$amphor_id),
            		"limit" => "1"
            		));
            	
            	/*$sql = "select postal_code from tbl_postal where amphor_id = ".$amphor_id ." limit 1";
                $tmp_postal = $this->Survey->query($sql);*/
    
                foreach($tmp_postal as $postal):
                    echo $postal["Postal"]["postal_code"];
                    //echo $tambol["tbl_tambol"]["description"];
                endforeach;
            } else {
                echo "";
            }
    }    
    
    public function add_data(){
        $this->autoRender = false;
        //print_r($this->data);
        //echo "test";
        
        $data = $this->data;
        
        //Array ( [Formjon1] => Array ( [officer_type] => 0 [prefix] => นาย
        //[firstname] => [lastname] => [txtbdname] => [txtfloor] => [txthousename] =>
        //[txthousegroup] => [txtsubstreet] => [txtstreet] => [tambol] => [amphor] =>
        //[postal_code] => [txttel] => [txtmobile] => [officer_id] => [officer_people_id] => ) )
        
        // ++++ Assign data for insert into table people ++++
        $prefix = $data['Formjon1']['prefix']; // คำนำหน้า
        $firstname = $data['Formjon1']['firstname']; // ชื้อต้น
        $lastname = $data['Formjon1']['lastname']; // นามสกุล
        
        // ++++ Assign data for insert into table officer ++++
        $officer_type = $data['Formjon1']['officer_type']; // ตำแหน่ง อพม,
        
        //echo $prefix.":".$firstname.":".$lastname.":".$officer_type;
        
        // ++++ Assign data for insert into table Address ++++
        //print_r($data);
        $BDname = $data['Formjon1']['txtbdname']; // ข้อมูลตึก
        $floor = $data['Formjon1']['txtfloor']; // ชั้นของตึก
        $housegroup = $data['Formjon1']['txthousegroup']; // หมู่
        $housename = $data['Formjon1']['txthousename']; // หมู่บ้าน
        $substreet = $data['Formjon1']['txtsubstreet']; // ซอย
        $road = $data['Formjon1']['txtstreet']; // ถนน
        $province = $data['Formjon1']['txtprovince']; // จังหวัด
        $tambol = $data['Formjon1']['tambol_id'];
        $amphor = $data['Formjon1']['amphor'];
        
        $postalCode = $data['Formjon1']['postal_code']; // รหัสไปรษณีย์
        $hometel = $data['Formjon1']['txttel']; // เบอร์บ้าน
        $mobileno = $data['Formjon1']['txtmobile']; // เบอร์มือถือ
          
          /*
        // Check Record Exists or Not in tbl_address
        
  		$conditions = array(
  			"Address.building" 	  => $BDname,
  			"Address.level"		  => $floor,
  			"Address.moo"		  => $housegroup,
  			"Address.mooban"	  => $housename,
  			"Address.soi"		  => $substreet,
  			"Address.road"		  => $road,
  			"Address.province_id" => "1",
  			"Address.postal_code" => $postalCode,
  			"Address.tambol_id"	  => $tambol,
	  		"Address.amphor_id"	  => $amphor,
	  		"Address.home_tel"	  => $hometel,
	  		"Address.mobile_tel"  => $mobileno
	  		//"Address.address_id" => 5
	  	);
	  	//print_r($conditions);
	  	
	  	$exists = "0";
	  	if ($this->Address->hasAny($conditions)) {
	  		$exists = "1";
	  	}
	  	//echo $exists;
	    */
          
          //Not check [existed] in address
          //#Add by Saranya K. on Feb 27,2013
          $exists = "0";
          
          
        // If check not found then Insert a new one
        // ตรวจสอบแล้วไม่พบข้อมูลที่ผู้ใช้กรอกมา
    	// ให้ทำโค้ดด้านล่างคือการเพิ่มข้อมูลเข้าสู่ฐานข้อมูล
    	// Coder : jon
        if ($exists == "0") {
        	
        	$data = array();
        	
        	$data['Address']['building'] = $BDname;
	        $data['Address']['level'] = $floor;
	        $data['Address']['moo'] = $housegroup;
	        $data['Address']['mooban'] = $housename;
	        $data['Address']['soi'] = $substreet;
	        $data['Address']['road'] = $road;
	        $data['Address']['province_id'] = "1";
	        $data['Address']['postal_code'] = $postalCode;
	        $data['Address']['tambol_id'] = $tambol;
	        $data['Address']['amphor_id'] = $amphor;
	    	$data['Address']['home_tel'] = $hometel;
        	$data['Address']['mobile_tel'] = $mobileno;
        	
        	//print_r($data);
        	$this->Address->create();
        	if ($this->Address->save($data)){
        		echo "Complete"." ";
        		$address_id = $this->Address->getLastInsertID();
        		echo "Address id : ".$address_id."<br>";
        	} else {
        		echo "Not Complete";
        	}
        	
	  	} else {
	  		echo "Already have this record in the system ->";
	  		$tmp_arr_address = $this->Address->find('all',array(
	  			"fields" => array("Address.address_id"),
	  			"conditions" => $conditions,
	  			"limit" => "1"
	  			));
	  		foreach ($tmp_arr_address as $address) {
	  			$address_id = $address['Address']['address_id'];
	  		}
	  		echo "Address id : ".$address_id."<br>";
	  	}
		
	  	// ++ Check record exists or not in table people
	  	$con_people = array(
	  		"People.prefix" 	=> $prefix,
	  		"People.name" 		=> $firstname,
		  	"People.last_name" 	=> $lastname
	  		);
	  	
	  	$exists = "0";
	  	if ($this->People->hasAny($con_people)) {
	  		$exists = "1";
  		}
	  	//echo "People exists : ".$exists;
	  	
	  	if ($exists == "0") {
	  		$data = array();
	  		$data['People']['prefix'] = $prefix;
	  		$data['People']['name'] = $firstname;
	  		$data['People']['last_name'] = $lastname;
	  		$data['People']['address_id'] = $address_id;
	  		$this->People->create();
        	if ($this->People->save($data)){
        		echo "Complete Save in People"." ";
        		$people_id = $this->People->getLastInsertID();
        		echo "People id : ".$people_id."<br>";
        	} else {
        		echo "Not Complete in People";
        	}
	  	} else {
	  		echo "Already have this record in the system ->";
	  		$tmp_people = $this->People->find('all',array(
	  			"fields" 		=> array("People.people_id"),
	  			"conditions" 	=> array(
	  					"People.prefix"		=>$prefix,
	  					"People.name"		=>$firstname,
	  					"People.last_name"	=>$lastname),
	  			"limit"			=> "1"
	  			));
	  			
	  		//print_r($tmp_people);
	  		foreach ($tmp_people as $value) {
	  			$people_id = $value['People']['people_id'];
	  		}	
	  		echo "People id : ".$people_id."<br>";
	  	}
	  	
	  	// ++ Check record exists or not in table officer
	  	$con_officer = array(
	  		"Officer.people_id" 	=> $people_id,
	  		"Officer.officer_type" 	=> $officer_type
		);
	  	$exists = "0";
		if ($this->Officer->hasAny($con_officer)) {
			$exists = "1";
		}
		
		if ($exists == "0") {
			$data = array();
			$data['Officer']['people_id'] = $people_id;
			$data['Officer']['officer_type'] = $officer_type;
			
			$this->Officer->create();
			if ($this->Officer->save($data)){
        		echo "Complete Save in Officer"." ";
        		$officer_id = $this->Officer->getLastInsertID();
        		echo "Officer id : ".$officer_id."<br>";
        	} else {
        		echo "Not Complete in Officer";
        	}
		} else {
			echo "Already have this record in the system ->";
			$tmp_officer = $this->Officer->find('all',array(
				"fields" 		=> array("Officer.officer_id"),
				"conditions" 	=> $con_officer,
				"limit"			=> "1"
			));
			
			foreach ($tmp_officer as $officer) {
				$officer_id = $officer['Officer']['officer_id'];
        		echo "Officer id : ".$officer_id."<br>";
			}
		}
		echo "|".$officer_id."|".$people_id;
    }
    
    public function edit_data(){
        $this->autoRender = false;
        //print_r($this->data);
        //echo "test";
        
        $data = $this->data;
        
        //print_r($data);
        
        //Array ( [Formjon1] => Array ( [officer_type] => 0 [prefix] => นาย
        //[firstname] => [lastname] => [txtbdname] => [txtfloor] => [txthousename] =>
        //[txthousegroup] => [txtsubstreet] => [txtstreet] => [tambol] => [amphor] =>
        //[postal_code] => [txttel] => [txtmobile] => [officer_id] => [officer_people_id] => ) )
        
        // ++++ Assign data for insert into table people ++++
        $pid = $data['Formjon1']['people_id'];
        $prefix = $data['Formjon1']['prefix']; // คำนำหน้า
        $firstname = $data['Formjon1']['firstname']; // ชื้อต้น
        $lastname = $data['Formjon1']['lastname']; // นามสกุล
        
        // ++++ Assign data for insert into table officer ++++
        $oid = $data['Formjon1']['officer_id'];
        $officer_type = $data['Formjon1']['officer_type']; // ตำแหน่ง อพม,
        
        //echo $prefix.":".$firstname.":".$lastname.":".$officer_type;
        
        // ++++ Assign data for insert into table Address ++++
        //print_r($data);
        $aid = $data['Formjon1']['address_id']; // ข้อมูลตึก
        $BDname = $data['Formjon1']['txtbdname']; // ข้อมูลตึก
        $floor = $data['Formjon1']['txtfloor']; // ชั้นของตึก
        $housegroup = $data['Formjon1']['txthousegroup']; // หมู่
        $housename = $data['Formjon1']['txthousename']; // หมู่บ้าน
        $substreet = $data['Formjon1']['txtsubstreet']; // ซอย
        $road = $data['Formjon1']['txtstreet']; // ถนน
        $province = $data['Formjon1']['txtprovince']; // จังหวัด
        $tambol = $data['Formjon1']['tambol_id'];
        $amphor = $data['Formjon1']['amphor'];
        
        $postalCode = $data['Formjon1']['postal_code']; // รหัสไปรษณีย์
        $hometel = $data['Formjon1']['txttel']; // เบอร์บ้าน
        $mobileno = $data['Formjon1']['txtmobile']; // เบอร์มือถือ
          
        // Check Record Exists or Not in tbl_address
        
        // If check not found then Insert a new one
    	// Coder : jon
        	
    	$data = array();
    	
    	$data['Address']['address_id'] = $aid;
    	$data['Address']['building'] = $BDname;
        $data['Address']['level'] = $floor;
        $data['Address']['moo'] = $housegroup;
        $data['Address']['mooban'] = $housename;
        $data['Address']['soi'] = $substreet;
        $data['Address']['road'] = $road;
        $data['Address']['province_id'] = "1";
        $data['Address']['postal_code'] = $postalCode;
        $data['Address']['tambol_id'] = $tambol;
        $data['Address']['amphor_id'] = $amphor;
    	$data['Address']['home_tel'] = $hometel;
    	$data['Address']['mobile_tel'] = $mobileno;
    	
    	//print_r($data);
    	$status = "";
    	
    	$this->Address->create();
    	if ($this->Address->save($data)){
    		//echo "Update : Complete"." ";
    		//$address_id = $this->Address->getLastInsertID();
    		//echo "Address id : ".$address_id."<br>";
    		$status = "1";
    	} else {
    		//echo "Not Complete";
    		$status = "0";
    	}
		
	  	// ++ Check record exists or not in table people
	  	
  		$data = array();
  		$data['People']['people_id'] = $pid;
  		$data['People']['prefix'] = $prefix;
  		$data['People']['name'] = $firstname;
  		$data['People']['last_name'] = $lastname;
  		$data['People']['address_id'] = $aid;
  		
  		$this->People->create();
    	if ($this->People->save($data)){
    		//echo "Update : Complete Save in People"." ";
    		//$people_id = $this->People->getLastInsertID();
    		$people_id = $pid;
    		//echo "People id : ".$people_id."<br>";
    		$status = $status."1";
    	} else {
    		//echo "Not Complete in People";
    		$status = $status."0";
    	}

	  	// ++ Check record exists or not in table officer
		$data = array();
		$data['Officer']['officer_id'] = $oid;
		$data['Officer']['people_id'] = $people_id;
		$data['Officer']['officer_type'] = $officer_type;
		
		$this->Officer->create();
		if ($this->Officer->save($data)){
    		//echo "Update : Complete Update in Officer"." ";
    		//$officer_id = $this->Officer->getLastInsertID();
    		//$officer_id = $oid;
    		//echo "Officer id : ".$officer_id."<br>";
    		$status = $status."1";
    	} else {
    		//echo "Not Complete in Officer";
    		$status = $status."0";
    	}
    	
		//echo "|".$officer_id."|".$people_id;
		if ($status == "111") {
	  		echo "Complete update data";
	  	}
	  	else {
	  		echo "Incomplete update data";
	  	}
    }
    
    // ฟังก์ชั่นที่ใช้ในการเพิ่ม ข้อมูลกลุ่มเป้าหมาย
    function add_data_tab2(){
    	
    	$this->autoRender = false;
    	//print_r($this->data);
    	/*
    	Array ( [Formjon2] => Array ( [iddigit_1] => [iddigit_2] => [iddigit_3] => [iddigit_4] => [iddigit_5] => [iddigit_6] => [iddigit_7] => [iddigit_8] => [iddigit_9] => [iddigit_10] => [iddigit_11] => [iddigit_12] => [iddigit_13] => [prefix] => นาย [firstname] => [lastname] => [birthday_date] => [birthday_month] => [birthday_year] => [marital_status] => โสด [txtbdname_tab2] => [txtfloor_tab2] => [housegroup_tab2] => [housename_tab2] => [txtsoi_tab2] => [txtstreet_tab2] => [amphor_tab2] => 25 [postal_code_tab2] => 34360 [txtphone_tab2] => [txtmobile_tab2] => [t_live_with] => [t_live_with_relation] => [economy_status] => ยากจน [income_range_id] => 1 [officer_id] => [officer_people_id] => [txtprovince] => อุบลราชธานี [tambol_id] => 0 ) ) 
    	*/
    	
    	$data = $this->data;
    	
    	// ++ Assign data for insert into table People ++
    	//$id_cardno = $data['Formjon2']['iddigit_1'].$data['Formjon2']['iddigit_2'].$data['Formjon2']['iddigit_3'].$data['Formjon2']['iddigit_4'].$data['Formjon2']['iddigit_5'].$data['Formjon2']['iddigit_6'].$data['Formjon2']['iddigit_7'].$data['Formjon2']['iddigit_8'].$data['Formjon2']['iddigit_9'].$data['Formjon2']['iddigit_10'].$data['Formjon2']['iddigit_11'].$data['Formjon2']['iddigit_12'].$data['Formjon2']['iddigit_13'];
    	
    	$id_cardno = $data['Formjon2']['id_number'];
    	
    	$hid_type = $data['Formjon2']['hidden_type'];
    	
    	if ($hid_type != "2") {
	    	$name_prefix = $data['Formjon2']['prefix'];
	    	if ($name_prefix == "นาย" or $name_prefix == "เด็กชาย") {
	    		$sex = "M";
	    	} else {
	    		$sex = "F";
	    	}
	    	$firstname = $data['Formjon2']['firstname'];
	    	$lastname = $data['Formjon2']['lastname'];
	    	/*$bd_date = $data['Formjon2']['birthday_date'];
	    	$bd_month = $data['Formjon2']['birthday_month'];
	    	$bd_year = $data['Formjon2']['birthday_year'];*/
	    	$birthdate = $data['Formjon2']['txt_birthday_cus'];
	    	$marital_status = $data['Formjon2']['marital_status'];
	    	
	    	// ++ Assign data for insert into table Address ++
	    	$banno = $data['Formjon2']['txtbanno_tab2'];
	    	$bdname = $data['Formjon2']['txtbdname_tab2'];
	    	$floor = $data['Formjon2']['txtfloor_tab2'];
	    	$housegroup = $data['Formjon2']['housegroup_tab2'];
	    	$housename = $data['Formjon2']['housename_tab2'];
	    	$soi = $data['Formjon2']['txtsoi_tab2'];
	    	$street = $data['Formjon2']['txtstreet_tab2'];
	    	$amphor = $data['Formjon2']['amphor_tab2'];
	    	$tambol = $data['Formjon2']['tambol_tab2'];
	    	$province = $data['Formjon2']['txtprovince'];
	    	$postal = $data['Formjon2']['postal_code_tab2'];
	    	$phone = $data['Formjon2']['txtphone_tab2'];
	    	$mobile = $data['Formjon2']['txtmobile_tab2'];
    	}
    	// ++ Assign data for insert into table Relation ++
    	$live_with = $data['Formjon2']['t_live_with'];
    	$live_relation = $data['Formjon2']['t_live_with_relation'];
    	$economy = $data['Formjon2']['economy_status'];
    	$income_range_id = $data['Formjon2']['income_range_id'];
    	
    	// jon new ##
    	$health_status = $data['Formjon2']['health_status'];
    		if ($health_status == "2") {
    			$health_detail = $data['Formjon2']['health_symptoms'];
    		}
    	$disbody_status = $data['Formjon2']['disbody_status'];
    		if ($disbody_status == "2") {
    			$disbody_detail = $data['Formjon2']['disbody_detail'];
    		}
    	
    	$officer_id = $data['Formjon2']['officer_id'];
    	
    	if ($officer_id == ""){
    		$officer_id = "1";
    	}
    	
    	//print_r($data);
    	//echo "<hr>";
    	// Check Record Exists or Not in tbl_address
        if ($hid_type != "2") {
  			
        	$conditions = array(
	  			"Address.banno"			=> $banno,
	  			"Address.building" 	  	=> $bdname,
	  			"Address.level"		  	=> $floor,
	  			"Address.moo"		  	=> $housegroup,
	  			"Address.mooban"	  	=> $housename,
	  			"Address.soi"		  		=> $soi,
	  			"Address.road"		  	=> $street,
	  			"Address.province_id"	=> "1",
	  			"Address.postal_code"	=> $postal,
	  			"Address.tambol_id"	=> $tambol,
		  		"Address.amphor_id"	=> $amphor
		  		//"Address.home_tel"	  	=> $phone,
		  		//"Address.mobile_tel"  	=> $mobile
		  		//"Address.address_id" => 5
		  	);
	  	//print_r($conditions);
	  	
		  	$exists = "0";
		  	if ($this->Address->hasAny($conditions)) {
		  		$exists = "1";
		  	}
		  	//echo $exists;
		    
	        // If check not found then Insert a new one
	        // ตรวจสอบแล้วไม่พบข้อมูลที่ผู้ใช้กรอกมา
	    	// ให้ทำโค้ดด้านล่างคือการเพิ่มข้อมูลเข้าสู่ฐานข้อมูล
	    	// Coder : jon
	        if ($exists == "0") {
	        	
	        	$data = array();
	        	
	        	$data['Address']['banno'] = $banno;
	        	$data['Address']['building'] = $bdname;
		        $data['Address']['level'] = $floor;
		        $data['Address']['moo'] = $housegroup;
		        $data['Address']['mooban'] = $housename;
		        $data['Address']['soi'] = $soi;
		        $data['Address']['road'] = $street;
		        $data['Address']['province_id'] = "1";
		        $data['Address']['postal_code'] = $postal;
		        $data['Address']['tambol_id'] = $tambol;
		        $data['Address']['amphor_id'] = $amphor;
		    	$data['Address']['home_tel'] = $phone;
	        	$data['Address']['mobile_tel'] = $mobile;
	        	
	        	//print_r($data);
	        	$this->Address->create();
	        	if ($this->Address->save($data)){
	        		echo "Complete"." ";
	        		$address_id = $this->Address->getLastInsertID();
	        		echo "Address id : ".$address_id."<br>";
	        	} else {
	        		echo "Not Complete";
	        	}
	        	
		  	} else {
		  		echo "Already have this record in the system ->";
		  		$tmp_arr_address = $this->Address->find('all',array(
		  			"fields" => array("Address.address_id"),
		  			"conditions" => $conditions,
		  			"limit" => "1"
		  			));
		  		foreach ($tmp_arr_address as $address) {
		  			$address_id = $address['Address']['address_id'];
		  		}
		  		echo "Address id : ".$address_id."<br>";
		  	}
    	
		  	// ++ Check record exists or not in table people
		  	//echo $name_prefix." ".$firstname." ".$lastname." ".$id_cardno;
		  	$con_people = array(
		  		"People.prefix" 	=> $name_prefix,
		  		"People.name" 		=> $firstname,
			  	"People.last_name" 	=> $lastname,
			  	"People.id_number" 	=> $id_cardno
		  		);
	  	
		  	$exists = "0";
		  	if ($this->People->hasAny($con_people)) {
		  		$exists = "1";
	  		}
		  	//echo "People exists : ".$exists;
	  	
		  	if ($exists == "0") {
		  		$data = array();
		  		$data['People']['prefix'] = $name_prefix;
		  		$data['People']['name'] = $firstname;
		  		$data['People']['last_name'] = $lastname;
		  		$data['People']['address_id'] = $address_id;
		  		$data['People']['sex'] = $sex;
		  		$data['People']['id_number'] = $id_cardno;
		  		//$data['People']['birthday'] = $bd_year."-".$bd_month."-".$bd_date;
		  		$data['People']['birthday'] = $birthdate;
		  		$data['People']['marital_status'] = $marital_status;
		  		$data['People']['health_status'] = $health_status;
		  		$data['People']['disable_status'] = $disbody_status;
	    	
		  		$this->People->create();
	        	if ($this->People->save($data)){
	        		echo "Complete Save in People"." ";
	        		$people_id = $this->People->getLastInsertID();
	        		echo "People id : ".$people_id."<br>";
	        	} else {
	        		echo "Not Complete in People";
	        	}
		  	} else {
		  		echo "Already have this record in the system ->";
		  		$tmp_people = $this->People->find('all',array(
		  			"fields" 		=> array("People.people_id"),
		  			"conditions" 	=> array(
		  					"People.prefix"		=>$name_prefix,
		  					"People.name"		=>$firstname,
		  					"People.last_name"	=>$lastname),
		  			"limit"			=> "1"
		  			));
		  			
		  		//print_r($tmp_people);
		  		foreach ($tmp_people as $value) {
		  			$people_id = $value['People']['people_id'];
		  		}	
		  		echo "People id : ".$people_id."<br>";
		  	}
	  	} // เสร็จการดักข้อมูล hidden_type -> มีข้อมูลอยู่แล้วในข้อมูลครอบครัว
	  	else {
	  			$people_id = $data['Formjon2']['hidden_people_id']; 
	  			
	  			// อัพเดทข้อมูล การเจ็บป่วยและความพิการ
	  			$data = array();
		  		$data['People']['people_id'] = $people_id;
		  		$data['People']['health_status'] = $health_status;
		  		$data['People']['disable_status'] = $disbody_status;
		  		$this->People->create();
	        	$this->People->save($data);
	        	
	  	}
	  	
	  	// Save data to Health Mapping
	  	$this->Health_mapping->deleteAll(array('Health_mapping.people_id' =>$people_id), false);
	  	if ($health_status == "2") {
	  		foreach ($health_detail as $key => $value) {
	  			$data = array();
		  		$data['Health_mapping']['people_id'] = $people_id;
		  		$data['Health_mapping']['hstatus_id'] = $value;
	    	
		  		$this->Health_mapping->create();
	        	if ($this->Health_mapping->save($data)){
	        		echo $key."Complete Save in Health_mapping (health)";
	        	} else {
	        		echo $key."Not Complete in Health_mapping (health)";
	        	}
	  		}
	  	}
	  	if ($disbody_status == "2") {
	  		foreach ($disbody_detail as $key => $value) {
	  			$data = array();
		  		$data['Health_mapping']['people_id'] = $people_id;
		  		$data['Health_mapping']['hstatus_id'] = $value;
	    	
		  		$this->Health_mapping->create();
	        	if ($this->Health_mapping->save($data)){
	        		echo $key."Complete Save in Health_mapping (disabled)";
	        	} else {
	        		echo $key."Not Complete in Health_mapping (disabled)";
	        	}
	  		}
	  	}
	  	
	  	// ++ Check record exists or not in table target_people
	  	// Model : Target
	  	$con_target = array(
	  		"Target.people_id" => $people_id
	  		);
	  		
	  	$exists = "0";
	  	if ($this->Target->hasAny($con_target)) {
	  		$exists = "1";
  		}
	  	
  		if ($exists == "0") {
	  		$data = array();
	  		
	  		$data['Target']['officer_id'] = $officer_id;
	  		$data['Target']['people_id'] = $people_id;
	  		$data['Target']['live_with'] = $live_with;
	  		$data['Target']['live_with_relation'] = $live_relation;
	  		$data['Target']['eco_status'] = $economy;
	  		$data['Target']['income_range_id'] = $income_range_id;
	  		
    		$this->Target->create();
        	if ($this->Target->save($data)){
        		echo "Complete Save in Target"." ";
        		$tid = $this->Target->getLastInsertID();
        		echo "tid id : ".$tid."<br>";
        	} else {
        		echo "Not Complete in Target";
        	}
	  	} else {
	  		echo "Already have this record in the system ->";
	  		$tmp_target = $this->Target->find('all',array(
	  			"fields" 		=> array("Target.tid"),
	  			"conditions" 	=> array("Target.people_id" => $people_id),
	  			"limit"			=> "1"
	  			));
	  			
	  		//print_r($tmp_people);
	  		foreach ($tmp_target as $value) {
	  			$tid = $value['Target']['tid'];
	  		}	
	  		echo "Tid : ".$tid."<br>";
	  	}
	  	//echo "|".$tid;
    }
    
    function check_people(){
    	$this->autoRender = false;
    	
    	$id_number = $this->data['id_number'];
    	$valid_people = "1|";
    	
    	$con_people = array(
    		"People.id_number" => $id_number
	    );
    	$exists = "0";
	  	if ($this->People->hasAny($con_people)) {
	  		$exists = "1";
  		}
  		
    	// Find people_id
    	if ($exists == "1") {
    		$arr_people = $this->People->find('all',array(
    			"conditions"	=>	$con_people,
    			"limit"			=> "1"
	    		));	
	    
	    	$people_id = $arr_people['0']['People']['people_id'];
	    	$address_id = $arr_people['0']['People']['address_id'];
	    	
	    	// เช็คว่าเป็น officer id หรือเปล่า
	    	$con_officer = array(
	    			"Officer.people_id" => $people_id
		    		);
		    if ($this->Officer->hasAny($con_officer)) {
	  			$valid_people = "0|";
  			}
  			
  			// เช็คว่ามีข้อมูลการคีย์เป็นกลุ่มเป้าหมายแล้วหรือเปล่า
  			$con_target = array(
	  				"Target.people_id" => $people_id
	  				);
	  		if ($this->Target->hasAny($con_target)) {
	  			$valid_people = "0|";
  			}
  			
  			// เช็คว่ามีข้อมูลการคีย์เป็นกลุ่มเป้าหมายแล้วหรือเปล่า
  			$con_family = array(
	  				"Family.people_id" => $people_id
	  				);
	  		if ($this->Family->hasAny($con_family)) {
	  			//$valid_people = "0";
	  			
	  			$people_prefix = $arr_people["0"]["People"]["prefix"];
	  			$people_name = $arr_people["0"]["People"]["name"];
	  			$people_last_name = $arr_people["0"]["People"]["last_name"];
	  			$people_birthday = $arr_people["0"]["People"]["birthday"];
	  			$people_marital = $arr_people["0"]["People"]["marital_status"];
	  			
	  			$return_data = $people_prefix."|".$people_name."|".$people_last_name."|".$people_birthday."|".$people_marital."|";
	  			$return_data .= $this->get_address_data($address_id);
	  			$return_data .= "|". $people_id;
	  			
	  			$valid_people = $valid_people.$return_data;
  			}
	    }
	    
	    echo $valid_people;
	    		
    }
    
    function edit_data_tab2(){
    	
    	$this->autoRender = false;
    	//print_r($this->data);
    	/*
    	Array ( [Formjon2] => Array ( [iddigit_1] => [iddigit_2] => [iddigit_3] => [iddigit_4] => [iddigit_5] => [iddigit_6] => [iddigit_7] => [iddigit_8] => [iddigit_9] => [iddigit_10] => [iddigit_11] => [iddigit_12] => [iddigit_13] => [prefix] => นาย [firstname] => [lastname] => [birthday_date] => [birthday_month] => [birthday_year] => [marital_status] => โสด [txtbdname_tab2] => [txtfloor_tab2] => [housegroup_tab2] => [housename_tab2] => [txtsoi_tab2] => [txtstreet_tab2] => [amphor_tab2] => 25 [postal_code_tab2] => 34360 [txtphone_tab2] => [txtmobile_tab2] => [t_live_with] => [t_live_with_relation] => [economy_status] => ยากจน [income_range_id] => 1 [officer_id] => [officer_people_id] => [txtprovince] => อุบลราชธานี [tambol_id] => 0 ) ) 
    	*/
    	
    	$data = $this->data;
    	
    	// ++ Assign data for insert into table People ++
    	$id_cardno = $data['Formjon2']['iddigit_1'].$data['Formjon2']['iddigit_2'].$data['Formjon2']['iddigit_3'].$data['Formjon2']['iddigit_4'].$data['Formjon2']['iddigit_5'].$data['Formjon2']['iddigit_6'].$data['Formjon2']['iddigit_7'].$data['Formjon2']['iddigit_8'].$data['Formjon2']['iddigit_9'].$data['Formjon2']['iddigit_10'].$data['Formjon2']['iddigit_11'].$data['Formjon2']['iddigit_12'].$data['Formjon2']['iddigit_13'];
    	
    	$name_prefix = $data['Formjon2']['prefix'];
    	if ($name_prefix == "นาย" or $name_prefix == "เด็กชาย") {
    		$sex = "M";
    	} else {
    		$sex = "F";
    	}
    	$firstname = $data['Formjon2']['firstname'];
    	$lastname = $data['Formjon2']['lastname'];
    	/*$bd_date = $data['Formjon2']['birthday_date'];
    	$bd_month = $data['Formjon2']['birthday_month'];
    	$bd_year = $data['Formjon2']['birthday_year'];*/
    	$birthdate = $data['Formjon2']['txt_birthday_cus'];
    	$marital_status = $data['Formjon2']['marital_status'];
    	
    	// ++ Assign data for insert into table Address ++
    	$bdname = $data['Formjon2']['txtbdname_tab2'];
    	$floor = $data['Formjon2']['txtfloor_tab2'];
    	$housegroup = $data['Formjon2']['housegroup_tab2'];
    	$housename = $data['Formjon2']['housename_tab2'];
    	$soi = $data['Formjon2']['txtsoi_tab2'];
    	$street = $data['Formjon2']['txtstreet_tab2'];
    	$amphor = $data['Formjon2']['amphor_tab2'];
    	$tambol = $data['Formjon2']['tambol_id'];
    	$province = $data['Formjon2']['txtprovince'];
    	$postal = $data['Formjon2']['postal_code_tab2'];
    	$phone = $data['Formjon2']['txtphone_tab2'];
    	$mobile = $data['Formjon2']['txtmobile_tab2'];
    	
    	// ++ Assign data for insert into table Relation ++
    	$live_with = $data['Formjon2']['t_live_with'];
    	$live_relation = $data['Formjon2']['t_live_with_relation'];
    	$economy = $data['Formjon2']['economy_status'];
    	$income_range_id = $data['Formjon2']['income_range_id'];
    	
    	$officer_id = $data['Formjon2']['officer_id'];
    	$people_id = $data['Formjon2']['people_id'];
    	$address_id = $data['Formjon2']['address_id'];
    	$target_id = $data['Formjon2']['target_id'];
    	
    	/*if ($officer_id == ""){
    		$officer_id = "1";
    	}*/
    	
    	// Check Record Exists or Not in tbl_address
        	
    	$data = array();
    	
    	$data['Address']['address_id'] = $address_id;
    	$data['Address']['building'] = $bdname;
        $data['Address']['level'] = $floor;
        $data['Address']['moo'] = $housegroup;
        $data['Address']['mooban'] = $housename;
        $data['Address']['soi'] = $soi;
        $data['Address']['road'] = $street;
        $data['Address']['province_id'] = "1";
        $data['Address']['postal_code'] = $postal;
        $data['Address']['tambol_id'] = $tambol;
        $data['Address']['amphor_id'] = $amphor;
    	$data['Address']['home_tel'] = $phone;
    	$data['Address']['mobile_tel'] = $mobile;
    	
    	$status = "";
    	
    	//print_r($data);
    	$this->Address->create();
    	if ($this->Address->save($data)){
    		/*echo "Update : Complete"." ";
    		echo "Address id : ".$address_id."<br>";*/
    		$status = "1";
    	} else {
    		echo "Not Complete";
    		$status = "0";
    	}
    	
	  	// ++ Check record exists or not in table people
	  	//echo $name_prefix." ".$firstname." ".$lastname." ".$id_cardno;
	  	
  		$data = array();
  		$data['People']['people_id'] = $people_id;
  		$data['People']['prefix'] = $name_prefix;
  		$data['People']['name'] = $firstname;
  		$data['People']['last_name'] = $lastname;
  		$data['People']['address_id'] = $address_id;
  		$data['People']['sex'] = $sex;
  		$data['People']['id_number'] = $id_cardno;
  		//$data['People']['birthday'] = $bd_year."-".$bd_month."-".$bd_date;
  		$data['People']['birthday'] = $birthdate;
  		$data['People']['marital_status'] = $marital_status;
		
  		$this->People->create();
    	if ($this->People->save($data)){
    		//echo "Complete update in People"." ";
    		//$people_id = $this->People->getLastInsertID();
    		//echo "People id : ".$people_id."<br>";
    		$status = $status."1";
    	} else {
    		//echo "Not Complete in People";
    		$status = $status."0";
    	}
	  	
	  	// ++ Check record exists or not in table target_people
	  	// Model : Target
  		$data = array();  	
  		
  		$data['Target']['tid'] = $target_id;
  		$data['Target']['officer_id'] = $officer_id;
  		$data['Target']['people_id'] = $people_id;
  		$data['Target']['live_with'] = $live_with;
  		$data['Target']['live_with_relation'] = $live_relation;
  		$data['Target']['eco_status'] = $economy;
  		$data['Target']['income_range_id'] = $income_range_id;
  		
		$this->Target->create();
    	if ($this->Target->save($data)){
    		//echo "Complete update in Target"." ";
    		//$target_id = $this->Target->getLastInsertID();
    		//echo "tid id : ".$target_id."<br>";
    		$status = $status."1";
    	} else {
    		//echo "Not Complete in People";
    		$status = $status."0";
    	}
	  	
	  	if ($status == "111") {
	  		echo "Complete update data";
	  	}
	  	else {
	  		echo "Incomplete update data";
	  	}
    }
    
    
    function get_address_data($address_id = null){
        $this->autoRender = false;
        
        if(!empty($address_id)){
            $tmp = $this->Address->find('first', array(
                'conditions' => array("Address.address_id" => $address_id)   
            ));
            if(!empty($tmp)){
                 	 	 		 	 		
                $banno = $tmp["Address"]["banno"];		 	 	 	 	 	 		
                $building = $tmp["Address"]["building"]; 	 	 	 	 	 		
                $level = $tmp["Address"]["level"];		 	 	 	 	 	 		
                $moo = $tmp["Address"]["moo"]; 	 	 	 	 	 		
                $mooban = $tmp["Address"]["mooban"];
                $soi = $tmp["Address"]["soi"];		 	 	 	 	 	 		
                $road = $tmp["Address"]["road"];		 	 	 	 	 	 		
                $tambol_id = $tmp["Address"]["tambol_id"];
                
                //#Get [description] of [Tambol_id]
                if(empty($tambol_id)){
                    $tambol_description = "";  
                }else{
                    $tmp1 = $this->Tambol->find('first',array(
                        'conditions' => array(
                            'Tambol.tambol_id' => $tambol_id
                        )
                    ));
                    if(!empty($tmp1)){
                        $tambol_description = $tmp1["Tambol"]["description"];
                    }else{
                        $tambol_description = "";
                    }
                }
                
                $amphor_id = $tmp["Address"]["amphor_id"];			 	 	 	 	 	 		
                $province_id	 = $tmp["Address"]["province_id"];	 	 	 	 	 	 		
                $postal_code = $tmp["Address"]["postal_code"];		 	 	 	 	 	 		
                $home_tel = $tmp["Address"]["home_tel"];	 	 	 	 	 	 		
                $mobile_tel = $tmp["Address"]["mobile_tel"];
                
                return $banno . "|" . $building . "|" . $level . "|" . $moo . "|" . $mooban . "|" . $soi . "|" . $road . "|" . $tambol_id . "|" . $tambol_description . "|" . $amphor_id . "|" . $province_id . "|" . $postal_code . "|" . $home_tel . "|" . $mobile_tel;
            }
        }
        return "";
    }
    //########################################
    
    function add_data_tab3(){
    	$this->autoRender = false;
    	
    	//print_r($this->data);
    	
    	/*Array ( [Formtab31] => Array ( [prefix] => นาย [firstname] => [lastname] => [birthday_date] => [f_highest_ed_level] => 0 [f_occupation] => 0 [f_income_range_id] => 1 [f_heath_type] => 0 [f_heath_details] => data[Formtab32][prefix]=นาย ) [Formtab32] => Array ( [firstname] => [lastname] => [birthday_date] => [f_highest_ed_level] => 0 [f_occupation] => 0 [f_income_range_id] => 1 [f_heath_type] => 0 [f_heath_details] => ) [total_person] => 2 [target_id] => 19 )  */
    	
    	$total_person = $this->data['total_person'];
    	$target_id = $this->data['target_id'];
    	
    	// Insert into table people if not exists in this table
    	
    	for ($i=1; $i <= $total_person ; $i++) { 
    		
    		$name_prefix = $this->data['Formtab3'.$i]['prefix'];
	    	if ($name_prefix == "นาย" or $name_prefix == "เด็กชาย") {
	    		$sex = "M";
	    	} else {
	    		$sex = "F";
	    	}
    		$first_name = $this->data['Formtab3'.$i]['firstname'];
    		$last_name = $this->data['Formtab3'.$i]['lastname'];
    		/*$bd_date = $this->data['Formtab3'.$i]['birthday_date'];
    		$bd_month = $this->data['Formtab3'.$i]['birthday_month'];
    		$bd_year = $this->data['Formtab3'.$i]['birthday_year'];*/
    		$birthdate = $this->data['Formtab3'.$i]['txt_birthday_cus'];
    		$high_ed_level = $this->data['Formtab3'.$i]['f_highest_ed_level'];
    		$occupation = $this->data['Formtab3'.$i]['f_occupation'];
    		$income = $this->data['Formtab3'.$i]['f_income_range_id'];
    		$health_id = $this->data['Formtab3'.$i]['f_heath_type'];
    		$health_detail = $this->data['Formtab3'.$i]['f_heath_details'];
    		
    		// ++ Check record exists or not in table people
		  	//echo $name_prefix." ".$firstname." ".$lastname." ".$id_cardno;
		  	$con_people = array(
		  		"People.prefix" 	=> $name_prefix,
		  		"People.name" 		=> $first_name,
			  	"People.last_name" 	=> $last_name
			  	//"People.id_number" 	=> $id_cardno
		  		);
	  	
		  	$exists = "0";
		  	if ($this->People->hasAny($con_people)) {
		  		$exists = "1";
	  		}
		  	//echo "People exists : ".$exists;
		  	
		  	if ($exists == "0") {
		  		$data = array();
		  		$data['People']['prefix'] = $name_prefix;
		  		$data['People']['name'] = $first_name;
		  		$data['People']['last_name'] = $last_name;
		  		//$data['People']['address_id'] = "0";
		  		$data['People']['sex'] = $sex;
		  		//$data['People']['id_number'] = "";
		  		//$data['People']['birthday'] = $bd_year."-".$bd_month."-".$bd_date;
		  		$data['People']['birthday'] = $birthdate;
		  		//$data['People']['marital_status'] = $marital_status;
		  		$data['People']['income_range_id'] = $income;
		  		$data['People']['occupation_id'] = $occupation;
	    	
		  		$this->People->create();
	        	if ($this->People->save($data)){
	        		echo "Complete Save in People"." ";
	        		$people_id = $this->People->getLastInsertID();
	        		echo "People id : ".$people_id."<br>";
	        	} else {
	        		echo "Not Complete in People";
	        	}
		  	} else {
		  		echo "Already have this record in the system ->";
		  		$tmp_people = $this->People->find('all',array(
		  			"fields" 		=> array("People.people_id"),
		  			"conditions" 	=> array(
		  					"People.prefix"		=>$name_prefix,
		  					"People.name"		=>$first_name,
		  					"People.last_name"	=>$last_name),
		  			"limit"			=> "1"
		  			));
		  			
		  		//print_r($tmp_people);
		  		foreach ($tmp_people as $value) {
		  			$people_id = $value['People']['people_id'];
		  		}	
		  		echo "People id : ".$people_id."<br>";
		  	}
    		
		  	// ++ Check record exists or not in table family mapping
		  	$con_family = array(
		  		"Family.tid" 		=> $target_id,
		  		"Family.people_id" 	=> $people_id
		  		);
		  		
		  	$exists = "0";
		  	if ($this->Family->hasAny($con_family)) {
		  		$exists = "1";
	  		}
		  	
		  	if ($exists == "0") {
		  		$data = array();
		  		$data['Family']['tid'] = $target_id;
		  		$data['Family']['people_id'] = $people_id;
		  		$data['Family']['highest_ed_level'] = $high_ed_level;
	    	
		  		$this->Family->create();
	        	if ($this->Family->save($data)){
	        		echo "Complete Save in Family"." ";
	        		$family_id = $this->Family->getLastInsertID();
	        		echo "Family id : ".$family_id."<br>";
	        	} else {
	        		echo "Not Complete in Family";
	        	}
		  	} else {
		  		echo "Already have this record in the system ->";
		  		$tmp_family = $this->Family->find('first',array(
		  			"fields" 		=> array("Family.fm_id"),
		  			"conditions" 	=> array(
		  					"Family.tid"		=>$target_id,
		  					"Family.people_id"		=>$people_id		  			
		  			)));
		  			
		  		//print_r($tmp_people);
		  		foreach ($tmp_family as $value) {
		  			$family_id = $value['Family']['fm_id'];
		  		}	
		  		echo "Family id : ".$family_id."<br>";
		  	}
		  	
    	}
    	
    }
    
    function edit_data_tab3(){
     $this->autoRender = false;
     
     //print_r($this->data);
     
     /*Array ( [Formtab31] => Array ( [prefix] => นาย [firstname] => [lastname] => [birthday_date] => [f_highest_ed_level] => 0 [f_occupation] => 0 [f_income_range_id] => 1 [f_heath_type] => 0 [f_heath_details] => data[Formtab32][prefix]=นาย ) [Formtab32] => Array ( [firstname] => [lastname] => [birthday_date] => [f_highest_ed_level] => 0 [f_occupation] => 0 [f_income_range_id] => 1 [f_heath_type] => 0 [f_heath_details] => ) [total_person] => 2 [target_id] => 19 )  */
     
     $total_person = $this->data['total_person'];     
     
     // Insert into table people if not exists in this table
     
     for ($i=1; $i <= $total_person ; $i++) { 
            
      $name_prefix = $this->data['Formtab3'.$i]['prefix'];
      if ($name_prefix == "นาย" or $name_prefix == "เด็กชาย") {
       $sex = "M";
      } else {
       $sex = "F";
      }
      $first_name = $this->data['Formtab3'.$i]['firstname'];
      $last_name = $this->data['Formtab3'.$i]['lastname'];
      $birthdate = $this->data['Formtab3'.$i]['txt_birthday_cus'];
      $high_ed_level = $this->data['Formtab3'.$i]['f_highest_ed_level'];
      $occupation = $this->data['Formtab3'.$i]['f_occupation'];
      $income = $this->data['Formtab3'.$i]['f_income_range_id'];
      $health_id = $this->data['Formtab3'.$i]['f_heath_type'];
      $health_detail = $this->data['Formtab3'.$i]['f_heath_details'];
      
      $people_id = $this->data['Formtab3'.$i]['people_id'];
      $target_id = $this->data['Formtab3'.$i]['target_id'];
      $family_id = $this->data['Formtab3'.$i]['family_id'];
   
      // ++ Check record exists or not in table people
     
     $data = array();
     $data['People']['people_id'] = $people_id;
     $data['People']['prefix'] = $name_prefix;
     $data['People']['name'] = $first_name;
     $data['People']['last_name'] = $last_name;
     $data['People']['address_id'] = "0";
     $data['People']['sex'] = $sex;
     //$data['People']['id_number'] = "";
     //$data['People']['birthday'] = $bd_year."-".$bd_month."-".$bd_date;
     $data['People']['birthday'] = $birthdate;
     //$data['People']['marital_status'] = $marital_status;
     $data['People']['income_range_id'] = $income;
     $data['People']['occupation_id'] = $occupation;
     
     $this->People->create();
         if ($this->People->save($data)){
          echo "Update complete in People<br>";
         } else {
          echo "Update incomplete in People<br>";
         }
      
     // ++ Check record exists or not in table family mapping
     $data = array();
     $data['Family']['fm_id'] = $family_id;
     $data['Family']['tid'] = $target_id;
     $data['Family']['people_id'] = $people_id;
     $data['Family']['highest_ed_level'] = $high_ed_level;
     
     $this->Family->create();
         if ($this->Family->save($data)){
          echo "Update complete in Family<br>";
         } else {
          echo "Update incomplete in Family<br>";
         }
     }
     
    }


    function add_new_heath() {
        $this->autoRender = false;

        //print_r($this->data);
        $new_health_detail = $this->data['Health']['new_health_detail'];
    	$health_type = $this->data['Health']['health_type'];
        // ++ Check record exists or not in table people
        //echo $name_prefix." ".$firstname." ".$lastname." ".$id_cardno;
        $con_health = array(
            "Health.details"     => $new_health_detail,
            "Health.type"    => $health_type
            );
    
        $exists = "0";
        if ($this->Health->hasAny($con_health)) {
            $exists = "1";
        }

        if ($exists == "0"){
            $data = array();
            $data['Health']['type']=$health_type;
            $data['Health']['details']=$new_health_detail;

            $this->Health->create();
            if ($this->Health->save($data)){
                    //echo "1";
                    $health_id = $this->Health->getLastInsertID();
                    echo $health_id;
                    //echo "Health id : ".$health_id."<br>";
                } else {
                    echo "0";
            }
        }
    }

}
?>