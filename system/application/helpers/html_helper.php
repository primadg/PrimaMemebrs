<?php
/**
 * 
 * THIS FILE CONTAINS HTML FUNCTIONS
 * 
 * @package Needsecure
 * @author uknown
 * @version uknown
 */
 function print_price($currency, $old, $new=0, $discount=false)
{
    $old=amount_to_print($old);
    $new=amount_to_print($new);

    if($new and $discount)
    {
        return "<div class='cena_b'><strike>$old</strike></div>
                <div class='cena_n'>&nbsp;$new</div>";
    }

    return "<div class='cena_b'>&nbsp;$old</div>";
}


function admin_print_fields($fields,$segment)
{
    ob_start();
    foreach($fields as $id=>$field)
    {
        if(is_array($field) && intval($field['enabled']))
        {
            if(isset($field['html']))
            {
                echo $field['html'];    
            }
            else
            {
                $width=(isset($field['width']) && $field['width'] ? $field['width'] : 300);
                $maxlength=255;
                $input_type=(isset($field['input_type']) ? $field['input_type'] : 'text');
                if(isset($field['length']) && is_array($field['length']))
                {
                    $maxlength=(isset($field['length']['limit']) && $field['length']['limit']) ? $field['length']['limit'] : $maxlength;
                    $maxlength=(isset($field['length']['max']) && $field['length']['max']) ? $field['length']['max'] : $maxlength;
                }
                echo isset($field['optional']) ? $field['optional'] : "";
                ?>
                <tr class="glav">
                <td align="right"><{<?php echo $segment?>_field_<?php echo $id?>}></td>
                <td>
                <?php  if((isset($field['obligate']) && $field['obligate']) || !isset($field['required']) || $field['required']){ ?>
                    <span style="color: red;">*</span>
                    <?php  } ?>
                </td>
                <td>
                <?php  
                switch($input_type){
                case 'phone':
				$code_id=isset($field['code_id']) ? $field['code_id'] : $id."cc";
				$code_value=isset($field['code_value']) ? $field['code_value'] :"";
				echo "<input type='text' style='width: 30px;' maxlength='3' name='".$code_id."' id='".$code_id."' value='".output($code_value)."' />&nbsp;&nbsp;&nbsp;<input type='text' value='".output(isset($field['value']) ? $field['value'] :"")."' name='".$id."' id='".$id."' style='width: ".$width."px;' maxlength='12' />";
				break;
                case 'select':
                echo "<select name='".$id."' id='".$id."' >";
                foreach($field['items'] as $k=>$v){
                    echo "<option ".($k==$field['value']?"selected":"")." value='".$k."' >".$v."</option>";
                }
                echo "</select>";
                break;
                case 'checkbox':
                echo "<input type='checkbox' ".(isset($field['value']) && intval($field['value']) ?"checked":"")." value='1' name='".$id."' id='".$id."' />";
                    break;
                case 'label':
                    echo "<span name='".$id."' id='".$id."' style='width: ".$width."px;'>".output(isset($field['value']) ? $field['value'] :"")."</span>";
                    break;
                default:
                    echo "<input type='".$input_type."' value='".output(isset($field['value']) ? $field['value'] :"")."' name='".$id."' id='".$id."' style='width: ".$width."px;' maxlength='".$maxlength."' />";
                    break;
                } 
                ?>
                </td>
                <td></td>
                </tr>
                <?php  
                if(isset($field['retype']) && intval($field['retype'])) 
                {
                    ?>
                    <tr class="glav">
                    <td align="right"><{<?php echo $segment?>_field_<?php echo $id?>_retype}></td>
                    <td><span style="color: red;">*</span></td>
                    <td><input type="<?php echo $input_type?>" value="<?php echo isset($field['value_retype']) ? $field['value_retype'] :"";?>"  name="<?php echo $id?>_retype" id="<?php echo $id?>_retype" style="width: <?php echo $width?>px;" maxlength="<?php echo $maxlength?>" /></td>
                    <td></td>
                    </tr>
                    <?php 
                }
            }
        }
    }
    return ob_get_clean();
}

function print_ex($a)
{
    echo "<pre>";
    print_r($a);
    echo "</pre>";
}

/**
 * Create table
 *
 * @param string $header
 * @param array $rows
 * @param integer $width
 * @param boolean $class
 */
function create_tab_table($header,$rows,$width=700,$class=false)
{
    ?>
    <div class="subject"><?php echo $header?></div>
    <table class="tab" align="center" width="<?php echo $width?>">
    <?php 
    $CI=&get_instance();
    if(isset($rows)&&is_array($rows))
    {
        $flag=true;
        foreach($rows as $row)
        {				
            $name=isset($row['name'])?$row['name']:"";
            $value=isset($row['value'])?$row['value']:"";
            if(isset($row['link'])&&$row['link']!=false && !$CI->admin_auth_model->isAccessDenied(MEMBER_CONTROL)!==false)
            {
                $value='<a href="#" onclick="'.$row['link'].'return false;">'.$value.'</a>';
            }
            $column_class=isset($class)&&$class!=false?"class=\"".output($class)."\"":"";
            
            ?>
            <tr class="<?php echo $flag?"light":"dark";$flag=!$flag;?>">
            <td><?php echo $name?></td>
            <td <?php echo $column_class?>><?php echo $value?></td>
            </tr>                
            <?php
        }
    }
    ?>
    </table>
    <?php 
}
/**
 * Create form with hidden files and submit buuton 
 *
 * @param string $action
 * @param string $name
 * @param array $params
 * @param string $method
 * @return string
 */
function submit_button($action,$name,$params=array(),$method="POST")
{
    $s="<form action='".$action."' method='".$method."'>";
    foreach($params as $key=>$value)
    {
        $s.="<input type='hidden' name='".$key."' value='".$value."' class='button'/>";
    }
    $s.="<input type='submit' value='".$name."' class='button'/>";
    $s.="</form>";
    return $s;
}

//Draw sort arrow
/**
 * Draw sort arrow
 *
 * @param string $sort_by
 * @param string $order_by
 * @param unknown_type $column
 * @param unknown_type $is_default
 * @return unknown
 */
function draw_arrow($sort_by, $order_by, $column, $is_default=false)
{
    return ($sort_by==$column || ($is_default && $sort_by=='')) ? (strtolower($order_by)=='desc' ? "&nbsp;&#9660;" : "&nbsp;&#9650;") : "";
}

//php array to javascript array ({key:value},{key:value});
/**
 * php array to javascript array ({key:value},{key:value});
 *
 * @param array $vars_arr
 * @return string
 */
function create_temp_vars_set1($vars_arr)
{
    $str="";
    if(isset($vars_arr)&&is_array($vars_arr)&&sizeof($vars_arr)>0)
    {
        $flag=false;
        $str.="{";
        foreach( $vars_arr as $key=>$value)
        {
            $str.=($flag?",":"")."'".$key."':'".$value."'";
            $flag=true;
        }
        $str.="}";
    }
    return $str;
}

/**
 * Enter description here...
 *
 * @param array $vars_arr
 * @param boolean $is_base64_value
 * @param boolean $is_recursive
 * @return string
 */
function create_temp_vars_set($vars_arr,$is_base64_value=false,$is_recursive=false)
{
    $str="";
    if(isset($vars_arr)&&is_array($vars_arr))
    {
        $result=array();
        foreach($vars_arr as $key=>$value)
        {
            if(is_array($value))
            {
                $result[]="'".$key."':".create_temp_vars_set($value,$is_base64_value,true);
            }
            else
            {
                $result[]="'".$key."':'".($is_base64_value? base64_encode(replace_lang($value)) : $value)."'";
            }
        }
        $str=(!$is_recursive && $is_base64_value ? "base64" : "")."{".implode(",",$result)."}";
    }
    return $str;
}
/**
 * Convert array to html list
 *
 * @param array $arr
 * @return string
 */
function array_to_html_list($arr)
{
    //print_r($arr);
    $r="<ul style='list-style-type:none'>";
    foreach($arr as $k=>$v)
    {
        $r.="<li><span style='font-weight: bold;'>".$k."</span>: ".(is_array($v) ? array_to_html_list($v) : htmlspecialchars(html_entity_decode($v,ENT_QUOTES,'UTF-8'), ENT_NOQUOTES))."<li>";
    }
    $r.="</ul>";
    return $r;
}


/**
* Returns CSS class definition for fucking date-picker based on config_get('system','config','date_format') format
*
* @return mixed string/timestamp
*
* @author Val Petruchek
* @copyright 2008
*/
function datepicker_class()
{
    $format = config_get('system','config','date_format');
    if (!$format) $format = "m/d/y";
    $format4dp = str_replace($format{1},"-",$format);
    $dividers = array('.'=>'dot','/'=>'slash','-'=>'dash');
    return "format-".strtolower($format4dp)." highlight-days-67 divider-".$dividers[$format{1}]." no-transparency realformat-$format4dp";
}
/**
 * Redirect to page
 *
 * @param string $title
 * @param string $url
 * @return true
 */
function redirect_page($title,$url)
{
    $url = !empty($url) ? output(site_url($url)) : base_url();
    print_page('user/redirect.html',array('title'=>$title,'url'=>$url));
    //_view('user/redirect',array('title'=>$title,'url'=>site_url($url)));
    header("Refresh: 0; url=".$url."");
    return true;
}
/**
 * Enter description here...
 *
 * @param unknown_type $class
 * @param unknown_type $keys
 * @return unknown
 */
function admin_print_msg_box($class='',$keys='')
{
    return print_msg_box($class,$keys,'admin');
}
/**
 * Get counties
 *
 * @return array
 */
