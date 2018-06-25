<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="questions index js-response">
     <div class="index_inner">
    <?php echo $this->Form->create('Photo' , array('class' => 'normal','action' => 'move_to')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
	<div class="clearfix">
        <div class="grid_right">
            <?php if (count($photos) < 8) { ?>
            <div class="clearfix">
                <?php echo $this->Html->link(__l('Add'), array('controller' => 'photos', 'action' => 'admin_random'), array('class' => 'add','title' => __l('Add'))); ?>
            </div>
        	<?php } ?>
        </div>
        <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
    </div>
	<table class="list">
        <tr>
            <th><?php echo __l('Actions'); ?></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Created'), 'Photo.created'); ?></div></th>
            <th><?php echo __l('Photo'); ?></th>
            <th><?php echo __l('Title'); ?></th>            
        </tr>
        <?php
           if (!empty($photos)):
            $i = 0;
            foreach ($photos as $photo):
                $class = null;
                if ($i++ % 2 == 0):
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="actions">
            <div class="action-block">
                <span class="action-information-block">
                    <span class="action-left-block">&nbsp;&nbsp;</span>
                        <span class="action-center-block">
                            <span class="action-info">
                                <?php echo __l('Action');?>
                             </span>
                        </span>
                    </span>
                    <div class="action-inner-block">
                    <div class="action-inner-left-block">
                        <ul class="action-link clearfix">
                           <li> <?php echo $this->Html->link(__l('Edit'), array('controller' => 'photos', 'action'=>'edit', $photo['Photo']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('controller' => 'photos','action'=>'delete', $photo['Photo']['id'],'type'=>'random'), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					   </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
                    
                        
                    </td>
                    <td><?php echo $this->Html->cDateTimeHighlight($photo['Photo']['created']);?></td>
                    <td>
                    <?php
						echo $this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false)));
					?>
					</td>
                    <td>
                        <span><?php echo $this->Html->cText($photo['Photo']['title']);?></span>
				    </td>
                </tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
            <td colspan="12"><p class="notice"><?php echo __l('No photos available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($photos)):
        ?>
         <div class="clearfix select-block-bot">
            <div class="js-pagination grid_right">
                <?php echo $this->element('paging_links'); ?>
            </div>
        </div>
        <div class="hide">
            <div class="submit-block clearfix">
                <?php echo $this->Form->submit('Submit');  ?>
            </div>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>
</div>