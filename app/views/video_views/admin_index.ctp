<?php /* SVN: $Id: admin_index.ctp 1300 2009-07-25 13:46:58Z boopathi_026ac09 $ */ ?>
<div class="photoViews index js-response">
     <?php echo $this->element('paging_counter');?>
    <?php echo $this->Form->create('VideoView' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
   <div class="overflow-block">
	<table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Video'), 'Video.title');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed by'), 'User.username');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed on'), 'created');?></div></th>
        </tr>
        <?php
        if (!empty($videoViews)):
            $i = 0;
            foreach ($videoViews as $videoView):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
				<td class="select"><?php echo $this->Form->input('VideoView.'.$videoView['VideoView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$videoView['VideoView']['id'], 'label' => false, 'class' => 'js-checkbox-active js-checkbox-list')); ?></td>
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
                                 <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoView['VideoView']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        					</ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
                  </div>
                 </td>
                    <td class="dl">
                    <?php
                      $videoView['Video']['Thumbnail']['id'] = (!empty($videoView['Video']['default_thumbnail_id'])) ? $videoView['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $videoView['Video']['Thumbnail']['id'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($videoView['Video']['title'], false)), 'title' => $this->Html->cText($videoView['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $videoView['Video']['slug'], 'admin' => false), array('escape' => false));
						?>
                    <?php echo $this->Html->link($this->Html->cText($videoView['Video']['title']), array('controller'=> 'videos', 'action'=>'view','action'=>'view', $videoView['Video']['slug'], 'admin' => false), array('escape' => false));?>
                    </td>
                    <td class="dl"><?php echo !empty($videoView['User']['username']) ? $this->Html->link($this->Html->cText($videoView['User']['username']), array('controller'=> 'users', 'action'=>'view', $videoView['User']['username'], 'admin' => false), array('escape' => false)) : __l('Guest');?></td>
                    <td class="dc">
						<?php if(!empty($videoView['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($videoView['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $videoView['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$videoView['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($videoView['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($videoView['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $videoView['Ip']['Country']['name']; ?>">
									<?php echo $videoView['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($videoView['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $videoView['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
					</td>
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($videoView['VideoView']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="6"><p class="notice"><?php echo __l('No Video Views available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
	</div>
    <?php
    if (!empty($videoViews)) :
       ?>
       <div class="clearfix admin-select-bot">
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
                <?php echo $this->element('paging_links'); ?>
            </div>
        </div>
        <div class="hide">
              <?php echo $this->Form->submit('Submit');  ?>
        </div>
     
        <?php
    endif;
    echo $this->Form->end();
    ?>

</div>