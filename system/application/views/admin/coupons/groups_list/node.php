
<!-- Coupons groups list node -->

        <tr class="<?php echo $tr_class;?>">
          <td><?php echo $row['id'];?></td>
          <td><a href="#"
          onClick="show_coupons('<?php echo $row['id'];?>',{back:'coupons_group_list'});return false;"
          ><?php echo $row['cnt'];?></a></td>
          <td><?php echo isset($row['start_time']) ? nsdate($row['start_time'],false) :"";?></td>
          <td><?php echo isset($row['end_time']) ? nsdate($row['end_time'],false) :"";?></td>
          <td><?php echo $row['available_use'];?>/<?php echo $row['coupons_used'];?></td>
          <td><?php echo $row['discount_value']."&nbsp;".$row['discount_type'];?></td>
          <td><img height="21" width="21" alt="" src="<?php echo base_url()."img/".(($row['locked']=='1') ? "ico_active.png" : "ico_delete.png");?>"/></td>
          <td>
            <a href="#"
            onClick="load_coupons_edit_form('<?php echo $row['id'];?>');return false;" title="<{admin_coupon_edit}>"><img alt="<{admin_coupon_edit}>" src="<?php echo base_url();?>img/page_edit16.png" width="16" height="16" /></a>&nbsp;
            <a href="#"
            onClick="delete_coupons_group('<?php echo $row['id'];?>');return false;" title="<{admin_btn_delete}>"><img alt="<{admin_btn_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>&nbsp;
          </td>
        </tr>
