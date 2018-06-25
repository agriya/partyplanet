<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="guestListUsers index">
    <h2><?php echo __l('Guest List');?></h2>
    <?php
        $no_rec = 0;
        if (!empty($guest)): 
            foreach ($guest as $revp_res =>$guestListUsers):
                if(!empty($guestListUsers)):?>
					<?php if($guestList['Event']['ticket_fee'] == 0){?>
                    <h3><?php echo __l('Attending');?>: <?php echo ucfirst($revp_res);?></h3><?php } ?>
                    <table class="list" >
                        <tr>
                            <th><?php echo __l('Name');?></th>
                            <th><?php echo __l('Attendies');?></th>
                        </tr>
                        <?php
                         $i = 0;
                         foreach ($guestListUsers as $guestListUser):
                        	$class = null;
                            	if ($i++ % 2 == 0) {
                            		$class = ' class="altrow"';
                            	}  ?>
                        	<tr<?php echo $class;?>>
                        		<td><?php echo $this->Html->link($this->Html->cText($guestListUser['User']['username']), array('controller'=> 'users', 'action' => 'view', $guestListUser['User']['username']), array('escape' => false));?></td>
                           		<td><?php echo $this->Html->cInt($guestListUser['GuestListUser']['in_party_count']);?></td>
                        	</tr>
                        <?php endforeach;  ?>
                    </table>
                    <?php
                 else:
                  $no_rec++;
                endif;
             endforeach;
        else:?>
             <p class="notice"><?php echo __l('No Guest List available');?></p>
            <?php
        endif;
        if($no_rec==3){?>
                     <p class="notice"><?php echo __l('No Guest List available');?></p>
        
        <?php }
        ?>
        
</div>