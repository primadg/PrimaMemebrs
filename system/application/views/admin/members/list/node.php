
          <tr class="<?php echo $tr_class;?>">
            <td><input type="checkbox" id="<?php echo "mbr_id-".$row['id']; ?>" <?php echo $is_demo_user ? 'disabled="disabled"' : "" ?> />
            </td>
            <td class="left"><?php echo $row['login'];?> <?php  if ($row['suspended'] == 1) {echo create_tooltip_div($row['susp_reason']['descr'], true);} ?></td>
            <td><a href="mailto:<?php echo $row['email'];?>"><?php echo $row['email2show'];?></a></td>
            <td><?php echo nsdate($row['reg_date'],false);?></td>
            <td>
                <?php 
                    if(isset($row['subscriptions']['price_sum']))
                    {
                        echo $row['subscriptions']['price_sum'];
                    }
                    if(isset($row['subscriptions']['subscr_count']))
                    {
                        echo "/".$row['subscriptions']['subscr_count'];
                    }
                ?>
            </td>
            <!--<td><?php 
                    if( $row['approve'] == 1 )
                    {
                ?>    
                    <img src="<?php echo base_url();?>img/ico_active.png" width="21" height="21" />                
                <?php
                    }
                ?>
            </td>
            <td><?php 
                    if( $row['activate'] == 1 )
                    {
                ?>    
                    <img src="<?php echo base_url();?>img/ico_active.png" width="21" height="21" />                
                <?php
                    }         
                ?>
            </td>-->
            <td>
            	<a style="cursor:pointer;" onclick="load_member_list({is_search: true, letter: null, is_oper: false, is_icon: true, icon_id: '<?php echo $row['id'];?>', icon_type: '<?php echo ($row['activate']==0)?"do_status_activate":"do_status_notactivate"?>' }); return false;" title=" <?php  if ($row['activate'] == 1) { ?><{tool_tip_status_inactivate}><?php  } else { ?> <{tool_tip_status_activate}><?php  } ?>">
                    <?php  if ($row['activate'] == 1) { ?>
                    	<img src="<?php echo base_url()?>img/ico_active.png" alt="<{tool_tip_status_inactivate}>"   width="16" height="16" style="float:left; padding-left:10px;"/>
                    <?php  }
					else { ?>
                    	<img src="<?php echo base_url()?>img/ico_active_off.png" alt="<{tool_tip_status_activate}>" width="16" height="16" style="float:left; padding-left:10px;"/>
                    <?php  } ?>
                </a>
            	
                <a style="cursor:pointer;" onclick="load_member_list({is_search: true, letter: null, is_oper: false, is_icon: true, icon_id: '<?php echo $row['id'];?>', icon_type: '<?php echo ($row['approve']==0)?"do_status_approve":"do_status_notapprove"?>' }); return false;" title=" <?php  if ($row['approve'] == 1) { ?><{tool_tip_status_disapprove}><?php  } else { ?> <{tool_tip_status_approve}><?php  } ?>">
					<?php  if ($row['approve'] == 1) { ?>
                    	<img src="<?php echo base_url()?>img/ico_active.png" alt="<{tool_tip_status_disapprove}>"   width="16" height="16" style="float:left; padding-left:6px;"/>
                    <?php  }
					else { ?>
                    	<img src="<?php echo base_url()?>img/ico_active_off.png" alt="<{tool_tip_status_approve}>" width="16" height="16" style="float:left; padding-left:6px;"/>
                    <?php  } ?>
                </a>
                
                <a style="cursor:pointer;" onclick="load_member_list({is_search: true, letter: null, is_oper: false, is_icon: true, icon_id: '<?php echo $row['id'];?>', icon_type: '<?php echo ($row['suspended']==0)?"do_status_suspend":"do_status_unsuspend"?>' }); return false;" title=" <?php  if ($row['suspended'] == 1) { ?><{tool_tip_status_unsuspend}><?php  } else { ?> <{tool_tip_status_suspend}><?php  } ?>">
                    <?php  if ($row['suspended'] == 1) { ?>
                    	<img src="<?php echo base_url()?>img/ico_active_red.png" alt="<{tool_tip_status_unsuspend}>"   width="16" height="16" style="float:left; padding-left:6px;"/>
                    <?php  }
					else { ?>
                    	<img src="<?php echo base_url()?>img/ico_active_off.png" alt="<{tool_tip_status_suspend}>" width="16" height="16" style="float:left; padding-left:6px;"/>
                    <?php  } ?>
                </a>
            </td>
            <td>
			  <a href="#" title="<{admin_member_control_img_tooltip_view}> '<?php echo $row['login'];?>'"
              onClick="load_member_info('<?php echo $row['id'];?>','load_member_list'); return false;">
              <img alt="<{admin_member_control_img_tooltip_view}>" src="<?php echo base_url();?>img/ico_coupon.png" width="16" height="16" /></a>
              <a href="#" title="<{admin_member_control_img_tooltip_edit}> '<?php echo $row['login'];?>'"
              		onClick="load_accnt_panel('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_edit}> '<?php echo $row['login'];?>'" src="<?php echo base_url();?>img/page_edit16.png" width="16" height="16" /></a><!--&nbsp;
              <?php  if ($row['suspended'] != 1 && $row['activate'] != 0 && $row['approve'] != 0) 
			  	 { 
			  ?>
                    <a href="#" title="<{admin_member_control_img_tooltip_suspend}> '<?php echo $row['login'];?>'"
              		onClick="load_member_list({is_search: true, letter: null, is_oper: false, is_icon: true, icon_id: '<?php echo $row['id'];?>' });return false;"><img alt="<{admin_member_control_img_tooltip_suspend}>" src="<?php echo base_url();?>img/ico_suspend.png" width="16" height="16" /></a>
              <?php  
				 }
				 else 
				 {
			  ?>
                    <?php  if ($row['suspended'] == 1) {?>
                    <a href="#" title="<{admin_member_control_img_tooltip_unsuspend}> '<?php echo $row['login'];?>'"
              		onClick="unsuspend_member('<?php echo $row['id'];?>', 'member_list'); return false;"><img alt="<{admin_member_control_img_tooltip_unsuspend}>" src="<?php echo base_url();?>img/ico_approve_decline16.png" width="16" height="16" /></a>
                    <?php  }
						else 
						{ ?>
                        <a href="#" title="<{admin_member_control_img_tooltip_suspend}> '<?php echo $row['login'];?>'"
              			onClick="load_member_list({is_search: true, letter: null, is_oper: false, is_icon: true, icon_id: '<?php echo $row['id'];?>' });return false;"><img alt="<{admin_member_control_img_tooltip_suspend}>" src="<?php echo base_url();?>img/ico_suspend.png" width="16" height="16" /></a>
                     <?php  } ?>
              <?php 
				 }
			  ?>-->
              <a href="#" title="<{admin_member_control_img_tooltip_delete}> '<?php echo $row['login'];?>'"
              onClick="delete_member('<?php echo $row['id'];?>'); return false;"><img alt="<{admin_member_control_img_tooltip_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>
            </td>
          </tr>