function get_countries()
{
    $countries = array();
    $countries['AD'] = '<{country_AD}>';// Andorra
    $countries['AE'] = '<{country_AE}>';// United Arab Emirates
    $countries['AF'] = '<{country_AF}>';// Afghanistan
    $countries['AG'] = '<{country_AG}>';// Antigua and Barbuda
    $countries['AI'] = '<{country_AI}>';// Anguilla
    $countries['AL'] = '<{country_AL}>';// Albania
    $countries['AM'] = '<{country_AM}>';// Armenia
    $countries['AN'] = '<{country_AN}>';// Netherlands Antilles
    $countries['AO'] = '<{country_AO}>';// Angola
    $countries['AR'] = '<{country_AR}>';// Argentina
    $countries['AS'] = '<{country_AS}>';// American Samoa
    $countries['AT'] = '<{country_AT}>';// Austria
    $countries['AU'] = '<{country_AU}>';// Australia
    $countries['AW'] = '<{country_AW}>';// Aruba
    $countries['AZ'] = '<{country_AZ}>';// Azerbaijan Republic
    $countries['BA'] = '<{country_BA}>';// Bosnia and Herzegovina
    $countries['BB'] = '<{country_BB}>';// Barbados
    $countries['BD'] = '<{country_BD}>';// Bangladesh
    $countries['BE'] = '<{country_BE}>';// Belgium
    $countries['BF'] = '<{country_BF}>';// Burkina Faso
    $countries['BG'] = '<{country_BG}>';// Bulgaria
    $countries['BH'] = '<{country_BH}>';// Bahrain
    $countries['BI'] = '<{country_BI}>';// Burundi
    $countries['BJ'] = '<{country_BJ}>';// Benin
    $countries['BM'] = '<{country_BM}>';// Bermuda
    $countries['BN'] = '<{country_BN}>';// Brunei
    $countries['BO'] = '<{country_BO}>';// Bolivia
    $countries['BR'] = '<{country_BR}>';// Brazil
    $countries['BS'] = '<{country_BS}>';// Bahamas
    $countries['BT'] = '<{country_BT}>';// Bhutan
    $countries['BW'] = '<{country_BW}>';// Botswana
    $countries['BY'] = '<{country_BY}>';// Belarus
    $countries['BZ'] = '<{country_BZ}>';// Belize
    $countries['CA'] = '<{country_CA}>';// Canada
    $countries['CD'] = '<{country_CD}>';// Democratic Republic of the Congo
    $countries['CF'] = '<{country_CF}>';// Central African Republic
    $countries['CG'] = '<{country_CG}>';// Republic of the Congo
    $countries['CH'] = '<{country_CH}>';// Switzerland
    $countries['CI'] = '<{country_CI}>';// Cote d'Ivoire
    $countries['CK'] = '<{country_CK}>';// Cook Islands
    $countries['CL'] = '<{country_CL}>';// Chile
    $countries['CM'] = '<{country_CM}>';// Cameroon
    $countries['CN'] = '<{country_CN}>';// China
    $countries['CO'] = '<{country_CO}>';// Colombia
    $countries['CR'] = '<{country_CR}>';// Costa Rica
    $countries['CU'] = '<{country_CU}>';// Cuba
    $countries['CV'] = '<{country_CV}>';// Cape Verde
    $countries['CY'] = '<{country_CY}>';// Cyprus
    $countries['CZ'] = '<{country_CZ}>';// Czech Republic
    $countries['DE'] = '<{country_DE}>';// Germany
    $countries['DJ'] = '<{country_DJ}>';// Djibouti
    $countries['DK'] = '<{country_DK}>';// Denmark
    $countries['DM'] = '<{country_DM}>';// Dominica
    $countries['DO'] = '<{country_DO}>';// Dominican Republic
    $countries['DZ'] = '<{country_DZ}>';// Algeria
    $countries['EC'] = '<{country_EC}>';// Ecuador
    $countries['EE'] = '<{country_EE}>';// Estonia
    $countries['EG'] = '<{country_EG}>';// Egypt
    $countries['EH'] = '<{country_EH}>';// Western Sahara
    $countries['ER'] = '<{country_ER}>';// Eritrea
    $countries['ES'] = '<{country_ES}>';// Spain
    $countries['ET'] = '<{country_ET}>';// Ethiopia
    $countries['FI'] = '<{country_FI}>';// Finland
    $countries['FJ'] = '<{country_FJ}>';// Fiji
    $countries['FK'] = '<{country_FK}>';// Falkland Islands
    $countries['FM'] = '<{country_FM}>';// Federated States of Micronesia
    $countries['FO'] = '<{country_FO}>';// Faroe Islands
    $countries['FR'] = '<{country_FR}>';// France
    $countries['GA'] = '<{country_GA}>';// Gabon Republic
    $countries['GB'] = '<{country_GB}>';// United Kingdom
    $countries['GD'] = '<{country_GD}>';// Grenada
    $countries['GE'] = '<{country_GE}>';// Georgia
    $countries['GF'] = '<{country_GF}>';// French Guiana
    $countries['GH'] = '<{country_GH}>';// Ghana
    $countries['GI'] = '<{country_GI}>';// Gibraltar
    $countries['GL'] = '<{country_GL}>';// Greenland
    $countries['GM'] = '<{country_GM}>';// Gambia
    $countries['GN'] = '<{country_GN}>';// Guinea
    $countries['GP'] = '<{country_GP}>';// Guadeloupe
    $countries['GQ'] = '<{country_GQ}>';// Equatorial Guinea
    $countries['GR'] = '<{country_GR}>';// Greece
    $countries['GT'] = '<{country_GT}>';// Guatemala
    $countries['GU'] = '<{country_GU}>';// Guam
    $countries['GW'] = '<{country_GW}>';// Guinea Bissau
    $countries['GY'] = '<{country_GY}>';// Guyana
    $countries['HK'] = '<{country_HK}>';// Hong Kong
    $countries['HN'] = '<{country_HN}>';// Honduras
    $countries['HR'] = '<{country_HR}>';// Croatia
    $countries['HT'] = '<{country_HT}>';// Haiti
    $countries['HU'] = '<{country_HU}>';// Hungary
    $countries['ID'] = '<{country_ID}>';// Indonesia
    $countries['IE'] = '<{country_IE}>';// Ireland
    $countries['IL'] = '<{country_IL}>';// Israel
    $countries['IN'] = '<{country_IN}>';// India
    $countries['IQ'] = '<{country_IQ}>';// Iraq
    $countries['IR'] = '<{country_IR}>';// Iran
    $countries['IS'] = '<{country_IS}>';// Iceland
    $countries['IT'] = '<{country_IT}>';// Italy
    $countries['JM'] = '<{country_JM}>';// Jamaica
    $countries['JO'] = '<{country_JO}>';// Jordan
    $countries['JP'] = '<{country_JP}>';// Japan
    $countries['KE'] = '<{country_KE}>';// Kenya
    $countries['KG'] = '<{country_KG}>';// Kyrgyzstan
    $countries['KH'] = '<{country_KH}>';// Cambodia
    $countries['KI'] = '<{country_KI}>';// Kiribati
    $countries['KM'] = '<{country_KM}>';// Comoros
    $countries['KN'] = '<{country_KN}>';// St. Kitts and Nevis
    $countries['KP'] = '<{country_KP}>';// Korea, Democratic People's Republic of
    $countries['KR'] = '<{country_KR}>';// South Korea
    $countries['KW'] = '<{country_KW}>';// Kuwait
    $countries['KY'] = '<{country_KY}>';// Cayman Islands
    $countries['KZ'] = '<{country_KZ}>';// Kazakhstan
    $countries['LA'] = '<{country_LA}>';// Laos
    $countries['LB'] = '<{country_LB}>';// Lebanon
    $countries['LC'] = '<{country_LC}>';// St. Lucia
    $countries['LI'] = '<{country_LI}>';// Liechtenstein
    $countries['LK'] = '<{country_LK}>';// Sri Lanka
    $countries['LR'] = '<{country_LR}>';// Liberia
    $countries['LS'] = '<{country_LS}>';// Lesotho
    $countries['LT'] = '<{country_LT}>';// Lithuania
    $countries['LU'] = '<{country_LU}>';// Luxembourg
    $countries['LV'] = '<{country_LV}>';// Latvia
    $countries['LY'] = '<{country_LY}>';// Libyan Arab Jamahiriya
    $countries['MA'] = '<{country_MA}>';// Morocco
    $countries['MC'] = '<{country_MC}>';// Monaco
    $countries['MD'] = '<{country_MD}>';// Moldova
    $countries['MG'] = '<{country_MG}>';// Madagascar
    $countries['MH'] = '<{country_MH}>';// Marshall Islands
    $countries['MK'] = '<{country_MK}>';// Macedonia
    $countries['ML'] = '<{country_ML}>';// Mali
    $countries['MM'] = '<{country_MM}>';// Myanmar
    $countries['MN'] = '<{country_MN}>';// Mongolia
    $countries['MO'] = '<{country_MO}>';// Macao
    $countries['MP'] = '<{country_MP}>';// Northern Mariana Islands
    $countries['MQ'] = '<{country_MQ}>';// Martinique
    $countries['MR'] = '<{country_MR}>';// Mauritania
    $countries['MS'] = '<{country_MS}>';// Montserrat
    $countries['MT'] = '<{country_MT}>';// Malta
    $countries['MU'] = '<{country_MU}>';// Mauritius
    $countries['MV'] = '<{country_MV}>';// Maldives
    $countries['MW'] = '<{country_MW}>';// Malawi
    $countries['MX'] = '<{country_MX}>';// Mexico
    $countries['MY'] = '<{country_MY}>';// Malaysia
    $countries['MZ'] = '<{country_MZ}>';// Mozambique
    $countries['NA'] = '<{country_NA}>';// Namibia
    $countries['NC'] = '<{country_NC}>';// New Caledonia
    $countries['NE'] = '<{country_NE}>';// Niger
    $countries['NF'] = '<{country_NF}>';// Norfolk Island
    $countries['NG'] = '<{country_NG}>';// Nigeria
    $countries['NI'] = '<{country_NI}>';// Nicaragua
    $countries['NL'] = '<{country_NL}>';// Netherlands
    $countries['NO'] = '<{country_NO}>';// Norway
    $countries['NP'] = '<{country_NP}>';// Nepal
    $countries['NR'] = '<{country_NR}>';// Nauru
    $countries['NU'] = '<{country_NU}>';// Niue
    $countries['NZ'] = '<{country_NZ}>';// New Zealand
    $countries['OM'] = '<{country_OM}>';// Oman
    $countries['PA'] = '<{country_PA}>';// Panama
    $countries['PE'] = '<{country_PE}>';// Peru
    $countries['PF'] = '<{country_PF}>';// French Polynesia
    $countries['PG'] = '<{country_PG}>';// Papua New Guinea
    $countries['PH'] = '<{country_PH}>';// Philippines
    $countries['PK'] = '<{country_PK}>';// Pakistan
    $countries['PL'] = '<{country_PL}>';// Poland
    $countries['PM'] = '<{country_PM}>';// St. Pierre and Miquelon
    $countries['PN'] = '<{country_PN}>';// Pitcairn Islands
    $countries['PR'] = '<{country_PR}>';// Puerto Rico
    $countries['PS'] = '<{country_PS}>';// Palestinian Territory
    $countries['PT'] = '<{country_PT}>';// Portugal
    $countries['PW'] = '<{country_PW}>';// Palau
    $countries['PY'] = '<{country_PY}>';// Paraguay
    $countries['QA'] = '<{country_QA}>';// Qatar
    $countries['RE'] = '<{country_RE}>';// Reunion
    $countries['RO'] = '<{country_RO}>';// Romania
    $countries['RS'] = '<{country_RS}>';// Serbia
    $countries['RU'] = '<{country_RU}>';// Russia
    $countries['RW'] = '<{country_RW}>';// Rwanda
    $countries['SA'] = '<{country_SA}>';// Saudi Arabia
    $countries['SB'] = '<{country_SB}>';// Solomon Islands
    $countries['SC'] = '<{country_SC}>';// Seychelles
    $countries['SD'] = '<{country_SD}>';// Sudan
    $countries['SE'] = '<{country_SE}>';// Sweden
    $countries['SG'] = '<{country_SG}>';// Singapore
    $countries['SH'] = '<{country_SH}>';// St. Helena
    $countries['SI'] = '<{country_SI}>';// Slovenia
    $countries['SJ'] = '<{country_SJ}>';// Svalbard and Jan Mayen Islands
    $countries['SK'] = '<{country_SK}>';// Slovakia
    $countries['SL'] = '<{country_SL}>';// Sierra Leone
    $countries['SM'] = '<{country_SM}>';// San Marino
    $countries['SN'] = '<{country_SN}>';// Senegal
    $countries['SO'] = '<{country_SO}>';// Somalia
    $countries['SR'] = '<{country_SR}>';// Suriname
    $countries['ST'] = '<{country_ST}>';// Sao Tome and Principe
    $countries['SV'] = '<{country_SV}>';// El Salvador
    $countries['SY'] = '<{country_SY}>';// Syrian Arab Republic
    $countries['SZ'] = '<{country_SZ}>';// Swaziland
    $countries['TC'] = '<{country_TC}>';// Turks and Caicos Islands
    $countries['TD'] = '<{country_TD}>';// Chad
    $countries['TG'] = '<{country_TG}>';// Togo
    $countries['TH'] = '<{country_TH}>';// Thailand
    $countries['TJ'] = '<{country_TJ}>';// Tajikistan
    $countries['TK'] = '<{country_TK}>';// Tokelau
    $countries['TL'] = '<{country_TL}>';// Timor-Leste
    $countries['TM'] = '<{country_TM}>';// Turkmenistan
    $countries['TN'] = '<{country_TN}>';// Tunisia
    $countries['TO'] = '<{country_TO}>';// Tonga
    $countries['TR'] = '<{country_TR}>';// Turkey
    $countries['TT'] = '<{country_TT}>';// Trinidad and Tobago
    $countries['TV'] = '<{country_TV}>';// Tuvalu
    $countries['TW'] = '<{country_TW}>';// Taiwan
    $countries['TZ'] = '<{country_TZ}>';// Tanzania
    $countries['UA'] = '<{country_UA}>';// Ukraine
    $countries['UG'] = '<{country_UG}>';// Uganda
    $countries['US'] = '<{country_US}>';// United States of America
    $countries['UY'] = '<{country_UY}>';// Uruguay
    $countries['UZ'] = '<{country_UZ}>';// Uzbekistan
    $countries['VA'] = '<{country_VA}>';// Vatican City State
    $countries['VC'] = '<{country_VC}>';// Saint Vincent and the Grenadines
    $countries['VE'] = '<{country_VE}>';// Venezuela
    $countries['VG'] = '<{country_VG}>';// Virgin Islands, British
    $countries['VI'] = '<{country_VI}>';// Virgin Islands, U.S.
    $countries['VN'] = '<{country_VN}>';// Vietnam
    $countries['VU'] = '<{country_VU}>';// Vanuatu
    $countries['WF'] = '<{country_WF}>';// Wallis and Futuna Islands
    $countries['WS'] = '<{country_WS}>';// Samoa
    $countries['YE'] = '<{country_YE}>';// Yemen
    $countries['YT'] = '<{country_YT}>';// Mayotte
    $countries['ZA'] = '<{country_ZA}>';// South Africa
    $countries['ZM'] = '<{country_ZM}>';// Zambia
    $countries['ZW'] = '<{country_ZW}>';// Zimbabwe
    return $countries;
}
/**
 * Enter description here
 *
 */
