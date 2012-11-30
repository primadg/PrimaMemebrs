<?php 
$free=$day + $month + $month3 + $month6 + $year + $unlimit;
?>

<div> <div class="body_header"> <div style="float: left;"><img alt="add product" src="<?php echo base_url()?>img/page_edit32.png" width="32" height="32" /></div>
          <div class="header_pad">
            <div class="header_subject"><{product_save_page_title}></div>
            <div class="header_comment"><{product_save_page_descr}></div>
          </div>
        </div>


        <div class="mess_err" style="display: none; width: 500px; margin: 0 auto;" id="form_errors">

            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>

            <div class="box_err" style="display: none;"><{product_save_error_name}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_poster_ext}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_poster_size}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_descr}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_groups}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_price}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_trial_price}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_trial_period}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_discount_value}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_dirs}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_model_error}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_free_product}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_discount_large_price}><br /></div>

            <div class="box_err" style="display: none;"><{product_save_error_poster_remove_error}><br /></div>
            
            <div class="box_err" style="display: none;"><{demo_msg_er_functionality_disabled}><br /></div>
            
            <div class="box_err" style="display: none;"><{product_save_error_host_type_error}><br /></div>
            
            <div class="box_err" style="display: none;"><{demo_msg_er_functionality_group_disabled}><br /></div>
            
            <div class="box_err" style="display: none;" id="form_custom_error"></div>

            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>

        </div>


        <div class="mess" style="width: 500px; margin: 0 auto; display: none;" id="form_msgs">
            <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>
                <div style="display: none;" class="box"><{product_save_poster_removed}></div>
            <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>
        </div>


        <?php 
        if(isset($not_exists))
        {
            ?>
            <div class="mess_err" style="width: 500px; margin: 0 auto;" id="add_errors">

                <span><b class="r3">&nbsp;</b><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b></span>

                    <div class="box_err"><{product_save_not_exists}><br /></div>

                <span><b class="r1">&nbsp;</b><b class="r1">&nbsp;</b><b class="r3">&nbsp;</b></span>

            </div>

            <?php 
        }
        else
        {/////////////////////////////BEGIN FIELD LIST
        ?>


        <input id="pid" type="hidden" style="width: 400px;" value="<?php echo output($id)?>" />



        <table style="width: 90%;" class="settings table_pos" style="margin-top: 10px;">
        <tbody id="product_save_form">
            <tr class="glav">
                <td align="right" class="table_first_td"><{product_save_product_name}></td><td><span style="color: red;">*</span></td>
                <td nowrap><input id="p_name" type="text" style="width: 400px;" value="<?php echo output($name)?>" />
                <?php if(Functionality_enabled('admin_multi_language')===true){?>
                <a style="text-decoration: none;" class="link_buttons" onClick="fieldLangsEdit(4,<?php echo (int)$product_id.",'<{product_save_not_saved_confirmation}>'"?>); return false;" href="#" title="<{product_list_edit_lang_button_alt}>"><img alt="<{product_list_edit_lang_button_alt}>" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" />&nbsp;</a>
                <?php }?>
                </td>
            </tr>
            <tr class="glav">
                <td valign="top" align="right" style="padding-top: 7px;"><{product_save_product_descr}></td><td style="vertical-align:top; padding-top:7px;"><span style="color: red;">*</span></td>
                <td><textarea id="p_descr" style="width: 400px; height: 100px;"><?php echo output($descr)?></textarea></td>
            </tr>
          <tr class="glav">
                <td align="right" valign="top" style="padding-top:7px;"><{product_save_product_groups}></td><td style="vertical-align:top; padding-top:7px;"><span style="color: red;">*</span></td>
                <td>

                <select style="width: 410px;" size="5" id="p_groups">
                    <?php

                        if($product_groups && @count($product_groups))
                        foreach($product_groups as $group)
                        {
                    ?>
                            <option <?php echo ($gid==$group['id']?" selected='true' ":"")?> value="<?php echo (int)$group['id'];?>">
                                <?php echo output($group['name']);?>
                            </option>
                    <?php
                        }
                    ?>
                </select>


                </td>
            </tr>
            
            <tr class="glav">
                <td align="right" valign="top"><{product_save_member_groups}></td><td><span style="display:none;" id="member_group_warning"><{product_save_member_groups_warning_not_visible}></span></td>
                <td>
                <div style="text-align:right;width:410px;height:15px;">
                <{product_save_member_groups_description}>
                </div>
                <div style="overflow:auto;border: 1px solid gray;width: 410px;height:75px;" id="_m_groups">
                <table style="width:100%">
                <?php  if($member_groups && @count($member_groups))
                        foreach($member_groups as $group){ ?>
                            <tr class="dropbox_table">
                            <td><?php echo output($group['name']);?></td>
                            <td style="width:6%">
                            <input onclick="clickMemberGroupsVisible(this);" class="member_group_visible" value="<?php echo intval($group['id']);?>" <?php echo isset($selected_member_groups[$group['id']])?"checked":"";?> type="checkbox"/>
                            </td>
                            <td style="width:6%">
                            <input class="member_group_available" value="<?php echo intval($group['id']);?>" <?php echo isset($selected_member_groups[$group['id']])?"":"disabled";?> <?php echo isset($selected_member_groups[$group['id']]) && intval($selected_member_groups[$group['id']])>0 ? "checked" : "";?> type="checkbox"/>
                            </td>
                            </tr>                            
                    <?php  } ?>
                </table>
                </div>
                </td>
            </tr>
            <tr class="glav <?php echo isset($only_free_product)?"hidden_element":"";?>">
                <td align="right">
                    <{product_add_free}>
                </td>
                <td></td>
                <td>
                    <input <?php echo (isset($only_free_product) || !$free)?"checked=checked":"";?> id="product_free" type="checkbox" onclick="click_product_free();" />
                </td>
            </tr>
          <tr class="glav <?php echo isset($only_free_product)?"hidden_element":"";?>">
            <td align="right" valign="top" style="padding-top: 12px;"><{product_save_product_prices}> </td>
            <td></td>
            <td>

            <table>
              <tr>
                <td align="right"><{product_save_product_price_day}></td>
                <td><input <?php echo ($free?"" : "disabled='true' ")?> id="p_p_day" type="text" style="width: 80px;" value="<?php echo output($day)?>"/></td>
              </tr>
              <tr>
                <td align="right"><{product_save_product_price_month}></td>
                <td><input <?php echo ($free?"" : "disabled='true' ")?> id="p_p_month" type="text" style="width: 80px;" value="<?php echo output($month)?>" /></td>
              </tr>
              <tr>
                <td align="right"><{product_save_product_price_month3}></td>
                <td><input <?php echo ($free?"" : "disabled='true' ")?> id="p_p_month3" type="text" style="width: 80px;" value="<?php echo output($month3)?>" /></td>
              </tr>
              <tr>
                <td align="right"><{product_save_product_price_month6}></td>
                <td><input <?php echo ($free?"" : "disabled='true' ")?> id="p_p_month6" type="text" style="width: 80px;" value="<?php echo output($month6)?>" /></td>
              </tr>
              <tr>
                <td align="right"><{product_save_product_price_year}></td>
                <td><input <?php echo ($free?"" : "disabled='true' ")?> id="p_p_year" type="text" style="width: 80px;" value="<?php echo output($year)?>" /></td>
              </tr>
              <tr>
                <td align="right"><{product_save_product_price_year5}></td>
                <td><input <?php echo ($free?"" : "disabled='true' ")?> id="p_p_year5" type="text" style="width: 80px;" value="<?php echo output($unlimit)?>" /></td>
              </tr>
            </table>

            </td>
          </tr>

    	  <tr class="glav <?php echo isset($only_free_product)?"hidden_element":"";?>">
    				<td align="right"><{product_save_product_recouring}></td><td><span style="color: red;">*</span></td>
    				<td>
      				<input <?php echo ($free?"" : "disabled='true' ")?> type="checkbox" id="p_rec_yes" style="border: 0px;" <?php echo ($is_recouring==2?"checked='true' ":"")?> /><{product_save_product_recouring_yes}>
    				</td>
    			</tr>
    			<tr class="glav <?php echo isset($only_free_product)?"hidden_element":"";?>">
    				<td align="right"><{product_save_product_discount}></td><td><span style="color: red;">*</span></td>
    				<td>
      				<input <?php echo ($free?"" : "disabled='true' ")?> <?php echo ( $discount_type==1?" checked='true'":"")?> type="radio" name="disc_type" id="p_disc_type_percent" style="border: 0px;" /> <{product_save_product_discount_percent}>
      				<input <?php echo ($free?"" : "disabled='true' ")?> <?php echo ($discount_type==2?" checked='true'":"")?> type="radio" name="disc_type" id="p_disc_type__val" style="border: 0px;" /> <{product_save_product_discount_value}>
      				<input <?php echo ($free?"" : "disabled='true' ")?> value="<?php echo output($discount)?>" type="text"  id="p_disc_value" style="margin-left: 20px; width: 30px;" value="0" />
    				</td>
    			</tr>
    			<tr class="<?php echo isset($only_free_product)?"hidden_element":"";?>">
    				<td align="right"><{product_save_product_trial_price}></td>
                    <td></td>
    				<td><input <?php echo ($free?"" : "disabled='true' ")?> type="text" style="width: 80px;" id="p_trial_price" value="<?php echo output($trial_price)?>" /></td>
    			</tr>
    			<tr class="<?php echo isset($only_free_product)?"hidden_element":"";?>">
    				<td align="right"><{product_save_product_trial_duration}></td>
                    <td></td>
    				<td>
    				<select <?php echo ($free?"" : "disabled='true' ")?> style="width: 80px;" id="p_trial_period_type">
                        <option <?php echo ($trial_period_type=="day"?" selected='true' ":"")?> value="day"><{product_save_product_trial_duration_day}></option>
                        <option <?php echo ($trial_period_type=="month"?" selected='true' ":"")?> value="month"><{product_save_product_trial_duration_month}></option>
                        <option <?php echo ($trial_period_type=="year"?" selected='true' ":"")?> value="year"><{product_save_product_trial_duration_year}></option>
    				</select>
    				<input <?php echo ($free?"" : "disabled='true' ")?> type="text" id="p_trial_period_value" value="<?php echo output($trial_period_value)?>" style="width: 80px;" />
    				</td>
    			</tr>

        <?php //***********Functionality limitations***********
        if(count($product_types)>2)
        {?>
            <tr>
            <td align="right"><{product_add_product_type}></td>
            <td></td>
            <td><{product_save_product_type_<?php echo intval($product_type);?>}><input type="hidden"  value="<?php echo intval($product_type);?>" name="product_type" id="p_product_type" /></td>
            </tr>
            <?php 
        } //*******End of functionality limitations******** 
        if(Functionality_enabled('admin_product_protected')===true && $product_type==PRODUCT_PROTECT)
        {
        ?>
            
    			<tr class="glav">
    				<td align="right" valign="top" style="padding-top: 5px;"><{product_save_product_dirs}></td>
                    <td></td>
    				<td>
                    <select id="p_dirs" style="width: 410px; height: 100px;" <?php echo ($product_type!=PRODUCT_PROTECT)?'disabled="disabled"':"";?> multiple>

                        <?php

                            if($protect_dirs && @count($protect_dirs))
                            foreach($protect_dirs as $dir)
                            {
                       	?>
                                <option <?php echo ((in_array(array('id'=>$dir['id'],'name'=>$dir['name']), $product_dirs) === true) ?"selected='true'":"")?> value="<?php echo (int)$dir['id'];?>">
                                    <?php echo output($dir['name']);?>
                                </option>
                        <?php
                            }
                        ?>

        				</select>
    				</td>
    			</tr>
        <?php 
        }
        //***********Functionality limitations***********
        if(Functionality_enabled('admin_product_hosted')===true  && $product_type==PRODUCT_HOSTED)
        {
        ?>

            <tr class="glav">
                <td align="right" valign="top" style="padding-top: 5px;"><{admin_product_hosted_save_host_plans}></td><td><span style="color: red;">*</span></td>
                <td>
                    <select id="p_hosts" style="width: 410px;" <?php echo ($product_type!=PRODUCT_HOSTED)?'disabled="disabled"':"";?>>

                    <?php

                        if($host_plans && @count($host_plans))
                        foreach($host_plans as $host_plan)
                        {                        
                    ?>
                            
                            <option <?php echo (in_array($host_plan, $product_host_plans) ?"selected='selected'":"")?> value="<?php echo (int)$host_plan['id'];?>">
                                    <?php echo word_wrap(output($host_plan['name']),45,2);?>
                            </option>
                    <?php
                        }
                    ?>

                    </select>
                </td>
            </tr>
        <?php 
        } //*******End of functionality limitations******** 
        ?>
            
  			</tbody>
            <tfoot>
                <tr class="glav">
                    <td align="center" colspan="3" id="img_upload_label" style="display: none;">
                        <div class="header_comment"><{product_uploading_image}></div>
                    </td>
                    <td align="center" colspan="3" id="img_upload_wait" style="display: none;">
                        <div class="header_comment"><{product_uploading_wait}></div>
                    </td>
                </tr>
                <tr class="glav">
                	<td align="right">
                    	<{product_save_product_image}> (<?php echo $upload_params['max_width']." x ".$upload_params['max_height'];?>) <?php echo $upload_params['max_size'];?> kb
                    </td>
                    <td></td>
                    <td>
                    	<form target="img_upload_iframe" action="<?php echo base_url()?>/product/iframe" method="POST" enctype="multipart/form-data" id="img_upload_form">
                            <input id="p_poster" name='poster' type="file" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input style="display: none;" id="img_upload_button" type='button' class="button_big" value="<{product_upload_image}>" onclick="click_image_upload();" />
                            <input type='hidden' name='pid' />
                        </form>
                    </td>
                </tr>
                <tr class="glav">
                	<td></td>
                    <td></td>
                    <td valign="top" align="left" style="padding-top: 7px;">

                        <?php 
                        if($image)
                        {?>
                            <div id="product_image">
                                <a target="blank" href="<?php echo base_url().config_get("product_posters", "path_original").$image?>">
                                    <img src="<?php echo base_url().config_get("product_posters", "path_previews").$image?>" />
                                </a>
                                <?php  //if (!defined('NS_DEMO_VERSION')) 
									//{ ?><br/><a href="#" onclick="remove_poster('<?php echo output($id)?>'); return false;" ><{product_uploading_remove_poster}></a>
                                <?php    //} ?>
                            </div>
                        <?php 
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                	<td colspan="3" align="center" style="padding-top:15px;">
                    	<input type="button" id="add_product_buttons" class="button_big" value="<{product_save_button_add}>" onClick="click_save();" />
                &nbsp;
            <input type="button" class="button_big" value="<{product_save_cancel}>" onClick="product_list_prepare_pager({});" />
                    </td>
                </tr>
            </tfoot>
            </table>
        <?php 
        }
        /////////////////////////////////////// END FIELD LIST
        ?>

      </div>
<br />

    <!--
        IFRAME FOR ASYNC FILE UPLOAD
        ##########################################################################################################
    -->

    <iframe style='display: none;' name="img_upload_iframe" id="img_upload_iframe">
    </iframe>

    <!-- ########################################################################################################## -->
