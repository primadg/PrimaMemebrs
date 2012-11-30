<?php
$odd=1;fb($products);
    if(isset($products) && @count($products))
        foreach($products as $product)
        {                             
            ?>
            
            
              <tr class="<?php echo (   $odd++%2    ?   "light"   :   "dark")?>">
                <td><?php echo (int)$product['id']?></td>
                <td class="left" <?php echo ( (($product['dir_id']>0 and $product['product_type']==1) or ($product['host_plan_id']>0 and $product['product_type']==2)) ? '' : 'style="color:#F00;"' )?>>
                    
                    <?php echo output(word_wrap($product['name'],30,2))?> 
                    
                </td>
                <td><?php echo output($product['users_in'])?></td>
                <td class="left">                    
                    <?php echo output(word_wrap($product['group_name'],20,2))?>   
                 </td>
                <td>
                    <?php echo (   $product['subscr_type']  == 1   ?   "<{product_pay_type_one_time}>"   :   "<{product_pay_type_reccuring}>"   )?>
                </td>
                <td width="100">
                <?php   if ( $product['locked'] <> 1 and $product['closed'] <> 1 and in_array($product['product_type'], array(PRODUCT_PROTECT,PRODUCT_HOSTED)))
					{
					?>
					<?php  if ($product['special_offers'] == 1) 
						{ 
						?>
                        <a style="cursor:pointer;" id="product_special_offers<?php echo (int)$product['id']?>" onclick="special_offers_product(<?php echo $product['id']?>); return false;" title="<{product_list_edit_special}>">
                    		<img src="<?php echo base_url()?>img/favorite.png" alt="<{product_list_edit_special}>"   width="16" height="16"/>
                		</a>
                		<?php  } else { ?>
                		<a style="cursor:pointer;" id="product_special_offers<?php echo (int)$product['id']?>" onclick="special_offers_product(<?php echo $product['id']?>); return false;" title="<{product_list_edit_not_special}>">
                    		<img src="<?php echo base_url()?>img/favorite_off.png" alt="<{product_list_edit_not_special}>"   width="16" height="16"/>
                		</a>                
                		<?php  	} 
					} 
					else 
					{ ?>
                    <?php  if ($product['special_offers'] == 1) 
						{ 
						?>
                    		<img src="<?php echo base_url()?>img/favorite.png" alt="<{product_list_edit_special}>"   width="16" height="16"/>
                        <?php  
						} 
						else 
						{ ?>
                        	<img src="<?php echo base_url()?>img/favorite_off.png" alt="<{product_list_edit_not_special}>"   width="16" height="16"/>
                     <?php  } ?>
                <?php  	} ?>
                 <a style="text-decoration: none;" class="link_buttons" onClick="click_edit(<?php echo (int)$product['id']?>); return false;" href="edit_products.php" title="<{product_list_edit_settings_button_alt}> '<?php echo output(word_wrap($product['name'],30,2))?>'"><img alt="<{product_list_edit_button_alt}> '<?php echo output(word_wrap($product['name'],30,2))?>'" src="<?php echo base_url()?>img/page_edit16.png" width="16" height="16" />&nbsp;</a>
                 <?php if(Functionality_enabled('admin_multi_language')===true){?>
                 <a style="text-decoration: none;" class="link_buttons" onClick="fieldLangsEdit(4,<?php echo (int)$product['id']?>); return false;" href="#" title="<{product_list_edit_lang_button_alt}>"><img alt="<{product_list_edit_lang_button_alt}>" src="<?php echo base_url()?>img/ico_lang.png" width="16" height="16" />&nbsp;</a>
                 <?php }?>
                 <a style="text-decoration: none;" class="link_buttons" onClick="click_delete_product(<?php echo (int)$product['id']?>); return false;" href="#" title="<{product_list_delete_button_alt}>"><img alt="<{product_list_delete_button_alt}>" src="<?php echo base_url()?>img/ico_delete.png" width="16" height="16" />&nbsp;</a>
                </td>
                <td>             

                
                    <?php 
                        if($product['locked'])
                        {
                            ?>
                                <a style="text-decoration: none;" id="product_locked<?php echo (int)$product['id']?>" href="#" onClick="block_product(<?php echo (int)$product['id']?>); return false;" title="<{product_list_unlock_button_alt}>">
                                    <img alt="<{product_list_unlock_button_alt}>" src="<?php echo base_url()?>img/ico_locked.png" width="16" height="16" />
                                </a>
                            <?php 
                        }
                        else
                        {
                            ?>
                                <a style="text-decoration: none;" id="product_locked<?php echo (int)$product['id']?>" href="#" onClick="block_product(<?php echo (int)$product['id']?>); return false;" title="<{product_list_lock_button_alt}>">
                                    <img alt="<{product_list_lock_button_alt}>" src="<?php echo base_url()?>img/ico_unlocked.png" width="16" height="16" />
                                </a>
                            <?php 
                        }
                    ?>                                                   
                </td>
              </tr>                                                            
              
              
            <?php 
        }
    else
    {
        ?>                           
            
        <tr class="dark">
            <td colspan="7"><{admin_table_empty}></td>                                
        </tr>
        
        <?php 
    }
?>
