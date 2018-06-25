<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="videoFavorites index js-response">
    <div class="clearfix">
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="grid_left">
        <?php if (!(isset($this->request->params['isAjax']) && $this->request->params['isAjax'] == 1)): ?>
        	<?php echo $this->Form->create('VideoFavorite' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
       		<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
           	<?php echo $this->Form->end(); ?>
        <?php endif; ?>
    </div>
    </div>
    <?php echo $this->Form->create('VideoFavorite' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
  <div class="overflow-block">
    <table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Video'), 'Video.title');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Added on'), 'created');?></div></th>
        </tr>
        <?php
        if (!empty($videoFavorites)):
        $i = 0;
        foreach ($videoFavorites as $videoFavorite):
        	$class = null;
        	if ($i++ % 2 == 0) :
        		$class = ' class="altrow"';
            endif;
        ?>
        	<tr<?php echo $class;?>>
                <td class="select"><?php echo $this->Form->input('VideoFavorite.'.$videoFavorite['VideoFavorite']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$videoFavorite['VideoFavorite']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoFavorite['VideoFavorite']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        					 </ul>
        					</div>
        					<div class="action-bottom-block"></div>
        				  </div>
                          </div>
                  
                </td>
                <td class="dl">
                   <?php $videoFavorite['Video']['Thumbnail']['id'] = (!empty($videoFavorite['Video']['default_thumbnail_id'])) ? $videoFavorite['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $videoFavorite['Video']['Thumbnail']['id'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($videoFavorite['Video']['title'], false)), 'title' => $this->Html->cText($videoFavorite['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $videoFavorite['Video']['slug'], 'admin' => false), array('escape' => false));
						?>
                    <?php echo $this->Html->link($this->Html->cText($videoFavorite['Video']['title']), array('controller'=> 'videos', 'action'=>'view', $videoFavorite['Video']['slug'], 'admin' => false), array('escape' => false));?>
                </td>
                <td class="dl">
                    <?php echo $this->Html->link($this->Html->cText($videoFavorite['User']['username']), array('controller'=> 'users', 'action'=>'view', $videoFavorite['User']['username'], 'admin' => false), array('escape' => false));?>
                </td>
                <td class="dc">
					<?php if(!empty($videoFavorite['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($videoFavorite['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $videoFavorite['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$videoFavorite['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($videoFavorite['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($videoFavorite['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $videoFavorite['Ip']['Country']['name']; ?>">
									<?php echo $videoFavorite['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($videoFavorite['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $videoFavorite['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
				</td>
        		<td class="dc"><?php echo $this->Html->cDateTimeHighlight($videoFavorite['VideoFavorite']['created']);?></td>
        	</tr>
        <?php
            endforeach;
        else:
        ?>
        	<tr>
        		<td colspan="6"><p class="notice"><?php echo __l('No Video Favorites available');?></p></td>
        	</tr>
        <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($videoFavorites)) :
        ?>
        <div class="admin-select-bot clearfix">
        <div class="admin-select-block">
            <div class="grid_left">
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
            </div>
            <div class="admin-checkbox-button grid_left">
                <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
            <div class="hide">
                    <?php echo $this->Form->submit('Submit');  ?>
              </div>
        </div>
        <div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
        </div>
        <?php
    endif; ?>
        <?php echo $this->Form->end(); ?>

</div>