function lang_list_alt()
{
    //error_reporting(E_ALL);
    $languages=array();
    $languages['AA']='Afar';
    $languages['AB']='Abkhazian';
    $languages['AF']='Afrikaans';
    $languages['AM']='Amharic';
    $languages['AR']='Arabic';
    $languages['AS']='Assamese';
    $languages['AY']='Aymara';
    $languages['AZ']='Azerbaijani';
    $languages['BA']='Bashkir';
    $languages['BE']='Byelorussian';
    $languages['BG']='Bulgarian';
    $languages['BH']='Bihari';
    $languages['BI']='Bislama';
    $languages['BN']='Bengali Bangla';
    $languages['BO']='Tibetan';
    $languages['BR']='Breton';
    $languages['CA']='Catalan';
    $languages['CO']='Corsican';
    $languages['CS']='Czech';
    $languages['CY']='Welsh';
    $languages['DA']='Danish';
    $languages['DE']='German';
    $languages['DZ']='Bhutani';
    $languages['EL']='Greek';
    $languages['EN']='English American';
    $languages['EO']='Esperanto';
    $languages['ES']='Spanish';
    $languages['ET']='Estonian';
    $languages['EU']='Basque';
    $languages['FA']='Persian';
    $languages['FI']='Finnish';
    $languages['FJ']='Fiji';
    $languages['FO']='Faeroese';
    $languages['FR']='French';
    $languages['FY']='Frisian';
    $languages['GA']='Irish';
    $languages['GD']='Gaelic Scots Gaelic';
    $languages['GL']='Galician';
    $languages['GN']='Guarani';
    $languages['GU']='Gujarati';
    $languages['HA']='Hausa';
    $languages['HI']='Hindi';
    $languages['HR']='Croatian';
    $languages['HU']='Hungarian';
    $languages['HY']='Armenian';
    $languages['IA']='Interlingua';
    $languages['IE']='Interlingue';
    $languages['IK']='Inupiak';
    $languages['IN']='Indonesian';
    $languages['IS']='Icelandic';
    $languages['IT']='Italian';
    $languages['IW']='Hebrew';
    $languages['JA']='Japanese';
    $languages['JI']='Yiddish';
    $languages['JW']='Javanese';
    $languages['KA']='Georgian';
    $languages['KK']='Kazakh';
    $languages['KL']='Greenlandic';
    $languages['KM']='Cambodian';
    $languages['KN']='Kannada';
    $languages['KO']='Korean';
    $languages['KS']='Kashmiri';
    $languages['KU']='Kurdish';
    $languages['KY']='Kirghiz';
    $languages['LA']='Latin';
    $languages['LN']='Lingala';
    $languages['LO']='Laothian';
    $languages['LT']='Lithuanian';
    $languages['LV']='Latvian Lettish';
    $languages['MG']='Malagasy';
    $languages['MI']='Maori';
    $languages['MK']='Macedonian';
    $languages['ML']='Malayalam';
    $languages['MN']='Mongolian';
    $languages['MO']='Moldavian';
    $languages['MR']='Marathi';
    $languages['MS']='Malay';
    $languages['MT']='Maltese';
    $languages['MY']='Burmese';
    $languages['NA']='Nauru';
    $languages['NE']='Nepali';
    $languages['NL']='Dutch';
    $languages['NO']='Norwegian';
    $languages['OC']='Occitan';
    $languages['OM']='Oromo Afan';
    $languages['OR']='Oriya';
    $languages['PA']='Punjabi';
    $languages['PL']='Polish';
    $languages['PS']='Pashto Pushto';
    $languages['PT']='Portuguese';
    $languages['QU']='Quechua';
    $languages['RM']='Rhaeto-Romance';
    $languages['RN']='Kirundi';
    $languages['RO']='Romanian';
    $languages['RU']='Russian';
    $languages['RW']='Kinyarwanda';
    $languages['SA']='Sanskrit';
    $languages['SD']='Sindhi';
    $languages['SG']='Sangro';
    $languages['SH']='Serbo-Croatian';
    $languages['SI']='Singhalese';
    $languages['SK']='Slovak';
    $languages['SL']='Slovenian';
    $languages['SM']='Samoan';
    $languages['SN']='Shona';
    $languages['SO']='Somali';
    $languages['SQ']='Albanian';
    $languages['SR']='Serbian';
    $languages['SS']='Siswati';
    $languages['ST']='Sesotho';
    $languages['SU']='Sudanese';
    $languages['SV']='Swedish';
    $languages['SW']='Swahili';
    $languages['TA']='Tamil';
    $languages['TE']='Tegulu';
    $languages['TG']='Tajik';
    $languages['TH']='Thai';
    $languages['TI']='Tigrinya';
    $languages['TK']='Turkmen';
    $languages['TL']='Tagalog';
    $languages['TN']='Setswana';
    $languages['TO']='Tonga';
    $languages['TR']='Turkish';
    $languages['TS']='Tsonga';
    $languages['TT']='Tatar';
    $languages['TW']='Twi';
    $languages['UK']='Ukrainian';
    $languages['UR']='Urdu';
    $languages['UZ']='Uzbek';
    $languages['VI']='Vietnamese';
    $languages['VO']='Volapuk';
    $languages['WO']='Wolof';
    $languages['XH']='Xhosa';
    $languages['YO']='Yoruba';
    $languages['ZH']='Chinese';
    $languages['ZU']='Zulu';

    $countries=array();
    $countries["AD"]="Andorra";
    $countries["AE"]="United Arab Emirates";
    $countries["AF"]="Afghanistan";
    $countries["AG"]="Antigua and Barbuda";
    $countries["AI"]="Anguilla";
    $countries["AL"]="Albania";
    $countries["AM"]="Armenia";
    $countries["AN"]="Netherlands Antilles";
    $countries["AO"]="Angola";
    $countries["AQ"]="Antarctica";
    $countries["AR"]="Argentina";
    $countries["AS"]="American Samoa";
    $countries["AT"]="Austria";
    $countries["AU"]="Australia";
    $countries["AW"]="Aruba";
    $countries["AX"]="Aland Islands Aland Islands";
    $countries["AZ"]="Azerbaijan";
    $countries["BA"]="Bosnia and Herzegovina";
    $countries["BB"]="Barbados";
    $countries["BD"]="Bangladesh";
    $countries["BE"]="Belgium";
    $countries["BF"]="Burkina Faso";
    $countries["BG"]="Bulgaria";
    $countries["BH"]="Bahrain";
    $countries["BI"]="Burundi";
    $countries["BJ"]="Benin";
    $countries["BL"]="Saint Barthelemy";
    $countries["BM"]="Bermuda";
    $countries["BN"]="Brunei Darussalam";
    $countries["BO"]="Bolivia";
    $countries["BR"]="Brazil";
    $countries["BS"]="Bahamas";
    $countries["BT"]="Bhutan";
    $countries["BV"]="Bouvet Island";
    $countries["BW"]="Botswana";
    $countries["BY"]="Belarus";
    $countries["BZ"]="Belize";
    $countries["CA"]="Canada";
    $countries["CC"]="Cocos (Keeling) Islands";
    $countries["CD"]="Congo, the Democratic Republic of the";
    $countries["CF"]="Central African Republic";
    $countries["CG"]="Congo";
    $countries["CH"]="Switzerland";
    $countries["CI"]="Cote d'Ivoire Cote d'Ivoire";
    $countries["CK"]="Cook Islands";
    $countries["CL"]="Chile";
    $countries["CM"]="Cameroon";
    $countries["CN"]="China";
    $countries["CO"]="Colombia";
    $countries["CR"]="Costa Rica";
    $countries["CU"]="Cuba";
    $countries["CV"]="Cape Verde";
    $countries["CX"]="Christmas Island";
    $countries["CY"]="Cyprus";
    $countries["CZ"]="Czech Republic";
    $countries["DE"]="Germany";
    $countries["DJ"]="Djibouti";
    $countries["DK"]="Denmark";
    $countries["DM"]="Dominica";
    $countries["DO"]="Dominican Republic";
    $countries["DZ"]="Algeria";
    $countries["EC"]="Ecuador";
    $countries["EE"]="Estonia";
    $countries["EG"]="Egypt";
    $countries["EH"]="Western Sahara";
    $countries["ER"]="Eritrea";
    $countries["ES"]="Spain";
    $countries["ET"]="Ethiopia";
    $countries["FI"]="Finland";
    $countries["FJ"]="Fiji";
    $countries["FK"]="Falkland Islands (Malvinas)";
    $countries["FM"]="Micronesia, Federated States of";
    $countries["FO"]="Faroe Islands";
    $countries["FR"]="France";
    $countries["GA"]="Gabon";
    $countries["GB"]="United Kingdom";
    $countries["GD"]="Grenada";
    $countries["GE"]="Georgia";
    $countries["GF"]="French Guiana";
    $countries["GG"]="Guernsey";
    $countries["GH"]="Ghana";
    $countries["GI"]="Gibraltar";
    $countries["GL"]="Greenland";
    $countries["GM"]="Gambia";
    $countries["GN"]="Guinea";
    $countries["GP"]="Guadeloupe";
    $countries["GQ"]="Equatorial Guinea";
    $countries["GR"]="Greece";
    $countries["GS"]="South Georgia and the South Sandwich Islands";
    $countries["GT"]="Guatemala";
    $countries["GU"]="Guam";
    $countries["GW"]="Guinea-Bissau";
    $countries["GY"]="Guyana";
    $countries["HK"]="Hong Kong";
    $countries["HM"]="Heard Island and McDonald Islands";
    $countries["HN"]="Honduras";
    $countries["HR"]="Croatia";
    $countries["HT"]="Haiti";
    $countries["HU"]="Hungary";
    $countries["ID"]="Indonesia";
    $countries["IE"]="Ireland";
    $countries["IL"]="Israel";
    $countries["IM"]="Isle of Man";
    $countries["IN"]="India";
    $countries["IO"]="British Indian Ocean Territory";
    $countries["IQ"]="Iraq";
    $countries["IR"]="Iran, Islamic Republic of";
    $countries["IS"]="Iceland";
    $countries["IT"]="Italy";
    $countries["JE"]="Jersey";
    $countries["JM"]="Jamaica";
    $countries["JO"]="Jordan";
    $countries["JP"]="Japan";
    $countries["KE"]="Kenya";
    $countries["KG"]="Kyrgyzstan";
    $countries["KH"]="Cambodia";
    $countries["KI"]="Kiribati";
    $countries["KM"]="Comoros";
    $countries["KN"]="Saint Kitts and Nevis";
    $countries["KP"]="Korea, Democratic People's Republic of";
    $countries["KR"]="Korea, Republic of";
    $countries["KW"]="Kuwait";
    $countries["KY"]="Cayman Islands";
    $countries["KZ"]="Kazakhstan";
    $countries["LA"]="Lao People's Democratic Republic";
    $countries["LB"]="Lebanon";
    $countries["LC"]="Saint Lucia";
    $countries["LI"]="Liechtenstein";
    $countries["LK"]="Sri Lanka";
    $countries["LR"]="Liberia";
    $countries["LS"]="Lesotho";
    $countries["LT"]="Lithuania";
    $countries["LU"]="Luxembourg";
    $countries["LV"]="Latvia";
    $countries["LY"]="Libyan Arab Jamahiriya";
    $countries["MA"]="Morocco";
    $countries["MC"]="Monaco";
    $countries["MD"]="Moldova, Republic of";
    $countries["ME"]="Montenegro";
    $countries["MF"]="Saint Martin (French part)";
    $countries["MG"]="Madagascar";
    $countries["MH"]="Marshall Islands";
    $countries["MK"]="Macedonia, the former Yugoslav Republic of";
    $countries["ML"]="Mali";
    $countries["MM"]="Myanmar";
    $countries["MN"]="Mongolia";
    $countries["MO"]="Macao";
    $countries["MP"]="Northern Mariana Islands";
    $countries["MQ"]="Martinique";
    $countries["MR"]="Mauritania";
    $countries["MS"]="Montserrat";
    $countries["MT"]="Malta";
    $countries["MU"]="Mauritius";
    $countries["MV"]="Maldives";
    $countries["MW"]="Malawi";
    $countries["MX"]="Mexico";
    $countries["MY"]="Malaysia";
    $countries["MZ"]="Mozambique";
    $countries["NA"]="Namibia";
    $countries["NC"]="New Caledonia";
    $countries["NE"]="Niger";
    $countries["NF"]="Norfolk Island";
    $countries["NG"]="Nigeria";
    $countries["NI"]="Nicaragua";
    $countries["NL"]="Netherlands";
    $countries["NO"]="Norway";
    $countries["NP"]="Nepal";
    $countries["NR"]="Nauru";
    $countries["NU"]="Niue";
    $countries["NZ"]="New Zealand";
    $countries["OM"]="Oman";
    $countries["PA"]="Panama";
    $countries["PE"]="Peru";
    $countries["PF"]="French Polynesia";
    $countries["PG"]="Papua New Guinea";
    $countries["PH"]="Philippines";
    $countries["PK"]="Pakistan";
    $countries["PL"]="Poland";
    $countries["PM"]="Saint Pierre and Miquelon";
    $countries["PN"]="Pitcairn";
    $countries["PR"]="Puerto Rico";
    $countries["PS"]="Palestinian Territory, Occupied";
    $countries["PT"]="Portugal";
    $countries["PW"]="Palau";
    $countries["PY"]="Paraguay";
    $countries["QA"]="Qatar";
    $countries["RE"]="Reunion Reunion";
    $countries["RO"]="Romania";
    $countries["RS"]="Serbia";
    $countries["RU"]="Russian Federation";
    $countries["RW"]="Rwanda";
    $countries["SA"]="Saudi Arabia";
    $countries["SB"]="Solomon Islands";
    $countries["SC"]="Seychelles";
    $countries["SD"]="Sudan";
    $countries["SE"]="Sweden";
    $countries["SG"]="Singapore";
    $countries["SH"]="Saint Helena";
    $countries["SI"]="Slovenia";
    $countries["SJ"]="Svalbard and Jan Mayen";
    $countries["SK"]="Slovakia";
    $countries["SL"]="Sierra Leone";
    $countries["SM"]="San Marino";
    $countries["SN"]="Senegal";
    $countries["SO"]="Somalia";
    $countries["SR"]="Suriname";
    $countries["ST"]="Sao Tome and Principe";
    $countries["SV"]="El Salvador";
    $countries["SY"]="Syrian Arab Republic";
    $countries["SZ"]="Swaziland";
    $countries["TC"]="Turks and Caicos Islands";
    $countries["TD"]="Chad";
    $countries["TF"]="French Southern Territories";
    $countries["TG"]="Togo";
    $countries["TH"]="Thailand";
    $countries["TJ"]="Tajikistan";
    $countries["TK"]="Tokelau";
    $countries["TL"]="Timor-Leste";
    $countries["TM"]="Turkmenistan";
    $countries["TN"]="Tunisia";
    $countries["TO"]="Tonga";
    $countries["TR"]="Turkey";
    $countries["TT"]="Trinidad and Tobago";
    $countries["TV"]="Tuvalu";
    $countries["TW"]="Taiwan, Province of China";
    $countries["TZ"]="Tanzania, United Republic of";
    $countries["UA"]="Ukraine";
    $countries["UG"]="Uganda";
    $countries["UM"]="United States Minor Outlying Islands";
    $countries["US"]="United States";
    $countries["UY"]="Uruguay";
    $countries["UZ"]="Uzbekistan";
    $countries["VA"]="Holy See (Vatican City State)";
    $countries["VC"]="Saint Vincent and the Grenadines";
    $countries["VE"]="Venezuela";
    $countries["VG"]="Virgin Islands, British";
    $countries["VI"]="Virgin Islands, U.S.";
    $countries["VN"]="Viet Nam";
    $countries["VU"]="Vanuatu";
    $countries["WF"]="Wallis and Futuna";
    $countries["WS"]="Samoa";
    $countries["YE"]="Yemen";
    $countries["YT"]="Mayotte";
    $countries["ZA"]="South Africa";
    $countries["ZM"]="Zambia";
    $countries["ZW"]="Zimbabwe";


    //List of all languages for Open Language Tools
    $l=array("ar-EG","ar-IL","ar-SA","zh-CN","EUC-CN","zh-HK","zh-SG","zh-TW","zh-gan","zh-guoyu","zh-hakka","zh-min","zh-min-nan","zh-wuu","zh-xiang","zh-yue","cs-CZ","da-DT","da-DK","nl-BE","nl-NL","en-AU","en-CA","en-GB","en-HK","en-IE","en-IN","en-LR","en-NZ","en-PH","en-SG","en-US","en-ZA","fi-FI","fr-BE","fr-CA","fr-CH","fr-FR","de-AT","de-BE","de-CH","de-DE","el-GR","he-IL","hi-IN","hu-HU","ga-IE","it-IT","ja-JP","kk-KZ","ko-KR","ms-ID","ms-MY","ms-SG","no-NO","pl-PL","pt-BR","pt-PT","ru-KZ","ru-RU","uk-UA","sk-SK","es-AMER","es-AR","es-CL","es-CO","es-ES","es-MX","es-PE","es-VE","sv-FI","sv-SE","ta-SG","th-TH","tr-TR","vi-VT","af-AF","am-AM","ang-ANG","as-AS","az-AZ","az-IR","be-BE","bg-BG","bn-BN","br-BR","bs-BS","cy-CY","eo-EO","et-ET","eu-EU","fa-FA","gl-GL","gu-GU","hr-HR","hy-HY","ia-IA","id-ID","is-IS","ka-KA","kn-KN","ku-KU","li-LI","lt-LT","lv-LV","mi-MI","mk-MK","ml-ML","mn-MN","mr-MR","ms-MS","nb-NB","ne-NE","nn-NN","nso-NSO","or-OR","pa-PA","ro-RO","rw-RW","sl-SL","sq-SQ","sr-SR","sr-CS","ta-TA","te-TE","tg-TG","tk-TK","tl-TL","ug-UG","uz-UZ","uz-LATN","vi-VI","wa-WA","xh-XH","yi-YI","yo-YO","zu-ZU");

    $n1=get_lang_list();
    $n=array();
    foreach($n1 as $k=>$v)
    {
        $n[strtolower($k)]=$v;
    }

    $r=array();
    foreach($l as $v)
    {
        if(array_key_exists(strtolower($v),$n))
        {
            $r[$v]=$n[strtolower($v)];
        }
        else
        {
            $v1=explode("-",$v);
            if(array_key_exists(strtoupper($v1[0]),$languages))
            {
                $r[$v] = $languages[strtoupper($v1[0])];
                if(strtoupper($v1[1])!=strtoupper($v1[0]))
                {
                    $r[$v] .= array_key_exists(strtoupper($v1[1]),$countries) ? " (".$countries[strtoupper($v1[1])].")" : "";
                }
            }
            
            //$v1=explode("-",$v);
            //$r[$v]=array_key_exists(strtolower($v1[0]),$n) ? $n[strtolower($v1[0])] : "";
            
            
        }
    }

    echo "<pre>";
    print_r($r);
    //print_r($l);
    echo "</pre>";
}

