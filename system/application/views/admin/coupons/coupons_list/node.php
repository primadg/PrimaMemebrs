
<!-- Coupons list node -->

        <tr id="cid-<?php echo $row['id'];?>" class="<?php echo $tr_class;?>">
          <td><?php echo $row['coupon_code'];?></td>
          <td><?php echo $row['available_use'];?>/<?php echo $row['coupons_used'];?></td>
          <?php
            if( $row['locked'] == '1' )
            {
          ?>
          <td><{admin_coupon_coupons_list_table_disabled_value_yes}></td>
          <?php
            }
            else
            {
          ?>
          <td><{admin_coupon_coupons_list_table_disabled_value_no}></td>
          <?php
            }
          ?>
          <td>
            <a href="#"
            onClick="delete_coupon('<?php echo $row['id'];?>');return false;" title="<{admin_btn_delete}>"><img alt="<{admin_btn_delete}>" src="<?php echo base_url();?>img/ico_delete.png" width="16" height="16" /></a>
          </td>
        </tr>

