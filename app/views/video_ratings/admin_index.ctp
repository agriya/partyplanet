<?php /* SVN: $Id: admin_index.ctp 1300 2009-07-25 13:46:58Z boopathi_026ac09 $ */ ?>
<div class="photoRatings index js-response">
    <?php echo $this->Form->create('VideoRating' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
     <?php echo $this->element('paging_counter');?>
    <table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Video'), 'Video.title');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort('rate');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Rated on'), 'created');?></div></th>
        </tr>
        <?php
        if (!empty($videoRatings)):
            $i = 0;
            foreach ($videoRatings as $videoRating):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="select"><?php echo $this->Form->input('VideoRating.'.$videoRating['VideoRating']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$videoRating['VideoRating']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                     <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoRating['VideoRating']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
            					 </ul>
            					</div>
            					<div class="action-bottom-block"></div>
            				  </div>
                         </div>
                    </td>
                    <td>
	           <?php
                 $videoRating['Video']['Thumbnail']['id'] = (!empty($videoRating['Video']['default_thumbnail_id'])) ? $videoRating['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $videoRating['Video']['Thumbnail']['id'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($videoRating['Video']['title'], false)), 'title' => $this->Html->cText($videoRating['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $videoRating['Video']['slug'], 'admin' => false), array('escape' => false));
						?>
                        <?php echo $this->Html->link($this->Html->cText($videoRating['Video']['title']), array('controller'=> 'videos', 'action' => 'v', 'slug' => $videoRating['Video']['slug'], 'view_type' => ConstViewType::NormalView, 'admin' => false), array('escape' => false));?>
                    </td>
                    <td><?php echo $this->Html->link($this->Html->cText($videoRating['User']['username']), array('controller'=> 'users', 'action'=>'view', $videoRating['User']['username'], 'admin' => false), array('escape' => false));?></td>
                    <td><?php echo $this->Html->cInt($videoRating['VideoRating']['rate']);?></td>
                    <td>
						<?php if(!empty($videoRating['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($videoRating['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $videoRating['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$videoRating['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($videoRating['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($videoRating['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $videoRating['Ip']['Country']['name']; ?>">
									<?php echo $videoRating['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($videoRating['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $videoRating['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
					</td>
                    <td><?php echo $this->Html->cDateTimeHighlight($videoRating['VideoRating']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="7"><p class="notice"><?php echo __l('No Video Ratings available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($videoRatings)) :
        ?>
        <div class="clearfix">
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