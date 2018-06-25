<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="photoFavorites index js-response">
    <div class="clearfix">
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="grid_left">
        <?php if (!(isset($this->request->params['isAjax']) && $this->request->params['isAjax'] == 1)): ?>
        	<?php echo $this->Form->create('PhotoFavorite' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
       		<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
           	<?php echo $this->Form->end(); ?>
        <?php endif; ?>
    </div>
    </div>
    <?php echo $this->Form->create('PhotoFavorite' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
  <div class="overflow-block">
    <table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Photo'), 'Photo.title');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Added on'), 'created');?></div></th>
        </tr>
        <?php
        if (!empty($photoFavorites)):
        $i = 0;
        foreach ($photoFavorites as $photoFavorite):
        	$class = null;
        	if ($i++ % 2 == 0) :
        		$class = ' class="altrow"';
            endif;
        ?>
        	<tr<?php echo $class;?>>
                <td class="select"><?php echo $this->Form->input('PhotoFavorite.'.$photoFavorite['PhotoFavorite']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$photoFavorite['PhotoFavorite']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photoFavorite['PhotoFavorite']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        					 </ul>
        					</div>
        					<div class="action-bottom-block"></div>
        				  </div>
                          </div>
                  
                </td>
                <td class="dl">
                    <?php echo $this->Html->link($this->Html->showImage('Photo', $photoFavorite['Photo']['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photoFavorite['Photo']['title'], false)), 'title' => $this->Html->cText($photoFavorite['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'view', $photoFavorite['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                    <?php echo $this->Html->link($this->Html->cText($photoFavorite['Photo']['title']), array('controller'=> 'photos', 'action'=>'view', $photoFavorite['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                </td>
                <td class="dl">
                    <?php echo $this->Html->link($this->Html->cText($photoFavorite['User']['username']), array('controller'=> 'users', 'action'=>'view', $photoFavorite['User']['username'], 'admin' => false), array('escape' => false));?>
                </td>
                <td class="dc">
					<?php if(!empty($photoFavorite['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($photoFavorite['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $photoFavorite['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$photoFavorite['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($photoFavorite['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($photoFavorite['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $photoFavorite['Ip']['Country']['name']; ?>">
									<?php echo $photoFavorite['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($photoFavorite['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $photoFavorite['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
				</td>
        		<td class="dc"><?php echo $this->Html->cDateTimeHighlight($photoFavorite['PhotoFavorite']['created']);?></td>
        	</tr>
        <?php
            endforeach;
        else:
        ?>
        	<tr>
        		<td colspan="6"><p class="notice"><?php echo __l('No Photo Favorites available');?></p></td>
        	</tr>
        <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($photoFavorites)) :
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