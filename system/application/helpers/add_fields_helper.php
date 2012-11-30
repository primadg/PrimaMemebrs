<?php
/**
* 
* THIS FILE CONTAINS ADD FIELDS FUNCTIONS
* 
* @package Needsecure
* @author uknown
* @version uknown
*/
/**
* Check fields
*
* @param unknown_type $id
* @param array $value
* @return mixed
*/
function check_field($id,$value)
{
    $CI = &get_instance();
    $id = intval($id);

    if( intval($id)<=0 )
    {
        return false;
    }
    if( !is_array($value) and mb_strlen($value)>=65535 )
    {
        return false;        
    }
    
    $query = $CI->db->get_where(db_prefix."Add_fields",array('id'=>$id));
    if( $query->num_rows()>0 )
    {
        $add_field_info = $query->result_array();
    }
    else
    {
        return false;
    }
    
    $error = intval(0);
    $error_text = '';
    unset($val_array,$value_array,$val);

    $id = intval($add_field_info[0]['id']);
    $name = $add_field_info[0]['name'];
    $req = intval($add_field_info[0]['req']);
    $type = intval($add_field_info[0]['type']);
    $def_value = $add_field_info[0]['def_value'];
    $check_rule = intval($add_field_info[0]['check_rule']);
    $val = trim($add_field_info[0]['val']);
    $val_array = explode(',',$val);
    
    $default_error_text = '<{user_registration_error_please_check}>'.' '.output($name);

    if( $type == 5 or $type == 2 or $type == 3 )
    {
        // check range in select,radio,select multiple		
        
        if( is_array($value) )
        {
            $value_array = $value;
        }
        else
        {
            $value_array = array($value);
        }
        
        if ( sizeof(array_diff($value_array,$val_array))>0 &&!(count($value_array)==1&&trim($value_array[0]) == ""))
        {
            return $default_error_text;
        }
        
        
        // _check range in select,radio,select multiple

        if( $req > 0 and ($type == 5 or $type == 2 or $type == 3) and is_array($value_array) and sizeof( $value_array ) == 1 )
        {
            foreach( $value_array as $array )
            {				
                if( trim($array) == "" )
                {
                    return 	'<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_not_empty}>';
                }
            }
        }		
        
        // ignore radio, select, selectmultiple
        return true;
        //_ ignore radio, select, selectmultiple
        
    }   
    
    if( $req > 0 )
    {
        if( is_array($value) and $type == 3 )
        {
            if( sizeof($value)<=0 )
            {
                // empty error
                $error_text = '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_not_empty}>';
                $error++;                    
            }
        }
        else if( !is_array($value) and $type != 3 )
        {
            if ( !isset($value) or empty($value) )
            {
                // empty error
                $error_text = '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_not_empty}>';
                $error++;                    
            }
        }
        else
        {
            return $default_error_text;
        }
    }
    
    
    if( !is_array($value) and (($req <=0 and !empty($value) ) or $req > 0 ))
    {
        switch ( $check_rule )
        {
        case 2:
            // numbers only
            if( eregi("^[0-9]+$",$value) == false )
            { 
                $error_text = '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_numbers_only}>';
                $error++;
            }
            // _numbers only            
            break;
            
        case 3:
            // letters only
            if( eregi("^[a-zA-Z]+$",$value) == false )
            {
                $error_text = '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_letters_only}>';
                $error++;
            }
            // _letters only            
            break;
            
        case 4:
            // email
            if( eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$",$value) == false )
            {
                $error_text = '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_email}>';
                $error++;
            }
            // email
            break;
            
        case 6:
            // phone
            if( eregi("^[-0-9+( )]+$",$value) == false )
            {
                $error_text = '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_phone}>';
                $error++;
            }
            // phone
            break;

        }
    }
    if( $error <=0 )
    {
        return true;
    }
    else
    {
        return $error_text;
    }
    
    return $default_error_text;
}
/**
* Enter description here...
*
* @param unknown_type $id
* @param unknown_type $user_value
* @param unknown_type $for_user
* @return mixed
*/
function generate_field_html( $id,$user_value, $for_user=false )
{
    $CI = &get_instance();
    $id = intval($id);
    $for_user = (bool)$for_user;
    $data = array();
    
    if( $id <=0 )
    {
        return false;
    }
    
    // get field_data
    $CI->db->select('id,type,name,descr,req,type,def_value,check_rule,val');
    $CI->db->limit(1);
    $query = $CI->db->get_where(db_prefix.'Add_fields',array('id'=>$id));
    if( $query->num_rows() > 0)
    {
        $field_type_info = $query->result_array();
        $field_type_id = intval($field_type_info[0]['type']);
        $data['field_data'] = $field_type_info;
        foreach( $data['field_data'] as $key=>$val)
        {
            $data['field_data'][$key]['name'] = word_wrap($val['name'],30,2,' ');
            $data['field_data'][$key]['descr'] = word_wrap($val['descr'],30,2,' ');
        }
        if( isset($user_value) and !empty($user_value) )
        {
            $data['user_value'] = $user_value;
        }
        else
        {
            $data['user_value'] = '';            
        }
    }
    else
    {
        return false;
    }
    // get field_data
    
    
    
    
    
    if( $for_user )
    {
        $return =  _view('user/common/add_fields/field_type_'.$field_type_id,$data,1);
        unset($user_value);
        return $return;
    }
    else
    {
        $return = $CI->load->view('admin/common/add_fields/field_type_'.$field_type_id,$data,1);
        unset($user_value);
        return $return;
        
    }
    return false;
}



/**
    * Gets additional fields as array
    *
    * @author Drovorubov
    * @param array $names field names for select
    * @return array 
    */
function get_add_fields($names)
{
    $rv = array();
    $CI = &get_instance();
    $CI->load->model('user_model');
    //Getting Email Keys Array
    $rv = $CI->user_model->get_add_fields($names);
    return $rv;
}
/**
* Enter description here...
*
* @param array $post
* @param boolean $is_return_vals
* @param boolean $no_update
* @return mixed
*/
function set_user_add_fields($post,$is_return_vals=false,$no_update=false)
{
    $user_values=array();
    if(isset($post['add_fields_keys'])&&isset($post['add_fields_values']))
    {
        $user_values=array_combine($post['add_fields_keys'],$post['add_fields_values']);
    }
    else
    {
        $user_values=array_intersect_key($post,array_flip(preg_grep("/^add_field_(\d+)/",array_keys($post))));
        if(count($user_values)>0)
        {
            $user_values=array_combine(preg_replace("/^add_field_(\d+)/i", "\${1}", array_keys($user_values)),$user_values);
        }
    }
    $data=normalize_user_add_field($user_values);
    if(count($data['errors'])==0 && isset($post['id']) && !$no_update)
    {        
        $CI = &get_instance();        
        foreach($data['values'] as $key=>$value)
        {
            $conditions=array();
            $conditions['user_id']=$post['id'];
            $conditions['field_id']=$key;            
            $query = $CI->db->get_where(db_prefix."User_add_fields",$conditions);
            if( $query->num_rows()>0 )
            {
                $query = $CI->db->update(db_prefix."User_add_fields",array('field_value'=>$value),$conditions);                
                if( $CI->db->affected_rows() == -1 )
                {
                    $data['errors'][$key]='not_updated';
                }                
            }
            else
            {
                $query = $CI->db->insert(db_prefix."User_add_fields",array('user_id'=>$post['id'],'field_value'=>$value,'field_id'=>$key));
                if( $CI->db->affected_rows() !==1 )
                {
                    $data['errors'][$key]='not_inserted';
                }
            }
        }        
    }
    if($is_return_vals)
    {
        return $data;
    }
    else
    {
        if(count($data['errors'])==0)
        {
            return true;
        }
        else
        {
            return $data['errors'];
        }
    }    
}
/**
* Normalize user fields
*
* @param array $user_values
* @return array
*/
function normalize_user_add_field($user_values)
{
    $CI = &get_instance();
    $query = $CI->db->get(db_prefix."Add_fields");
    $t=$query->result_array();
    $CI =& get_instance();        
    $CI->load->model("lang_manager_model"); 
    $t=$CI->lang_manager_model->combine_with_language_data($t,11,array('name'=>'name'),'id',array('col'=>'taborder'),false,&$add_params);
    $add_fields=$t;
    
    $data=array();
    $data['errors']=array();
    $data['values']=array();   
    foreach($add_fields as $field)
    {
        if($field['req']==1 && (!isset($user_values[$field['id']]) || (!is_array($user_values[$field['id']]) && $user_values[$field['id']]=="")))
        {   
            $data['errors'][$field['id']]='<{user_registration_add_fields_error_field_text}> '.output($field['name']).' <{user_registration_add_fields_error_text_not_empty}>';
        }
        if(isset($user_values[$field['id']]))
        {
            
            if($field['type']==2||$field['type']==3||$field['type']==5||$field['type']==6)
            {
                $user_values[$field['id']]=is_array($user_values[$field['id']]) ? $user_values[$field['id']] : explode(",",$user_values[$field['id']]);
                
                
                if(count(array_diff($user_values[$field['id']],explode("\n",$field['val'])))==0)
                {
                    $data['values'][$field['id']]=implode("\n",$user_values[$field['id']]);
                }
                else
                {
                    $data['errors'][$field['id']]='<{user_registration_add_fields_error_field_text}> '.output($field['name']).' <{user_registration_add_fields_error_text_unknown_value}>';  
                }
            }
            else
            {
                $res=(trim($user_values[$field['id']])!="") ? check_add_field_rule($field['check_rule'],$user_values[$field['id']],$field['name']) : true;
                if($res===true)
                {
                    $data['values'][$field['id']]=$user_values[$field['id']];
                }
                else
                {
                    $data['errors'][$field['id']]=$res;
                }
            }
        }
        else
        {
            $data['values'][$field['id']]="";
        }
    }
    return $data;        
}
/**
* Validate fields values
*
* @param array $data
* @return array
*/
function check_add_field($data)
{
    $errors=array();
    if(trim($data['title'])=="" || mb_strlen($data['title'])>64)
    {
        $errors[]="title";        
    }
    if(mb_strlen($data['descr'])>100)
    {
        $errors[]="description";        
    }
    
    if($data['type']==2||$data['type']==3||$data['type']==5||$data['type']==6)
    {
        $data['field_value']=explode("\n",$data['field_value']);
        
        if(count(preg_grep("/^$/",$data['field_value']))>0 || count($data['field_value'])!=count(array_unique($data['field_value'])))
        {
            $errors[]="field_values";
        }
        
        if(array_search($data['default_value'],$data['field_value'])===false && $data['default_value']!="")
        {
            $errors[]="default_value";        
        }
    }
    else
    {
        if($data['default_value']!="")
        {
            $res=check_add_field_rule($data['check_rule'],$data['default_value'],false);
            if($res!==true)
            {
                $errors[]=$res;
            }
        }        
    }
    return $errors;
}
/**
* Validate fields values
*
* @param unknown_type $rule
* @param string $value
* @param boolean $name
* @return string
*/
function check_add_field_rule($rule,$value,$name)
{
    switch (intval($rule))
    {
    case 2:
        // numbers only
        if( eregi("^[0-9]+$",$value) == false )
        { 
            if($name===false)
            {
                return "check_rule_numbers";
            }            
            return '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_numbers_only}>';    
        }
        // _numbers only            
        break;        
    case 3:
        // letters only
        if( eregi("^[a-zA-Z]+$",$value) == false )
        {
            if($name===false)
            {
                return "check_rule_letters";
            }            
            return '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_letters_only}>';
        }
        // _letters only            
        break;        
    case 4:
        // email
        if( eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$",$value) == false )
        {
            if($name===false)
            {
                return "check_rule_email";
            }            
            return '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_email}>';
        }
        // email
        break;        
    case 6:
        // phone
        if( eregi("^[-0-9+( )]+$",$value) == false )
        {
            if($name===false)
            {
                return "check_rule_phone";
            }            
            return '<{user_registration_add_fields_error_field_text}> '.output($name).' <{user_registration_add_fields_error_text_phone}>';
        }
        // phone
        break;
    }
    return true;
}
/**
* Enter description here...
*
* @param boolean $user_id
* @param boolean $field_id
* @param boolean $user_values
* @return string
*/
function get_user_add_fields_view($user_id=false,$field_id=false,$user_values=false)
{
    $res="";
    $CI = &get_instance();
    $fields=get_user_add_fields($user_id,$field_id); 
    foreach($fields as $field)
    {
        if($user_values!==false)
        {
            $field['user_value']="";
            if(isset($user_values[$field['field']['id']]))
            {                
                $field['user_value']=$user_values[$field['field']['id']];                    
            }
        }
        
        $data=array();
        $data['if_field']=(isset($field['field']) && is_array($field['field']) && count($field['field'])>0) ? array(array()) : array();
        $data['id_type']=intval($field['field']['id']).(intval($field['field']['type'])==3?"[]":"");
        $data['validation_errors']=array();
        if(isset($field['validation_error_text'])&&is_array($field['validation_error_text']))
        {
            foreach($field['validation_error_text'] as $key=>$value)
            {
                $data['validation_errors'][]=array('key'=>$key,'value'=>$value);           
            }
        }
        $data['name']=output($field['field']['name']);
        $data['if_required']= (intval($field['field']['req'])>0) ? array(array()) : array();
        $data['if_description'] = array();
        if(isset($field['field']['descr']) && !empty($field['field']['descr']))
        {
            $data['if_description'] = array(array());        
            $data['description']=soft_wrap(output($field['field']['descr']));
        }
        $data['id']=intval($field['field']['id']);
        $data['field_width']=isset($field['field_width'])?$field['field_width']:300;
        $data['validation_classes']=(isset($field['validation_classes'])&&is_array($field['validation_classes'])) ? implode(" ",$field['validation_classes']):"";
        $data['if_type_text'] = array();
        $data['if_type_select'] = array();
        $data['if_type_multiselect'] = array();
        $data['if_type_textarea'] = array();
        $data['if_type_radio'] = array();
        $data['if_type_checkbox'] = array();
        
        
        //$user_value=$field['user_value'];
        //$def_value=$field['field']['def_value'];
        switch (intval($field['field']['type'])) 
        {
            //if type is text
        case 1:
            $data['value']=output(isset($field['user_value']) ? ((is_array($field['user_value']) ? implode(", ",$field['user_value']) : $field['user_value'])) : $field['field']['def_value']);
            $data['if_type_text'] = array(array());
            break;
            //if type is select
        case 2:
            $data['field_width']+=10;
            $value=isset($field['user_value'])?(is_array($field['user_value']) ? $field['user_value'][0] : $field['user_value']) : $field['field']['def_value'];
            $vals=explode("\n",$field['field']['val']);
            $data['values']=array();
            foreach($vals as $val)
            {
                $data['values'][]=array(
                'selected'=>(($value==$val) ? "selected" : ""),
                'value'=>output($val)
                );
            }
            $data['if_type_select'] = array(array());
            break;
            //if type is multiselect
        case 3:
            $data['field_width']+=10;
            $value=isset($field['user_value'])?(is_array($field['user_value']) ? $field['user_value'] : explode("\n",$field['user_value'])) : explode(",",$field['field']['def_value']);
            $vals=explode("\n",$field['field']['val']);
            $data['size']=(sizeof($vals) < 9) ? sizeof($vals) : 9;
            $data['values']=array();
            foreach($vals as $val)
            {
                $data['values'][]=array(
                'selected'=>((in_array($val,$value)) ? "selected" : ""),
                'value'=>output($val)
                );
            }
            $data['if_type_multiselect'] = array(array());
            break;
            //if type is textarea
        case 4:
            $data['value']=output(isset($field['user_value']) ? ((is_array($field['user_value']) ? implode(", ",$field['user_value']) : $field['user_value'])) : $field['field']['def_value']);
            $data['if_type_textarea'] = array(array());
            break;
            //if type is radio
        case 5:
            $value=isset($field['user_value'])?(is_array($field['user_value']) ? $field['user_value'][0] : $user_values) : $field['field']['def_value'];
            $vals=explode("\n",$field['field']['val']);
            $data['values']=array();
            foreach($vals as $val)
            {
                $data['values'][]=array(
                'selected'=>(($value==$val) ? "checked" : ""),
                'value'=>output($val)
                );
            }
            $data['if_type_radio'] = array(array());
            break;
            //if type is checkbox
        case 6:
            $value=isset($field['user_value'])?(is_array($field['user_value']) ? $field['user_value'][0] : $field['user_value']) : $field['field']['def_value'];
            $data['value']=explode("\n",$field['field']['val']);
            $data['value']=$data['value'][0];
            $data['checked'] = ($value==$data['value']) ? "checked" : "";
            $data['if_type_checkbox'] = array(array());
            break;
        }
        fb($data,__line__);
        $res.=print_page("user/common/add_fields/field.html",$data,true);
    }
    return $res;
}
/**
* Enter description here...
*
* @param boolean $user_id
* @param boolean $field_id
* @param boolean $user_values
* @return string
*/
function get_member_add_fields_view($user_id=false,$field_id=false,$user_values=false)
{
    $res="";
    $CI = &get_instance();
    $fields=get_user_add_fields($user_id,false,$field_id);
    foreach($fields as $field)
    {
        if($user_values!==false)
        {
            $field['user_value']="";
            if(isset($user_values[$field['field']['id']]))
            {                
                $field['user_value']=$user_values[$field['field']['id']];                    
            }
        }
        //echo("<pre>");
        //print_r($field);
        //echo("</pre>");
        
        $res.=$CI->load->view("/admin/common/add_fields/field",$field,true);
    }
    return $res;
}
/**
* Enter description here...
*
* @param boolean $user_id
* @param boolean $field_id
* @return array
*/
function get_user_add_fields($user_id=false,$field_id=false)
{
    $CI = &get_instance();
    if($user_id)
    {
        $conditions=array();
        $conditions['user_id']=$user_id;
        if($field_id)
        {
            $conditions['field_id']=$field_id;            
        }
        $query = $CI->db->get_where(db_prefix."User_add_fields",$conditions);
        $user_values=$query->result_array();
    }
    //$CI->db->order_by("taborder");
    if($field_id)
    {
        $CI->db->where('id',$field_id);            
    }
    $query = $CI->db->get(db_prefix."Add_fields");
    
    $t=$query->result_array();
    $CI =& get_instance();        
    $CI->load->model("lang_manager_model"); 
    $t=$CI->lang_manager_model->combine_with_language_data($t,11,array('name'=>'name','descr'=>'descr'),'id',array('col'=>'taborder'),false,&$add_params);
    $add_fields=$t;
    
    //$add_fields=$query->result_array();
    
    $fields=array();
    foreach($add_fields as $field)
    {
        $data=array();
        if($user_id)
        {
            $data['user_value']="";
            foreach($user_values as $user_value)
            {
                if($user_value['field_id']==$field['id'])
                {
                    $data['user_value']=$user_value['field_value'];
                    break;
                }
            }
        }
        
        $data['validation_classes']=array();
        $data['validation_error_text']=array();
        if($field['req']>0)
        {
            $data['validation_classes'][]='qv_required';
            $data['validation_error_text']['qv_required']='<{user_registration_add_fields_error_field_text}> <{user_registration_add_fields_error_text_not_empty}>';
        }
        switch (intval($field['check_rule']))
        {
        case 2:
            $data['validation_classes'][]='qv_numbers';
            $data['validation_error_text']['qv_numbers']='<{user_registration_add_fields_error_field_text}> <{user_registration_add_fields_error_text_numbers_only}>';
            break;   
        case 4:
            $data['validation_classes'][]='qv_email';
            $data['validation_error_text']['qv_email']='<{user_registration_add_fields_error_field_text}> <{user_registration_add_fields_error_text_email}>';
            break;
        case 6:
            $data['validation_classes'][]='qv_phone';
            $data['validation_error_text']['qv_phone']='<{user_registration_add_fields_error_field_text}> <{user_registration_add_fields_error_text_phone}>';
            break;            
        }
        if(count($data['validation_classes'])>0)
        {
            $data['validation_classes'][]='quickvalidator';
        }        
        $data['field']=$field;        
        $fields[$field['id']]=$data;
    }
    return $fields;        
}
?>
