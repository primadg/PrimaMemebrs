
<!-- Coupons Statistic --> 

          <tr class="<?php echo $tr_class;?>">
            <td><?php echo $row['coupon_code'];?>/<a href="#"
            onClick="show_coupons('<?php echo $row['group_id'];?>',{back: 'coupons_statistic'});return false;"><?php echo $row['group_id'];?></a></td>
            <td><?php echo isset($row['cdate']) ? nsdate($row['cdate'],false) :"";?></td>
            <td><a href="#"
            onClick="load_member_info('<?php echo $row['user_id'];?>','coupons_statistic');return false;"><?php echo $row['user_name'];?></a></td>
            <td><?php echo $row['product_name'];?></td>
            <td><?php echo isset($row['start_time']) ? nsdate($row['start_time'],false) :"";?> - <?php echo (isset($row['end_time']) && nsdate($row['end_time'],false)!='') ? nsdate($row['end_time'],false) :"<{admin_coupon_statistic_table_undefined_end_date}>";?></td>
            <td>
            <?php 
                if( intval($row['discount_percent']) > 0 )
                {
                    echo $row['discount_percent'];
                    echo " <{admin_coupon_create_coupons_field_discount_type_percent}>";
                }
                else if( intval($row['discount_value']) > 0 )
                {
                    echo $row['discount_value'];
                  if(config_get('system','config','currency_code')!=false)
                    {echo config_get('system','config','currency_code');}
                  else 
                    {echo "<{admin_coupon_create_coupons_field_discount_type_value}>";}
                }
            ?></td>
            <td><?php echo $row['summ']; ?></td>
            <td><img height="21" width="21" alt="" src="<?php echo base_url()."img/".(($row['completed']=='1') ? "ico_active.png" : "ico_delete.png");?>"/></td>
          </tr>
