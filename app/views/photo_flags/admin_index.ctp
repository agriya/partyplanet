<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="photoFlags index js-response">
    <?php if (!(isset($this->request->params['isAjax']) && $this->request->params['isAjax'] == 1)): ?>
    <div class="clearfix">
      <div class="grid_left">
         <?php echo $this->element('paging_counter');?>
      </div>
        <div class="grid_left">
            <?php echo $this->Form->create('PhotoFlag' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
            <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
            <?php echo $this->Form->submit(__l('Search'));?>
            <?php echo $this->Form->end(); ?>
      </div>
  </div>
   <?php endif; ?>
   <?php echo $this->Form->create('PhotoFlag' , array('class' => 'normal','action' => 'update')); ?>
   <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <div class="overflow-block">
	<table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'),'User.username');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Photo'), 'Photo.title');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Flag Category'), 'PhotoFlagCategory.name');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('message');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Posted on'), 'created');?></div></th>
        </tr>
        <?php
        if (!empty($photoFlags)):
            $i = 0;
            foreach ($photoFlags as $photoFlag):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="select"><?php echo $this->Form->input('PhotoFlag.'.$photoFlag['PhotoFlag']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$photoFlag['PhotoFlag']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photoFlag['PhotoFlag']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
       					 </ul>
        					</div>
        					<div class="action-bottom-block"></div>
                     </div>
                     </div>
                    </td>
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->cText($photoFlag['User']['username']), array('controller'=> 'users', 'action'=>'view', $photoFlag['User']['username'], 'admin' => false), array('escape' => false));?>
                    </td>
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->showImage('Photo', $photoFlag['Photo']['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photoFlag['Photo']['title'], false)), 'title' => $this->Html->cText($photoFlag['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'view', $photoFlag['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                        <?php echo $this->Html->link($this->Html->cText($photoFlag['Photo']['title']), array('controller'=> 'photos', 'action'=>'view', $photoFlag['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                    </td>
                    <td class="dl"><?php echo $this->Html->cText($photoFlag['PhotoFlagCategory']['name']);?></td>
                    <td class="dl"><?php echo $this->Html->Truncate($photoFlag['PhotoFlag']['message']);?></td>
                    <td class="dc">
						<?php if(!empty($photoFlag['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($photoFlag['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $photoFlag['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$photoFlag['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($photoFlag['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($photoFlag['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $photoFlag['Ip']['Country']['name']; ?>">
									<?php echo $photoFlag['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($photoFlag['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $photoFlag['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
					</td>
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($photoFlag['PhotoFlag']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="9"><p class="notice"><?php echo __l('No Photo Flags available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($photoFlags)) :
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
              <?php echo $this->Form->submit('Submit');  ?>
         </div>

        <?php
    endif;
    echo $this->Form->end();
    ?>

  </div>

