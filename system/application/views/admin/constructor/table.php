<?php if(isset($pagers)){?><div class="page"><?php  echo $pagers['pager'][0]; ?></div><?php }?>
<table 
id="<?php echo isset($id) ? $id : "";?>" 
class="<?php echo isset($classes) ? $classes : "tab";?>" 
align="<?php echo isset($align) ? $align : "center";?>" 
width="<?php echo isset($width) ? $width : "95%";?>"
<?php echo isset($attributes) ? $attributes : "";?> >
          <?php  if(isset($columns) && is_array($columns)){ 
		  $columns_count=isset($columns_count) ? $columns_count : "";
		  ?>
		  <tr class="<?php echo isset($header_classes) ? $header_classes : "glav_big";?>">
            <?php  foreach($columns as $id=>$column){
			if(is_array($column)){
			?>
			<td 
			class="<?php echo isset($column['classes']) ? $column['classes'] : "";?>" 
			width="<?php echo isset($column['width']) ? $column['width'] : "";?>" 
			style="<?php echo isset($column['style']) ? $column['style'] : "";?>"
			<?php echo isset($column['attributes']) ? $column['attributes'] : "";?> >
			<?php  if(isset($column['sortable']) && $column['sortable']){?>
			<a href="#" id="<?php echo $id?>">
			<?php  }else{?>
			<span id="<?php echo $id?>">
			<?php  } ?>
			<?php echo isset($column['text']) ? $column['text'] : "";?>
			<?php  if(isset($column['sortable']) && $column['sortable']){?>
			</a>
			<?php  }else{?>
			</span>
			<?php  } ?>
			</td>
            <?php  }else{ ?>
			<?php echo preg_match('/^<td[\s\S]+<\/td>$/',$column) ? $column : "<td>".$column."</td>"?>
			<?php  } 
			} ?>
          </tr>
			<?php  }else{?>
			<?php echo isset($columns) ? $columns : "";?>
			<?php  } ?>
			
        <?php 
        if(isset($rows)&&is_array($rows)&&count($rows))
        {
            $flag=true;
            foreach($rows as $value)
            {				
                
				$cells=$value;
				$row_classes=$flag?"light":"dark";$flag=!$flag;
				if(isset($value['cells']))
				{
					$cells=$value['cells'];
					$row_classes=isset($value['classes']) ? $value['classes'] : $row_classes;
				}
				
				?>
				<tr class="<?php echo $row_classes?>">
                <?php  foreach($cells as $id=>$cell){ ?>
				<td><?php echo $cell?></td>
				<?php  } ?>
				
				</tr>                
                <?php                
            }
        }
        else
        {
            ?>
            <tr class="dark">
            <td colspan="<?php echo $columns_count?>"><{admin_table_empty}></td>                                
            </tr>
            <?php 
        }
        ?>
        </table>
        <br />
        <div class="add">
        <input type="button" class="button_super_big" value="<{admin_member_group_btn_add}>" onClick="fieldLangsEdit(15,'');"/>
        </div>
        <?php if(isset($pagers)){?><div class="page"><?php  echo $pagers['pager'][1]; ?></div><?php }?>
        <br/>
