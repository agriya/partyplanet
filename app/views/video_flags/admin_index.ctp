<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="videoFlags index js-response">
    <div class="clearfix">
         <div class="grid_left">
             <?php echo $this->element('paging_counter');?>
         </div>
    <?php if (!(isset($this->request->params['isAjax']) && $this->request->params['isAjax'] == 1)): ?>
        <div class="form-content-block grid_left">
            <?php echo $this->Form->create('VideoFlag' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
         	<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
            <?php echo $this->Form->end(); ?>
       </div>
   <?php endif; ?>
    </div>
   
   <?php echo $this->Form->create('VideoFlag' , array('class' => 'normal','action' => 'update')); ?>
   <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <div class="overflow-block">
	<table class="list">
        <tr>
            <th><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'),'User.username');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Video'), 'Video.title');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Flag Category'), 'VideoFlagCategory.name');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Message'), 'message');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Posted on'), 'created');?></div></th>
        </tr>
        <?php
          if (!empty($videoFlags)):
            $i = 0;
            foreach ($videoFlags as $videoFlag):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td><?php echo $this->Form->input('VideoFlag.'.$videoFlag['VideoFlag']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$videoFlag['VideoFlag']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoFlag['VideoFlag']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					   </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
                    </td>
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->cText($videoFlag['User']['username']), array('controller'=> 'users', 'action'=>'view', $videoFlag['User']['username'], 'admin' => false), array('escape' => false));?>
                             </td>
                    <td class="dl">
                    <?php
                        $videoFlag['Video']['Thumbnail']['id'] = (!empty($videoFlag['Video']['default_thumbnail_id'])) ? $videoFlag['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $videoFlag['Video']['Thumbnail']['id'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($videoFlag['Video']['title'], false)), 'title' => $this->Html->cText($videoFlag['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $videoFlag['Video']['slug'], 'admin' => false), array('escape' => false));
						?>
                        <?php echo $this->Html->link($this->Html->cText($videoFlag['Video']['title']), array('controller'=> 'videos', 'action'=>'view', $videoFlag['Video']['slug'], 'admin' => false), array('escape' => false));?>
                    </td>
                    <td class="dl"><?php echo $this->Html->Truncate($videoFlag['VideoFlagCategory']['name']);?></td>
                    <td class="dl"><?php echo $this->Html->Truncate($videoFlag['VideoFlag']['message']);?></td>
                    <td class="dc">
						<?php if(!empty($videoFlag['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($videoFlag['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $videoFlag['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$videoFlag['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($videoFlag['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($videoFlag['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $videoFlag['Ip']['Country']['name']; ?>">
									<?php echo $videoFlag['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($videoFlag['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $videoFlag['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
					</td>
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($videoFlag['VideoFlag']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="9"><p class="notice"><?php echo __l('No Video Flags available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($videoFlags)) :
        ?>
        <div class="clearfix select-block-bot">
            <div class="admin-select-block grid_left">
                <div class="grid_left">
                    <?php echo __l('Select:'); ?>
                    <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                    <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                </div>
                <div class="admin-checkbox-button grid_left">
                    <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
                </div>
            </div>
            <div class="js-pagination grid_right">
                <?php  echo $this->element('paging_links'); ?>
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