/* Konstantin X */
/**
 * Enter description here...
 *
 * @author Konstantin X
 * @return array
 */
function get_lang_list()
{
    $langs = array();
    //List of languages for Open Language Tools translate
    $langs['ar-EG'] = 'Arabic (Egypt)';
    $langs['ar-IL'] = 'Arabic (Israel)';
    $langs['ar-SA'] = 'Arabic (Saudi Arabia)';
    $langs['zh-CN'] = 'Chinese (PRC)';
    $langs['zh-HK'] = 'Chinese (Hong Kong SAR)';
    $langs['zh-SG'] = 'Chinese (Singapore)';
    $langs['zh-TW'] = 'Chinese (Taiwan)';
    $langs['zh-gan'] = 'Chinese (gan)';
    $langs['zh-guoyu'] = 'Chinese (guoyu)';
    $langs['zh-hakka'] = 'Chinese (hakka)';
    $langs['zh-min'] = 'Chinese (min)';
    $langs['zh-min-nan'] = 'Chinese (min-nan)';
    $langs['zh-wuu'] = 'Chinese (wuu)';
    $langs['zh-xiang'] = 'Chinese (xiang)';
    $langs['zh-yue'] = 'Chinese (yue)';
    $langs['cs-CZ'] = 'Czech (Czech Republic)';
    $langs['da-DT'] = 'Danish';
    $langs['da-DK'] = 'Danish (Denmark)';
    $langs['nl-BE'] = 'Dutch (Belgium)';
    $langs['nl-NL'] = 'Dutch';
    $langs['en-AU'] = 'English (Australia)';
    $langs['en-CA'] = 'English (Canada)';
    $langs['en-GB'] = 'English (UK)';
    $langs['en-HK'] = 'English American (Hong Kong)';
    $langs['en-IE'] = 'English (Ireland)';
    $langs['en-IN'] = 'English American (India)';
    $langs['en-LR'] = 'English American (Liberia)';
    $langs['en-NZ'] = 'English (New Zealand)';
    $langs['en-PH'] = 'English American (Philippines)';
    $langs['en-SG'] = 'English American (Singapore)';
    $langs['en-US'] = 'English (US)';
    $langs['en-ZA'] = 'English (South Africa)';
    $langs['fi-FI'] = 'Finnish';
    $langs['fr-BE'] = 'French (Belgium)';
    $langs['fr-CA'] = 'French (Canada)';
    $langs['fr-CH'] = 'French (Switzerland)';
    $langs['fr-FR'] = 'French';
    $langs['de-AT'] = 'German (Austria)';
    $langs['de-BE'] = 'German (Belgium)';
    $langs['de-CH'] = 'German (Switzerland)';
    $langs['de-DE'] = 'German';
    $langs['el-GR'] = 'Greek (Greece)';
    $langs['hi-IN'] = 'Hindi';
    $langs['hu-HU'] = 'Hungarian';
    $langs['ga-IE'] = 'Irish';
    $langs['it-IT'] = 'Italian';
    $langs['ja-JP'] = 'Japanese';
    $langs['kk-KZ'] = 'Kazakh';
    $langs['ko-KR'] = 'Korean (Korea, Republic of)';
    $langs['ms-ID'] = 'Malay (Indonesia)';
    $langs['ms-MY'] = 'Malay (Malaysia)';
    $langs['ms-SG'] = 'Malay (Singapore)';
    $langs['no-NO'] = 'Norwegian';
    $langs['pl-PL'] = 'Polish';
    $langs['pt-BR'] = 'Portuguese (Brazil)';
    $langs['pt-PT'] = 'Portuguese';
    $langs['ru-KZ'] = 'Russian (Kazakhstan)';
    $langs['ru-RU'] = 'Russian';
    $langs['uk-UA'] = 'Ukrainian';
    $langs['sk-SK'] = 'Slovak';
    $langs['es-AMER'] = 'Spanish (American)';
    $langs['es-AR'] = 'Spanish (Argentina)';
    $langs['es-CL'] = 'Spanish (Chile)';
    $langs['es-CO'] = 'Spanish (Colombia)';
    $langs['es-ES'] = 'Spanish (Spain)';
    $langs['es-MX'] = 'Spanish (Mexico)';
    $langs['es-PE'] = 'Spanish (Peru)';
    $langs['es-VE'] = 'Spanish (Venezuela)';
    $langs['sv-FI'] = 'Swedish (Finland)';
    $langs['sv-SE'] = 'Swedish (Sweden)';
    $langs['ta-SG'] = 'Tamil (Singapore)';
    $langs['th-TH'] = 'Thai';
    $langs['tr-TR'] = 'Turkish';
    $langs['vi-VN'] = 'Vietnamese';
    $langs['af-AF'] = 'Afrikaans';
    $langs['am-AM'] = 'Amharic';
    $langs['as-AS'] = 'Assamese';
    $langs['az-AZ'] = 'Azerbaijani';
    $langs['az-IR'] = 'Azerbaijani (Iran, Islamic Republic of)';
    $langs['be-BE'] = 'Byelorussian';
    $langs['bg-BG'] = 'Bulgarian';
    $langs['bn-BN'] = 'Bengali Bangla';
    $langs['br-BR'] = 'Breton';
    $langs['cy-CY'] = 'Welsh';
    $langs['eo-EO'] = 'Esperanto';
    $langs['et-ET'] = 'Estonian';
    $langs['eu-EU'] = 'Basque';
    $langs['fa-FA'] = 'Persian';
    $langs['gl-GL'] = 'Galician';
    $langs['gu-GU'] = 'Gujarati';
    $langs['hr-HR'] = 'Croatian';
    $langs['hy-HY'] = 'Armenian';
    $langs['ia-IA'] = 'Interlingua';
    $langs['is-IS'] = 'Icelandic';
    $langs['ka-KA'] = 'Georgian';
    $langs['kn-KN'] = 'Kannada';
    $langs['ku-KU'] = 'Kurdish';
    $langs['lt-LT'] = 'Lithuanian';
    $langs['lv-LV'] = 'Latvian Lettish';
    $langs['mi-MI'] = 'Maori';
    $langs['mk-MK'] = 'Macedonian';
    $langs['ml-ML'] = 'Malayalam';
    $langs['mn-MN'] = 'Mongolian';
    $langs['mr-MR'] = 'Marathi';
    $langs['ms-MS'] = 'Malay';
    $langs['ne-NE'] = 'Nepali';
    $langs['or-OR'] = 'Oriya';
    $langs['pa-PA'] = 'Punjabi';
    $langs['ro-RO'] = 'Romanian';
    $langs['rw-RW'] = 'Kinyarwanda';
    $langs['sl-SL'] = 'Slovenian';
    $langs['sq-SQ'] = 'Albanian';
    $langs['sr-SR'] = 'Serbian';
    $langs['ta-TA'] = 'Tamil';
    $langs['te-TE'] = 'Tegulu';
    $langs['tg-TG'] = 'Tajik';
    $langs['tk-TK'] = 'Turkmen';
    $langs['tl-TL'] = 'Tagalog';
    $langs['uz-UZ'] = 'Uzbek';
    $langs['uz-LATN'] = 'Uzbek (Latin)';
    $langs['xh-XH'] = 'Xhosa';
    $langs['yo-YO'] = 'Yoruba';
    $langs['zu-ZU'] = 'Zulu';
    natcasesort($langs);

    /*
        $langs['af']    = 'Afrikaans';// Afrikaans
        $langs['sq']    = 'Albanian';// Albanian
        $langs['ar-SA'] = 'Arabic (Saudi Arabia)';// Arabic (Saudi Arabia)
        $langs['ar-IQ'] = 'Arabic (Iraq)';// Arabic (Iraq)
        $langs['ar-EG'] = 'Arabic (Egypt)';// Arabic (Egypt)
        $langs['ar-LY'] = 'Arabic (Libya)';// Arabic (Libya)
        $langs['ar-DZ'] = 'Arabic (Algeria)';// Arabic (Algeria)
        $langs['ar-MA'] = 'Arabic (Morocco)';// Arabic (Morocco)
        $langs['ar-TN'] = 'Arabic (Tunisia)';// Arabic (Tunisia)
        $langs['ar-OM'] = 'Arabic (Oman)';// Arabic (Oman)
        $langs['ar-ye'] = 'Arabic (Yemen)';// Arabic (Yemen)
        $langs['ar-sy'] = 'Arabic (Syria)';// Arabic (Syria)
        $langs['ar-jo'] = 'Arabic (Jordan)';// Arabic (Jordan)
        $langs['ar-lb'] = 'Arabic (Lebanon)';// Arabic (Lebanon)
        $langs['ar-kw'] = 'Arabic (Kuwait)';// Arabic (Kuwait)
        $langs['ar-ae'] = 'Arabic (U.A.E.)';// Arabic (U.A.E.)
        $langs['ar-bh'] = 'Arabic (Bahrain)';// Arabic (Bahrain)
        $langs['ar-qa'] = 'Arabic (Qatar)';// Arabic (Qatar)
        $langs['eu']    = 'Basque';// Basque
        $langs['bg']    = 'Bulgarian';// Bulgarian
        $langs['be']    = 'Belarusian';// Belarusian
        $langs['ca']    = 'Catalan';// Catalan
        $langs['zh-tw'] = 'Chinese (Taiwan)';// Chinese (Taiwan)
        $langs['zh-cn'] = 'Chinese (PRC)';// Chinese (PRC)
        $langs['zh-hk'] = 'Chinese (Hong Kong SAR)';// Chinese (Hong Kong SAR)
        $langs['zh-sg'] = 'Chinese (Singapore)';// Chinese (Singapore)
        $langs['hr']    = 'Croatian';// Croatian
        $langs['cs']    = 'Czech';// Czech
        $langs['da']    = 'Danish';// Danish
        $langs['nl']    = 'Dutch (Standard)';// Dutch (Standard)
        $langs['nl-be'] = 'Dutch (Belgium)';// Dutch (Belgium)
        $langs['en-US'] = 'English (US)';// English (United States)
        $langs['en-GB'] = 'English (UK)';// English (United Kingdom)
        $langs['en-AU'] = 'English (Australia)';// English (Australia)
        $langs['en-CA'] = 'English (Canada)';// English (Canada)
        $langs['en-NZ'] = 'English (New Zealand)';// English (New Zealand)
        $langs['en-IE'] = 'English (Ireland)';// English (Ireland)
        $langs['en-ZA'] = 'English (South Africa)';// English (South Africa)
        $langs['en-JM'] = 'English (Jamaica)';// English (Jamaica)
        $langs['en-BZ'] = 'English (Belize)';// English (Belize)
        $langs['en-TT'] = 'English (Trinidad)';// English (Trinidad)
        $langs['et']    = 'Estonian';// Estonian
        $langs['fo']    = 'Faeroese';// Faeroese
        $langs['fa']    = 'Farsi';// Farsi
        $langs['fi']    = 'Finnish';// Finnish
        $langs['fr']    = 'French (Standard)';// French (Standard)
        $langs['fr-be'] = 'French (Belgium)';// French (Belgium)
        $langs['fr-ca'] = 'French (Canada)';// French (Canada)
        $langs['fr-ch'] = 'French (Switzerland)';// French (Switzerland)
        $langs['fr-lu'] = 'French (Luxembourg)';// French (Luxembourg)
        $langs['gd']    = 'Gaelic (Scotland)';// Gaelic (Scotland)
        $langs['gd-ie'] = 'Gaelic (Ireland)';// Gaelic (Ireland)
        $langs['de']    = 'German (Standard)';// German (Standard)
        $langs['de-ch'] = 'German (Switzerland)';// German (Switzerland)
        $langs['de-at'] = 'German (Austria)';// German (Austria)
        $langs['de-lu'] = 'German (Luxembourg)';// German (Luxembourg)
        $langs['de-li'] = 'German (Liechtenstein)';// German (Liechtenstein)
        $langs['el']    = 'Greek';// Greek
        $langs['he']    = 'Hebrew';// Hebrew
        $langs['hi']    = 'Hindi';// Hindi
        $langs['hu']    = 'Hungarian';// Hungarian
        $langs['is']    = 'Icelandic';// Icelandic
        $langs['id']    = 'Indonesian';// Indonesian
        $langs['it']    = 'Italian (Standard)';// Italian (Standard)
        $langs['it-ch'] = 'Italian (Switzerland)';// Italian (Switzerland)
        $langs['ja']    = 'Japanese';// Japanese
        $langs['ko']    = 'Korean';// Korean
        $langs['ko']    = 'Korean (Johab)';// Korean (Johab)
        $langs['lv']    = 'Latvian';// Latvian
        $langs['lt']    = 'Lithuanian';// Lithuanian
        $langs['mk']    = 'Macedonian (FYROM)';// Macedonian (FYROM)||$langs['ms'] = 'Malaysian';// Malaysian
        $langs['mt']    = 'Maltese';// Maltese
        $langs['no']    = 'Norwegian (Bokmal)';// Norwegian (Bokmal)
        $langs['no']    = 'Norwegian (Nynorsk)';// Norwegian (Nynorsk)
        $langs['pl']    = 'Polish';// Polish
        $langs['pt-br'] = 'Portuguese (Brazil)';// Portuguese (Brazil)
        $langs['pt']    = 'Portuguese (Portugal)';// Portuguese (Portugal)
        $langs['rm']    = 'Rhaeto-Romanic';// Rhaeto-Romanic
        $langs['ro']    = 'Romanian';// Romanian
        $langs['ro-mo'] = 'Romanian (Republic of Moldova)';// Romanian (Republic of Moldova)
        $langs['ru']    = 'Russian';// Russian
        $langs['ru-mo'] = 'Russian (Republic of Moldova)';// Russian (Republic of Moldova)
        $langs['sz']    = 'Sami (Lappish)';// Sami (Lappish)
        $langs['sr']    = 'Serbian (Cyrillic)';// Serbian (Cyrillic)
        $langs['sr']    = 'Serbian (Latin)';// Serbian (Latin)
        $langs['sk']    = 'Slovak';// Slovak
        $langs['sl']    = 'Slovenian';// Slovenian
        $langs['sb']    = 'Sorbian';// Sorbian
        $langs['es']    = 'Spanish (Spain)';// Spanish (Spain)
        $langs['es-mx'] = 'Spanish (Mexico)';// Spanish (Mexico)
        $langs['es-gt'] = 'Spanish (Guatemala)';// Spanish (Guatemala)
        $langs['es-cr'] = 'Spanish (Costa Rica)';// Spanish (Costa Rica)
        $langs['es-pa'] = 'Spanish (Panama)';// Spanish (Panama)
        $langs['es-do'] = 'Spanish (Dominican Republic)';// Spanish (Dominican Republic)
        $langs['es-ve'] = 'Spanish (Venezuela)';// Spanish (Venezuela)
        $langs['es-co'] = 'Spanish (Colombia)';// Spanish (Colombia)
        $langs['es-pe'] = 'Spanish (Peru)';// Spanish (Peru)
        $langs['es-ar'] = 'Spanish (Argentina)';// Spanish (Argentina)
        $langs['es-ec'] = 'Spanish (Ecuador)';// Spanish (Ecuador)
        $langs['es-cl'] = 'Spanish (Chile)';// Spanish (Chile)
        $langs['es-uy'] = 'Spanish (Uruguay)';// Spanish (Uruguay)
        $langs['es-py'] = 'Spanish (Paraguay)';// Spanish (Paraguay)
        $langs['es-bo'] = 'Spanish (Bolivia)';// Spanish (Bolivia)
        $langs['es-sv'] = 'Spanish (El Salvador)';// Spanish (El Salvador)
        $langs['es-hn'] = 'Spanish (Honduras)';// Spanish (Honduras)
        $langs['es-ni'] = 'Spanish (Nicaragua)';// Spanish (Nicaragua)
        $langs['es-pr'] = 'Spanish (Puerto Rico)';// Spanish (Puerto Rico)
        $langs['sx']    = 'Sutu';// Sutu
        $langs['sv']    = 'Swedish';// Swedish
        $langs['sv-fi'] = 'Swedish (Finland)';// Swedish (Finland)
        $langs['th']    = 'Thai';// Thai
        $langs['ts']    = 'Tsonga';// Tsonga
        $langs['tn']    = 'Tswana';// Tswana
        $langs['tr']    = 'Turkish';// Turkish
        $langs['uk']    = 'Ukrainian';// Ukrainian
        $langs['ur']    = 'Urdu';// Urdu
        $langs['ve']    = 'Venda';// Venda
        $langs['vi']    = 'Vietnamese';// Vietnamese
        $langs['xh']    = 'Xhosa';// Xhosa
        $langs['ji']    = 'Yiddish';// Yiddish
        $langs['zu']    = 'Zulu';// Zulu

        $langs['aa'] = 'Afar';// Afar
        $langs['ab'] = 'Abkhazian';// Abkhazian
        $langs['af'] = 'Afrikaans';// Afrikaans
        $langs['ak'] = 'Akan';// Akan
        $langs['sq'] = 'Albanian';// Albanian
        $langs['am'] = 'Amharic';// Amharic
        $langs['ar'] = 'Arabic';// Arabic
        $langs['an'] = 'Aragonese';// Aragonese
        $langs['hy'] = 'Armenian';// Armenian
        $langs['as'] = 'Assamese';// Assamese
        $langs['av'] = 'Avaric';// Avaric
        $langs['ae'] = 'Avestan';// Avestan
        $langs['ay'] = 'Aymara';// Aymara
        $langs['az'] = 'Azerbaijani';// Azerbaijani
        $langs['ba'] = 'Bashkir';// Bashkir
        $langs['bm'] = 'Bambara';// Bambara
        $langs['eu'] = 'Basque';// Basque
        $langs['be'] = 'Belarusian';// Belarusian
        $langs['bn'] = 'Bengali';// Bengali
        $langs['bh'] = 'Bihari';// Bihari
        $langs['bi'] = 'Bislama';// Bislama
        $langs['bs'] = 'Bosnian';// Bosnian
        $langs['br'] = 'Breton';// Breton
        $langs['bg'] = 'Bulgarian';// Bulgarian
        $langs['my'] = 'Burmese';// Burmese
        $langs['ca'] = 'Catalan';// Catalan; Valencian
        $langs['ch'] = 'Chamorro';// Chamorro
        $langs['ce'] = 'Chechen';// Chechen
        $langs['zh'] = 'Chinese';// Chinese
        $langs['cv'] = 'Chuvash';// Chuvash
        $langs['kw'] = 'Cornish';// Cornish
        $langs['co'] = 'Corsican';// Corsican
        $langs['cr'] = 'Cree';// Cree
        $langs['cs'] = 'Czech';// Czech
        $langs['da'] = 'Danish';// Danish
        $langs['dv'] = 'Maldivian';// Divehi; Dhivehi; Maldivian
        $langs['nl'] = 'Dutch';// Dutch; Flemish
        $langs['dz'] = 'Dzongkha';// Dzongkha
        $langs['en-GB'] = 'English (GB)';
        $langs['en-US'] = 'English (US)';
        $langs['et'] = 'Estonian';// Estonian
        $langs['ee'] = 'Ewe';// Ewe
        $langs['fo'] = 'Faroese';// Faroese
        $langs['fj'] = 'Fijian';// Fijian
        $langs['fi'] = 'Finnish';// Finnish
        $langs['fr'] = 'French';// French
        $langs['ff'] = 'Fulah';// Fulah
        $langs['ka'] = 'Georgian';// Georgian
        $langs['de'] = 'German';// German
        $langs['gd'] = 'Gaelic';// Gaelic; Scottish Gaelic
        $langs['ga'] = 'Irish';// Irish
        $langs['gl'] = 'Galician';// Galician
        $langs['gv'] = 'Manx';// Manx
        $langs['el'] = 'Greek';// Greek, Modern (1453-)
        $langs['gn'] = 'Guarani';// Guarani
        $langs['gu'] = 'Gujarati';// Gujarati
        $langs['ht'] = 'Haitian';// Haitian; Haitian Creole
        $langs['ha'] = 'Hausa';// Hausa
        $langs['he'] = 'Hebrew';// Hebrew
        $langs['hz'] = 'Herero';// Herero
        $langs['hi'] = 'Hindi';// Hindi
        $langs['ho'] = 'Hiri Motu';// Hiri Motu
        $langs['hr'] = 'Croatian';// Croatian
        $langs['hu'] = 'Hungarian';// Hungarian
        $langs['ig'] = 'Igbo';// Igbo
        $langs['is'] = 'Icelandic';// Icelandic
        $langs['ii'] = 'Nuosu';// Sichuan Yi; Nuosu
        $langs['iu'] = 'Inuktitut';// Inuktitut
        $langs['id'] = 'Indonesian';// Indonesian
        $langs['ik'] = 'Inupiaq';// Inupiaq
        $langs['it'] = 'Italian';// Italian
        $langs['jv'] = 'Javanese';// Javanese
        $langs['ja'] = 'Japanese';// Japanese
        $langs['kl'] = 'Kalaallisut';// Kalaallisut; Greenlandic
        $langs['kn'] = 'Kannada';// Kannada
        $langs['ks'] = 'Kashmiri';// Kashmiri
        $langs['kr'] = 'Kanuri';// Kanuri
        $langs['kk'] = 'Kazakh';// Kazakh
        $langs['km'] = 'Central Khmer';// Central Khmer
        $langs['ki'] = 'Kikuyu';// Kikuyu; Gikuyu
        $langs['rw'] = 'Kinyarwanda';// Kinyarwanda
        $langs['ky'] = 'Kirghiz';// Kirghiz; Kyrgyz
        $langs['kv'] = 'Komi';// Komi
        $langs['kg'] = 'Kongo';// Kongo
        $langs['ko'] = 'Korean';// Korean
        $langs['kj'] = 'Kuanyama';// Kuanyama; Kwanyama
        $langs['ku'] = 'Kurdish';// Kurdish
        $langs['lo'] = 'Lao';// Lao
        $langs['lv'] = 'Latvian';// Latvian
        $langs['li'] = 'Limburgan';// Limburgan; Limburger; Limburgish
        $langs['ln'] = 'Lingala';// Lingala
        $langs['lt'] = 'Lithuanian';// Lithuanian
        $langs['lb'] = 'Luxembourgish';// Luxembourgish; Letzeburgesch
        $langs['lu'] = 'Luba-Katanga';// Luba-Katanga
        $langs['lg'] = 'Ganda';// Ganda
        $langs['mk'] = 'Macedonian';// Macedonian
        $langs['mh'] = 'Marshallese';// Marshallese
        $langs['ml'] = 'Malayalam';// Malayalam
        $langs['mi'] = 'Maori';// Maori
        $langs['mr'] = 'Marathi';// Marathi
        $langs['ms'] = 'Malay';// Malay
        $langs['mg'] = 'Malagasy';// Malagasy
        $langs['mt'] = 'Maltese';// Maltese
        $langs['mn'] = 'Mongolian';// Mongolian
        $langs['na'] = 'Nauru';// Nauru
        $langs['no'] = 'Norwegian';// Norwegian
        $langs['os'] = 'Ossetian';// Ossetian; Ossetic
        $langs['fa'] = 'Persian';// Persian
        $langs['pl'] = 'Polish';// Polish
        $langs['pt'] = 'Portuguese';// Portuguese
        $langs['ps'] = 'Pushto';// Pushto; Pashto
        $langs['qu'] = 'Quechua';// Quechua
        $langs['rm'] = 'Romansh';// Romansh
        $langs['ro'] = 'Romanian';// Romanian; Moldavian; Moldovan
        $langs['rn'] = 'Rundi';// Rundi
        $langs['ru'] = 'Russian';// Russian
        $langs['sg'] = 'Sango';// Sango
        $langs['sa'] = 'Sanskrit';// Sanskrit
        $langs['si'] = 'Sinhala';// Sinhala; Sinhalese
        $langs['sk'] = 'Slovak';// Slovak
        $langs['sl'] = 'Slovenian';// Slovenian
        $langs['se'] = 'Northern Sami';// Northern Sami
        $langs['sm'] = 'Samoan';// Samoan
        $langs['sn'] = 'Shona';// Shona
        $langs['sd'] = 'Sindhi';// Sindhi
        $langs['so'] = 'Somali';// Somali
        $langs['st'] = 'Sotho';// Sotho, Southern
        $langs['es'] = 'Spanish';// Spanish; Castilian
        $langs['sc'] = 'Sardinian';// Sardinian
        $langs['sr'] = 'Serbian';// Serbian
        $langs['ss'] = 'Swati';// Swati
        $langs['sv'] = 'Swedish';// Swedish
        $langs['ty'] = 'Tahitian';// Tahitian
        $langs['tt'] = 'Tatar';// Tatar
        $langs['tg'] = 'Tajik';// Tajik
        $langs['th'] = 'Thai';// Thai
        $langs['bo'] = 'Tibetan';// Tibetan
        $langs['to'] = 'Tonga';// Tonga (Tonga Islands)
        $langs['tn'] = 'Tswana';// Tswana
        $langs['ts'] = 'Tsonga';// Tsonga
        $langs['tk'] = 'Turkmen';// Turkmen
        $langs['tr'] = 'Turkish';// Turkish
        $langs['uk'] = 'Ukrainian';// Ukrainian
        $langs['uz'] = 'Uzbek';// Uzbek
        $langs['vi'] = 'Vietnamese';// Vietnamese
        $langs['yi'] = 'Yiddish';// Yiddish
        $langs['zu'] = 'Zulu';// Zulu
*/
        return $langs;
}


/**
 * Enter description here...
 *
 * @return array
 */
function get_states()
{
    $states = array();

    $states['XX']="<{state_XX}>";
    $states['AL']="<{state_AL}>";
    $states['AK']="<{state_AK}>";
    $states['AB']="<{state_AB}>";
    $states['AS']="<{state_AS}>";
    $states['AZ']="<{state_AZ}>";
    $states['AR']="<{state_AR}>";
    $states['AA']="<{state_AA}>";
    $states['AE']="<{state_AE}>";
    $states['AP']="<{state_AP}>";
    $states['BC']="<{state_BC}>";
    $states['CA']="<{state_CA}>";
    $states['CO']="<{state_CO}>";
    $states['CT']="<{state_CT}>";
    $states['DE']="<{state_DE}>";
    $states['DC']="<{state_DC}>";
    $states['FL']="<{state_FL}>";
    $states['GA']="<{state_GA}>";
    $states['GU']="<{state_GU}>";
    $states['HI']="<{state_HI}>";
    $states['ID']="<{state_ID}>";
    $states['IL']="<{state_IL}>";
    $states['IN']="<{state_IN}>";
    $states['IA']="<{state_IA}>";
    $states['KS']="<{state_KS}>";
    $states['KY']="<{state_KY}>";
    $states['LA']="<{state_LA}>";
    $states['ME']="<{state_ME}>";
    $states['MB']="<{state_MB}>";
    $states['MD']="<{state_MD}>";
    $states['MA']="<{state_MA}>";
    $states['MI']="<{state_MI}>";
    $states['MN']="<{state_MN}>";
    $states['MS']="<{state_MS}>";
    $states['MO']="<{state_MO}>";
    $states['MT']="<{state_MT}>";
    $states['NE']="<{state_NE}>";
    $states['NV']="<{state_NV}>";
    $states['NB']="<{state_NB}>";
    $states['NH']="<{state_NH}>";
    $states['NJ']="<{state_NJ}>";
    $states['NM']="<{state_NM}>";
    $states['NY']="<{state_NY}>";
    $states['NF']="<{state_NF}>";
    $states['NC']="<{state_NC}>";
    $states['ND']="<{state_ND}>";
    $states['MP']="<{state_MP}>";
    $states['NT']="<{state_NT}>";
    $states['NS']="<{state_NS}>";
    $states['NU']="<{state_NU}>";
    $states['OH']="<{state_OH}>";
    $states['OK']="<{state_OK}>";
    $states['ON']="<{state_ON}>";
    $states['OR']="<{state_OR}>";
    $states['PW']="<{state_PW}>";
    $states['PA']="<{state_PA}>";
    $states['PE']="<{state_PE}>";
    $states['QC']="<{state_QC}>";
    $states['PR']="<{state_PR}>";
    $states['RI']="<{state_RI}>";
    $states['SK']="<{state_SK}>";
    $states['SC']="<{state_SC}>";
    $states['SD']="<{state_SD}>";
    $states['TN']="<{state_TN}>";
    $states['TX']="<{state_TX}>";
    $states['UT']="<{state_UT}>";
    $states['VT']="<{state_VT}>";
    $states['VI']="<{state_VI}>";
    $states['VA']="<{state_VA}>";
    $states['WA']="<{state_WA}>";
    $states['WV']="<{state_WV}>";
    $states['WI']="<{state_WI}>";
    $states['WY']="<{state_WY}>";
    $states['YT']="<{state_YT}>";


    return $states;
}

/**
* If even one message is displayed.
*
* @param array $msgs
* @return boolean
*/
function is_msg_displayed($msgs)
{
    foreach($msgs as $k=>$v)
    {
        if(is_array($v) && isset($v['display']) && $v['display'])
        return true;
    }
    return false;
}

/**
 * Enter description here...
 *
 * @param string $class
 * @param array $keys
 * @param string $interface
 * @return mixed
 */
function print_msg_box($class='',$keys='',$interface='user')
{
    $CI = &get_instance();
    
    if( !isset($class) or empty($class) or !in_array($class,array('msg','emsg')) ) { $class='msg'; }
    if( !isset($keys) or !is_array($keys) or sizeof($keys)<=0 ) { $keys = array('-'=>'-'); }
    $data = array();
    $data['keys'] = $keys;
    $data['class'] = $class;
    $display = 0;

    foreach( $keys as $id=>$value)
    {
        if( isset($value) )
        {
            if( is_array($value) )
            {
                if(isset($value['display']) and intval($value['display'])==1)
                {
                    $display = 1;
                }
            }
        }
    }
    $data['box_display'] = $display;

    if( $interface == 'user' )
    {
        $data['box_class']=(isset($data['class']) && $data['class']=='msg')?'mess':'mess_err';
        $data['item_class']=(isset($data['class']) && $data['class']=='msg')?'box':'box_err';
        $data['box_display']=(isset($data['box_display']) && intval($data['box_display'])==1)?'':'display:none;';
        $data['items']=array();
        foreach($data['keys'] as $id=>$value)
        {
            $data['items'][]=array(
            'id'=>$id,
            'display'=>((is_array($value) && isset($value['display']) && intval($value['display'])==1)?'':'display:none;'),
            'text'=>((is_array($value) && isset($value['text']))?$value['text']:$value)
            );
        }        
        $box = _view('user/common/error_box.html',$data,true,true);
    }
    else
    {
        $box = $CI->load->view('admin/common/error_box',$data,true);
    }
    return $box;
}
/**
 * Prepare amount to print
 *
 * @param float $amount
 * @return string
 */
function amount_to_print($amount)
{

    if(strval($amount)==='')
    {
        $amount='00.00';
    }
    else
    {
        $amount = round(floatval($amount),2);
        $amount_part=explode('.',strval($amount));

        if( isset($amount_part[0]) )
        {
            $amount_f=strval($amount_part[0]);
        }
        else
        {
            $amount_f = '0';
        }

        if( isset($amount_part[1]) )
        {
            $amount_s=strval($amount_part[1]);
        }
        else
        {
            $amount_s = '0';
        }


        if(strlen($amount_s)==1 and intval($amount_s)>0 and intval($amount_s)<10)
        {
        $amount_s=intval($amount_s).'0';
        }
        elseif(strlen($amount_s)==2 and intval($amount_s)>0 and intval($amount_s)<10)
        {
        $amount_s='0'.intval($amount_s);
        }


        if(intval($amount_s)==0)
        {
        $amount_s='00';
        }

        $amount=intval($amount_f).".".$amount_s;
    }

    return strval($amount);
}


/**
 * Enter description here...
 *
 * @param string $template_name
 * @param array $template_data
 * @param boolean $return_result
 * @param boolean $is_parser
 * @return mixed
 * 
 */
function _view($template_name,$template_data,$return_result=false,$is_parser=false)
{
    $CI = &get_instance();
    $CI->load->model('user_auth_model');

    $text = mb_substr($template_name,0,2048);
    if(empty($text))
    {
        return false;
    }

    if(!is_array($template_data))
    {
        return false;
    }

    //get system status and offline message if there is some
    $system_status_is_online = intval(config_get('SYSTEM','STATUS','online'));
    $system_offline_message = config_get('SYSTEM','STATUS','offline_msg');

    //
    // Makarenko Sergey
    // added at 02.10.2008 17:56:22
    //
    //DO NOT REMOVE NEXT CODE LINE!
    //if $template_name=='user/header' then ignore $system_status_is_online state
    if ($template_name=='user/header')
    {
        $system_status_is_online = 1;
    }
    if($template_name!=='user/remote_login_form.html' && $is_parser && $return_result!==false)
    {
        $system_status_is_online = 1;
    }
    //******************************
    if( $CI->user_auth_model->is_auth() )
    {
        $active_reg_design = config_get('DESIGN','active_reg_design');
        $design_reg_list = config_get('DESIGN','design_reg_list');
        
        if(defined("NS_CURRENT_DESIGN"))
        {
            $design_prefix = NS_CURRENT_DESIGN.'/reg/';
        }
        else
        {
            $design_prefix = $design_reg_list[$active_reg_design].'/reg/';
        }
        
        if(!file_exists(config_get("SYSTEM","CONFIG","ABSOLUTE_PATH")."system/application/views/".$design_prefix))
        {
            design_check();
            $active_reg_design = config_get('DESIGN','active_reg_design');
            $design_reg_list = config_get('DESIGN','design_reg_list');
            $design_prefix = $design_reg_list[$active_reg_design].'/reg/';
        }
        
        if(Functionality_enabled('admin_config_design')!==true)
        {
            $design_prefix = 'default/reg/';
        }

        if(defined("NS_PREVIEW_DESIGN") && file_exists(config_get("SYSTEM","CONFIG","ABSOLUTE_PATH")."system/application/views/".NS_PREVIEW_DESIGN))
        {
            $design_prefix=NS_PREVIEW_DESIGN;
        }
        else if (!$system_status_is_online)
        {
            $template_name = $is_parser ? 'user/system_offline.html' : 'user/system_offline';
            $template_data['offline_reason'] = $system_offline_message;
        }
    }
    else
    {
        $active_unreg_design = config_get('DESIGN','active_unreg_design');
        $design_unreg_list = config_get('DESIGN','design_unreg_list');
        
        
        if(defined("NS_CURRENT_DESIGN"))
        {
            $design_prefix = NS_CURRENT_DESIGN.'/unreg/';
        }
        else
        {
            $design_prefix = $design_unreg_list[$active_unreg_design].'/unreg/';
        }
        
        if(!file_exists(config_get("SYSTEM","CONFIG","ABSOLUTE_PATH")."system/application/views/".$design_prefix))
        {
            design_check();
            $active_unreg_design = config_get('DESIGN','active_unreg_design');
            $design_unreg_list = config_get('DESIGN','design_unreg_list');
            $design_prefix = $design_reg_list[$active_reg_design].'/reg/';
        }
        
        if(Functionality_enabled('admin_config_design')!==true)
        {
            $design_prefix = 'default/unreg/';
        }
        
        if(defined("NS_PREVIEW_DESIGN") && file_exists(config_get("SYSTEM","CONFIG","ABSOLUTE_PATH")."system/application/views/".NS_PREVIEW_DESIGN))
        {
            $design_prefix=NS_PREVIEW_DESIGN;
        }
        else if (!$system_status_is_online)
        {
            $template_name = $is_parser ? 'user/system_offline.html' : 'user/system_offline';
            //if remote login
            $template_name =($is_parser==='remote') ? 'user/remote_system_offline.html' : $template_name;
            
            $template_data['offline_reason'] = $system_offline_message;
            $template_data['header']=isset($data['header']) ? $data['header'] : print_header(array(),true);
            $template_data['menu']=isset($data['menu']) ? $data['menu'] : print_menu();
            $template_data['footer']=isset($data['footer']) ? $data['footer'] : print_footer();    
        }
    }
    $template_data['theme_path'] = '/'.$design_prefix;
    $template_data['base_url'] = base_url();
    if($is_parser)
    {
        $CI->load->library('parser');
        return $CI->parser->parse($design_prefix.$template_name,$template_data,$return_result);
    }
    else
    {
        return $CI->load->view($design_prefix.$template_name,$template_data,$return_result);
    }
}


/**
 * Create tooltip div
 *
 * @param string $key
 * @param boolean $is_concat
 * @return string
 */
function create_tooltip_div($key,$is_concat=false)
{
    if( !isset($key) )
    {
        return false;
    }
    $return = '';

    $return .='<span id="'.md5($key).'" class="tooltip_area">';
    $return .='<img class="helper" width="16" height="16" style="vertical-align: middle;" src="'.base_url().'img/ico_help.png" />';
    $return .='<span style="display:none;">'.($is_concat ? $key : '<{'.output($key).'}>').'</span>';
    $return .='</span>';

    return $return;
}

//you may pass here special array of variables in $data
/**
 * Enter description here...
 *
 * @param array $data
 * @param boolean $return_result
 * @return mixed
 */
function print_header($data=false,$return_result=false)
{
    $CI = &get_instance();
    $data=is_array($data)?$data:array();
    
    $data['header_title'] = output(array_key_exists('header_title', $data) ? config_get('system','config','site_name').' - '.$data['header_title'] : config_get('system','config','site_name'));
    $data['keywords'] = output((array_key_exists('keywords', $data)&&is_array($data['keywords'])) ? implode(",", $data['keywords']) : false);
    $data['current_url'] = encode_url(base_url().mb_substr($CI->uri->uri_string(),1));
    // last_login and user's suscriptions count
    $data['user_info']=array();
    $data['home_link']=array();
    $data['user_name'] = "user";
    $data['last_login_date']=nsdate(date("Y-m-d h:i:s"));
            
    if( intval($CI->user_auth_model->uid)>0 )
    {
        $CI->db->select('name,last_online');
        $CI->db->limit(1);
        $query = $CI->db->get_where(db_prefix.'Users',array('id'=>intval($CI->user_auth_model->uid)));
        if( $query->num_rows() > 0 )
        {
            $user_info = $query->row();
            $data['user_name'] = output($user_info->name);
            $data['last_login_date']=nsdate($user_info->last_online);
            $data['user_info'][]=array(0=>'');
            if(Functionality_enabled('member_registered_link_home')===true)
            {
                $data['home_link'][]=array(0=>'');
            }
        }        
    }
    // _last_login and user's suscriptions count
    
    $data['multi_language']=array();
    $data['config_script']=isset($data['config_script']) ? $data['config_script'] : array();
    $data['config_script']=array(0=>array('content'=>"var base_url='".base_url()."';\nwindow.cronManually=".(get_debug_params(6)>0?"true":"false").";\n".client_script_config($data['config_script'])));
    if(isset($data['user_scripts']))
    {
        $scripts=array();
        $data['user_scripts']=is_array($data['user_scripts']) ? $data['user_scripts'] : array($data['user_scripts']);
        foreach($data['user_scripts'] as $value)
        {
            $scripts[]=array('script'=>$value);
        }
        $data['user_scripts']=$scripts;
    }
    else
    {
        $data['user_scripts']=array();
    }    
    //warnings (demo, trial, etc)
    $data['warnings']=array();
    if(defined('NS_DEMO_VERSION'))
    {
        $data['warnings'][]=array('id'=>"demo_timer",'class'=>"demo",'text'=>"<{demo_header_warning}>".time_left(date('Y-m-d 00:00:00',time()+86400)));
    }
    if(defined('NS_TRIAL_VERSION'))
    {
        $data['warnings'][]=array('id'=>"",'class'=>"demo",'text'=>"TRIAL VERSION");
    }
    //language selector
    $data['multi_language']=array();
    $data['if_languages']=array();
    if(Functionality_enabled('admin_multi_language')===true)
    {
        // language array
        $CI->db->order_by('id');
        $languages_query = $CI->db->get(db_prefix.'Languages');
        if( $languages_query-> num_rows() >1 )
        {
            $data['languages'] = $languages_query->result_array();
            $data['if_languages']=array(array());
            // _language array
            foreach($data['languages'] as $key=>$value)
            {
                $data['languages'][$key]['selected']=(intval(@$_COOKIE['lang_id'])==intval($value['id']))?'selected':'';
            }
            $data['change_language_disable']=(intval($CI->user_auth_model->uid)>0 && Functionality_enabled('admin_member_info_modify', intval($CI->user_auth_model->uid))!==true) ? 'disabled' : '';
            
        }
        $data['simple_translate']=(defined('DEBUG_RESPONSE_FLAG') && get_debug_params(2)>0) ? array(0=>array()) : array();
        $data['multi_language'][]=array('content'=>_view('user/multi_language_menu.html',$data,true,true));        
    }
    
    return _view('user/header.html',$data,$return_result,true);    
    return true;
}
/**
 * Enter description here...
 *
 * @param array $data
 * @return mixed
 */
function print_menu($data=false)
{
    $CI = &get_instance();
    $data=is_array($data)?$data:array();
    $data['site_info']=array(); 
    if(Functionality_enabled('member_unregistered_menu_site_info')===true)
    {
        $data['site_info'][]=array(0=>'');
    }
    $data['domain_registration_info']=array(); 
    
    $CI->load->model("user_model");
    $data['profile_additional']=array();
    foreach($CI->user_model->profile_additional_list() as $k=>$v)
    {
        $pfs=count($v) ? $v : array(array('account_id'=>0,'account_type_string'=>$k,'account_name'=>('<{user_menu_profile_additional_'.$k.'_add}>')));
        $data['profile_additional']=array_merge($data['profile_additional'],$pfs);
    }    
    
    $data['menu_additional']=array(); 
    if(Functionality_enabled('member_unregistered_menu_additional')===true)
    {
        $CI->load->model("user_model");
        $data['menu_additional']=$CI->user_model->Get_pages_list(true);
        foreach($data['menu_additional'] as $k=>$v){        
        $data['menu_additional'][$k]['page_title'] = output($v['page_title']);
        }
    }
    fb($data);
    $data['active_products']=array(); 
    if(Functionality_enabled('member_registered_menu_active_products')===true)
    {
        $data['active_products'][]=array(0=>'');
    }
    $data['paid_invoices']=array(); 
    if(Functionality_enabled('admin_products_modify_paid')===true)
    {
        $data['paid_invoices'][]=array(0=>'');
    }
    
    return _view('user/menu.html',$data,true,true);    
}
/**
 * Enter description here...
 *
 * @param array $data
 * @return mixed
 */
function print_footer($data=false)
{
    $data=is_array($data)?$data:array();
    return _view('user/footer.html',$data,true,true);    
}
/**
 * Enter description here...
 *
 * @param string $template_name
 * @param array $data
 * @param boolean $return_result
 * @return mixed
 */
function print_page($template_name,$data=array(),$return_result=false)
{
    $header_data=array();    
    $header_data['user_scripts']= isset($data['user_scripts']) ? $data['user_scripts'] : array();
    $header_data['config_script']= isset($data['config_script']) ? $data['config_script'] : array();
    $data['header']=isset($data['header']) ? $data['header'] : print_header($header_data,true);
    $data['menu']=isset($data['menu']) ? $data['menu'] : print_menu();
    $data['footer']=isset($data['footer']) ? $data['footer'] : print_footer();
    $data['error_box']=(isset($data['error_box']) && is_array($data['error_box'])) ? print_msg_box('emsg',$data['error_box']) : (isset($data['error_box']) ? $data['error_box'] : '');
    $data['message_box']=(isset($data['message_box']) && is_array($data['message_box'])) ? print_msg_box('msg',$data['message_box']) : (isset($data['message_box']) ? $data['message_box'] : '');
        return _view($template_name,$data,$return_result,true);
}
/**
 * Draw standart table for user part
 * 
 * @param array $content
 * array(
 *  1=>array(
 *      'descr'=array(
 *          'text'=>output($value['descr']),                      //cell text
 *          'link'=>base_url().'news/show/'.md5($value['id']),    //cell link (optional)
 *          'class'=>'align_left',                                //cell css classes (optional) 
 *          'link_class'=>'first_class second_class'              //cell link css classes (optional) 
 *          )
 *       ),
 *       'name'=>'name_text'                                      //cell text         
 *  );
 * @param array $settings
 * array(
 *  'url'=>base_url().'news/all/',                //page url
 *  'table_widt'=>'700px',                        //table width (700,700px,25%)(optional)
 *  'table_class'=>'first_class second_class',    //css classes (optional)
 *  'order'=>array('column'=>'asc'),              //order params column=>direction (optional)
 *   'pager'=>array('current_page'=>$current_page, 'per_page'=>$per_page, 'pages'=>$pages),
 *                                                //pager params  (optional)
 *  //if is set array $settings['columns'] that will display only those columns that are described therein. 
 *  'columns'=>array(
 *  'date'=>array(                                 //column
 *      'width'=>'100px',                          //columt width (700,700px,25%) (optional)
 *      'name'=>'<{user_news_all_table_date}>',    //columt name text (optional)
 *      'sortable'=>true,                          //indicates whether the column sort (optional)
 *      'link'=>'param1/param2'                    //additional params for column sorting link http://url/pager_params/sort_params/[additional/params]
 *   )));    
 *
 * @return mixed
 */
function print_table($content,$settings=array())
{
    $data=array();
    $data['url']=isset($settings['url']) ? (preg_replace("/(\/\/)$/", "/", $settings['url'].'/')) : "";
    $data['table_class']=isset($settings['table_class']) ? $settings['table_class'] : "";
    $data['table_width']=isset($settings['table_width']) ? $settings['table_width'] : "100%";
    $data['columns']=array();
    $data['rows']=array();
    $data['if_sort_asc']=array();
    $data['if_sort_desc']=array();
    
    $order_column='0';
    $order_direction='0';
    if(isset($settings['order']) && is_array($settings['order']))
    {
        foreach($settings['order'] as $key=>$value)
        {
            $data['if_sort_asc']=(strtolower($value)=='asc') ? array(array()) : array();
            $data['if_sort_desc']=(strtolower($value)!='asc') ? array(array()) : array();
            $order_column=$key;
            $order_direction=(strtolower($value)=='asc') ? 'asc' :'desc';
        }
    }
    
    $data['pager']="";
    $p=isset($settings['pager']) ? $settings['pager'] : array();
    $p['current_page']=isset($p['current_page']) ? $p['current_page'] : 1;
    $p['per_page']=isset($p['per_page']) ? $p['per_page'] : config_get("PAGER","DEFAULT_PERPAGE");
    $p['pages']=isset($p['pages']) ? $p['pages'] : 1;
    
    if(isset($settings['pager']))
    {
        $data['pager']=html_pager($data['url'], $p['current_page'], $p['per_page'], $p['pages'], $order_column, $order_direction);
    }
    
    
    $i=0;
    foreach($content as $row)
    {
       $temp_row=(isset($settings['columns']) && is_array($settings['columns'])) ? $settings['columns'] : $row; 
       
       if(!count($data['columns']))
        {
            foreach($temp_row as $key=>$val)
            {
                if(isset($row[$key]))
                {
                    $value=$row[$key];
                    
                    if(!isset($settings['columns']) || isset($settings['columns'][$key]))
                    {
                        $column=array();
                        $column['column_id']=$key;
                        $column['column_name']=$key;
                        $column['column_width']='auto';
                        $column['if_column_sortable']=array();
                        $column['else_column_sortable']=array(array());
                        
                        if(isset($settings['columns']) && isset($settings['columns'][$key]))
                        {
                            $s=$settings['columns'][$key];
                            $column['column_link']=$data['url'].$p['current_page'].'/'.$p['per_page'].'/'.$column['column_id'].'/'.($order_column==$column['column_id'] ? ($order_direction=='asc'?'desc':'asc') : 'asc').(isset($s['link']) ? '/'.$s['link'] : "");
                            $column['column_name']=isset($s['name']) ? $s['name'] : $column['column_name']; 
                            $column['column_width']=isset($s['width']) ? $s['width'] : $column['column_width'];                        
                            if(isset($s['sortable']) && $s['sortable'])
                            {
                                $column['if_column_sortable']=array(array());
                                $column['else_column_sortable']=array();
                                $column['if_column_sort']=($order_column==$key) ? array(array()) : array();
                                $column['if_column_sort_style']=($order_column==$key) ? array(array()) : array();
                            }
                        }
                        $data['columns'][]=$column;
                    }
                }
            }            
        }
        
        
            $r=array();
            $r['if_odd']=($i%2) ? array() : array(array());
            $r['if_even']=($i%2) ? array(array()) : array();
            $r['cells']=array();
            foreach($temp_row as $key=>$val)
            {
                if(isset($row[$key]))
                {
                    $value=$row[$key];
                    if(!isset($settings['columns']) || isset($settings['columns'][$key]))
                    {
                        $c=array();
                        $c['cell_class']="";
                        $c['if_cell_link']=array();
                        $c['else_cell_link']=array(array());
                        if(is_array($value))
                        {
                            $c['cell_text']=isset($value['text']) ? $value['text'] : "";
                            $c['cell_class']=isset($value['class']) ? $value['class'] : $c['cell_class'];
                            if(isset($value['link']))
                            {
                                $c['if_cell_link']=array(array());
                                $c['else_cell_link']=array();
                                $c['cell_link']=$value['link'];
                                $c['cell_link_class']=isset($value['link_class']) ? $value['link_class'] : "";
                            }                
                        }
                        else
                        {                
                            $c['cell_text']=$value;
                        }
                        $r['cells'][]=$c;
                    }
                }
            }
            $data['rows'][]=$r;        
            $i++;
    }  
    fb($data);
    return _view('user/table.html',$data,true,true);
}

/**
 * Enter description here...
 *
 * @param array $data
 * @param unknown_type $is_admin
 * @return string
 */
function client_script_config($data=array(),$is_admin=false)
{
$CI = &get_instance();    
$data['user']=isset($data['user']) ? $data['user'] : array();
$data['user']['authorized']=(intval($CI->user_auth_model->uid)>0)?"true":"false";

return "window.server=".create_temp_vars_set($data).";";
}

/**
 * Enter description here...
 *
 * @return unknown
 */
function get_additional_menu_items()
{
    $CI = &get_instance();
    $CI->load->model("user_model");
    // return an array(sid=>page_title)
    return $CI->user_model->Get_pages_list();
}

/**
 * Enter description here...
 *
 * @param array $post
 * @param mixed $order_column
 * @param integer $pagers_count
 * @param string $order_direction
 * @return array
 */
function page_and_sort($post,$order_column="",$pagers_count=2,$order_direction="asc")
{
    $CI = &get_instance();
    
    $query = $CI->db->get();
    
    $all_items=$query->num_rows();
    
    $pagers=pager_ex($post,$all_items,$order_column,$pagers_count,$order_direction);
    $params=$pagers['params'];
    
    $order_query=" ORDER BY `".$params['column']."` ".$params['order'];
    $limit_query=" LIMIT ".$params['offset'].", ".$params['limit'];
    
    $query_string=$CI->db->last_query().$order_query.$limit_query;
    
    $query = $CI->db->query($query_string);
    
    $data=array();
    $data['pagers'] = $pagers;
    $data['query'] = $query;
    return $data;
}

/**
* Generates selectbox for page selection and selectbox for number of items per page selection
* generates params of order tables
*
* @author Onagr
* @param array $post
* @param integer $all_items
* @param mixed $order_column
* @param integer $pagers_count
* @param string $order_direction
* @return array
*/
function pager_ex($post,$all_items,$order_column="",$pagers_count=2,$order_direction="asc")
{

    //print_r(array($post,$all_items,$order_column,$pagers_count,$order_direction));
    if(!is_array($post)||!isset($post['pager']))
    {
        $pager=array();
        $pager[0]=is_array($order_column) ? $order_column[0] : $order_column;
        $pager[1]=$order_direction; //added by val petruchek
        $pager[2]=config_get('pager','default_perpage');
        $pager[3]='1';
    }
    else
    {
        $pager=$post['pager'];
    }
    if(is_array($order_column))
    {
        $pager[0]=in_array($pager[0],$order_column) ? $pager[0] : $order_column[0];
    }
    $ppage_set = get_perpagelist();
    $per_page=intval($pager[2]);
    $all_page=ceil(intval($all_items)/$per_page);
    $all_page=($all_page<1) ? 1 : $all_page;
    $cur_page=intval($pager[3])>$all_page?$all_page:intval($pager[3]);
    $cur_col="$pager[0]";
    $cur_sort="$pager[1]";
    $params=array('ccol'=>$cur_col,'csort'=>$cur_sort,'ppage'=>$per_page,'cpage'=>$cur_page);
    $result=array();
    $result['pager']=array();
    for($i=0;$i<$pagers_count;$i++)
    {
        $result['pager'][$i] = perpage_selectbox($ppage_set,'pagerHandler',$params,$per_page) . pagination_title(($cur_page-1)*$per_page,$per_page,$all_items) .page_selectbox($all_page ,'pagerHandler',$params, $cur_page);
    }
    $result['params']=array();
    $result['params']['limit']=$per_page;
    $result['params']['offset']=($cur_page-1)*$per_page;
    $result['params']['column']=$pager[0];
    $result['params']['order']=$pager[1];
    $result['params']['count']=$all_items;
    return $result;
}
/**
 * Sets pager param
 *
 * @param integer $page
 * @param integer $limit
 * @param integer $count
 * @return array
 */
function pager_params($page,$limit,$count)
{
    $params=array();
    $params['count']=$count;
    $params['limit']=$limit>0 ? ($limit>$count ? $count : $limit) : intval(config_get('pager','default_perpage'));
    $params['pages']=($params['limit']>0 && $params['count']>0) ? ceil($params['count']/$params['limit']) :1;
    $params['page']=$page>0 ? ($page>$params['pages'] ? $params['pages'] : $page) : 1;
    $params['offset']=($params['page']-1)*$params['limit'];

    return $params;
}


/**
* Generates text "showing items X..Y from Z", called by pager_ex()
* uses view: admin/common/pagination_title
*
* @param integer X-1
* @param integer perpage value
* @param integer Z
* @return string
*
* @author Val Petruchek
* @copyright 2008
*/
function pagination_title($start_1, $per_page, $total)
{
    if ($total == 0)
    {
        return "";
    }
    $data = array();
    $data['first'] = $start_1 + 1;
    $data['last']  = min ($total, $start_1+$per_page);
    $data['total'] = $total;
    $CI = &get_instance();
    $rv = $CI->load->view("admin/common/pagination_title", $data, true);
    return $rv;
}

/**
* Generates selectbox for page selection
*
* @author Drovorubov
* @param integer $count
* @param string $js_handler
* @param array $js_handler_params
* @param integer $curpage
* @return string
*/
function page_selectbox($count,$js_handler,$js_handler_params,$curpage=1)
{
    static $pg_id_cnt = 0;
    $pg_id_cnt++;

    if( intval($count) <= 0 || intval($curpage) <= 0)
    {
        return false;
    }

    if( $curpage > $count )
    {
        return false;
    }
    // Make params string
    $params = '';
    if( is_array($js_handler_params) || !empty($js_handler_params) )
    {
        foreach( $js_handler_params as $key=>$val )
        {
            $params .= $key . ":'" . output($val) . "',";
        }
    }
    // Generate pager in HTML code
    $data = array();
    $data['count'] = $count;
    $data['curpage'] = $curpage;
    $data['js_handler'] = $js_handler;
    $data['params'] = $params;
    $data['pg_id_cnt'] = $pg_id_cnt;
    $rv = '';
    $CI =& get_instance();
    $rv=$CI->load->view("admin/common/page_selectbox", $data, true);

    return $rv;
}



/**
* Creates selectbox for number of items per page selection
*
* @author Drovorubov
* @param array $perpages
* @param string $js_handler
* @param array $js_handler_params
* @param integer $cur_perpage
* @return string
*/
function perpage_selectbox($perpages,$js_handler,$js_handler_params,$cur_perpage)
{
    static $pp_id_cnt = 0;
    $pp_id_cnt++;

    if( !is_array($perpages) || empty($perpages) )
    {
        $perpages = get_perpagelist();
    }
    // Make params string
    $params = '';
    if( is_array($js_handler_params) || !empty($js_handler_params) )
    {
        foreach( $js_handler_params as $key=>$val )
        {
            $params .= $key . ":'" . output($val) . "',";
        }
    }
    // Check current per page value
    if( intval($cur_perpage) <= 0 )
    {
        $cur_perpage = config_get('PAGER','default_perpage');
    }
    // Generate pager in HTML code
    $data = array();
    $data['perpages'] = $perpages;
    $data['cur_perpage'] = $cur_perpage;
    $data['js_handler'] = $js_handler;
    $data['params'] = $params;
    $data['pp_id_cnt'] = $pp_id_cnt;
    $rv = '';
    $CI =& get_instance();
    $rv=$CI->load->view("admin/common/per_page_selectbox", $data, true);

    return $rv;
}



/**
* Create div with selectboxes for perpage and pager items.
*
* @author Drovorubov
* @param string $url
* @param integer $current_page
* @param integer $current_per_page
* @param integer $pages
* @param string $sort_by
* @param string $sort_how
* @return mixed
*/
function html_pager($url, $current_page, $current_per_page, $pages, $sort_by='', $sort_how='')
{
    if( empty($url) )
    {
        return false;
    }

    if( intval($current_page) <= 0 || intval($current_per_page) <= 0 ||
            intval($pages) <= 0 )
    {
        return false;
    }

    if( $current_page > $pages )
    {
        return false;
    }

    // Generate pager in HTML code
    $data = array();
    $data['current_per_page'] = $current_per_page;
    $data['pages'] = $pages;
    $data['current_page'] = $current_page;
    $data['url'] = $url;
    $data['sort_by'] = $sort_by;
    $data['sort_how'] = $sort_how;
    $data['sort_link'] = ($sort_by != '') ? '/'.$sort_by.'/'.$sort_how : '';
    $rv = '';
    $CI =& get_instance();
    
    $perpage_list=explode(",",config_get("PAGER","PERPAGE_LIST"));
    foreach($perpage_list as $value)
    {
        $value=intval(trim($value));
        $data['perpage_list'][]=array('value'=>$value,'selected'=>($data['current_per_page']==$value?'selected':''));
    }
    $data['page_selector']=array();
    if($data['pages']>1)
    {
        $page_range=array();
        for($i=1;$i<=$data['pages'];$i++)
        {
            $page_range[]=array('value'=>$i,'selected'=>($data['current_page']==$i?'selected':''));
        }
        $prev_pages=array();
        if($data['current_page']>1)
        {
            $prev_pages[]=array('prev_page'=>$data['current_page']-1);
        }
        $next_pages=array();
        if($data['pages'] > $data['current_page'])
        {
            $next_pages[]=array('next_page'=>$data['current_page']+1);
        }
        $data['page_selector'][]=array(
        'page_range'=>$page_range,
        'prev_pages'=>$prev_pages,
        'next_pages'=>$next_pages
        );        
    }
    
    //fb($data['perpage_list']);
    //$rv=$CI->load->view("default/unreg/user/common/pager", $data, true);
    $rv=_view("user/common/pager.html", $data, true,true);
    
    return $rv;
}

/*
* Gets per page list from config file
*
* @author Drovorubov
* @return array
*/
function get_perpagelist()
{
    $rv = array();
    $s = config_get('PAGER','perpage_list');
    $rv = explode(',',$s);
    return $rv;
}



/**
* Gets email keys, wraps them into option tags and returns html string.
*
* @author Drovorubov
* @param string $type /'user', 'admin'
* @return string $rv
*/
function get_email_keys_str($type)
{
    $CI = &get_instance();
    $CI->load->model('mail_model');
    $rv = '';
    //Getting Email Keys Array
    $tmp = array();
    $tmp = $CI->mail_model->get_template_variables($type);
    //Wraps Email Key with option tags
    foreach( $tmp as $arr_key=>$val)
    {
        $val = output($val);
        $rv .= "<option value=\"##".$val."##\">".$val."</option>";
    }
    return $rv;
}
/**
 * Enter description here...
 *
 */
function print_user_menu()
{
    $CI = &get_instance();
    $CI->load->model("market_model", "market");

    $uid=intval($CI->user_auth_model->uid);
    $data=array();
    $data['pcnt']=$CI->market->get_user_product_cnt($uid);
    _view("user/menu", $data);
}


/**
* Converts array to string (recursively), breaks and capitalizes keys using underline
*
* @param array to convert
* @param integer left indent
* @return string
*
* @author Val Petruchek
* @copyright 2008
*/
function array_to_string($array, $padding=0)
{
    $result = "";
    foreach($array as $key=>$value)
    {
        if (is_array($value))
        {
            $result .= str_repeat("&nbsp;",$padding*4).output(ucwords(str_replace("_"," ",$key))).": <br />".array_to_string($value,$padding+1);
        }
        else
        {
            $result .= str_repeat("&nbsp;",$padding*4).output(ucwords(str_replace("_"," ",$key))).": ".nl2br(output($value))."<br />";
        }
    }
    return $result;
}

/**
* Converts timestamp to string applying formatting from configuration
*
* @param int timestamp or mysql yyyy-mm-dd hh:ii:ss
* @param boolean show time or not
* @return string
*
* @author Val Petruchek
* @copyright 2008
*/
function nsdate($timestamp, $showtime = true)
{
    if($timestamp=='' || $timestamp == '0000-00-00' || $timestamp =='0000-00-00 00:00:00')
    {
        return "";
    }
    if (!is_numeric($timestamp))
    {
        //Big date hack
        if(eregi("^[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}",$timestamp)!==false)
        {
            if(@preg_match_all("/^[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}/", $timestamp, $source) && $source[0] && isset($source[0][0]))
            {
                $d=explode("-",$source[0][0]);
                $year=$d[0];
                $timestamp=str_replace(implode("-",$d), (((intval($year)%4)==0) ?"2000":"2001")."-".$d[1]."-".$d[2], $timestamp);
            }
        }
        //End of big date hack

        //it means we have mysql date, need to convert it to unix timestamp
        $timestamp = strtotime($timestamp);
    }
    $format = canonic_date_format();
    $result=date($format, $timestamp);

    //Big date hack
    if(isset($year))
    {
        $f=array_flip(explode($format{1},strtolower($format)));
        $d=explode($format{1},$result);
        $d[$f['y']]=(strpos($format,"Y")!==false) ? $year : substr($year,2);
        $result=implode($format{1},$d);
    }
    //End of big date hack

    if ($showtime)
    {
        $result.= date(" H:i:s", $timestamp);
    }
    return $result;
}
/**
 * Enter description here...
 *
 * @param string $date
 * @return unknown
 */
function time_left($date)
{
    if(strtotime('now')>strtotime($date))
    {
        return false;
    }
    $t=(strtotime($date)-strtotime('now'));
    $h =floor($t/3600);
    $m =floor(($t%3600)/60);
    $s =floor(($t%3600)%60);
    return  ($h<10?"0":"").$h.":".($m<10?"0":"").$m.":".($s<10?"0":"").$s;
}

/**
* Converts needsecure date format to canonic PHP date() format
* Takes format from config_get('system','config','date_format')
*
* @return string
*
* @author Val Petruchek
* @copyright 2008
*/
function canonic_date_format()
{
    $format = config_get('system','config','date_format');
    if (!$format) $format = "m/d/y";
    return str_replace(array('D','M'),array('j','n'),$format);
}


/**
* Returns an array with names of the months
*
* @author Drovorubov
* redone by Val Petruchek
* @param string $type ('long' or 'short' values)
* @return array
*/
function get_month_names($type='long')
{
    static $result_full, $result_short;

    if (!isset($result_full))
    {
        //Prepare months array
        $string = ""; //concatenating keys into this string
        $separator = "|!|!|\t\r\n"; //using unique separator
        for ($i=1;$i<=12;$i++)
        {
            $string .= "<{month_name_$i}>";
            if ($i != 12)
            {
                $string .= $separator;
            }
        }

        $result_full = explode($separator, replace_lang($string)); //breaking string into messages

        $result_short = array();

        for ($i=0;$i<12;$i++)
        {
            $result_short[$i] = mb_substr($result_full[$i], 0, 3); //I'm not sure this is good idea for short motnhs, but let it be for now
        }
    }

    return ($type == "short") ? $result_short : $result_full;
}


?>
