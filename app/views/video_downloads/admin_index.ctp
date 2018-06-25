<?php /* SVN: $Id: admin_index.ctp 1233 2011-04-26 10:25:01Z boopathi_026ac09 $ */ ?>
<div class="videoDownloads index js-response">
        <div class="clearfix">
            <div class="grid_left">
                 <?php echo $this->element('paging_counter');?>
            </div>
            <?php if(empty($this->request->params['named']['video'])):?>
            <div class="grid_left">
        	   <?php echo $this->Form->create('VideoDownload' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
       			<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
       		    <?php echo $this->Form->submit(__l('Search'));?>
       	       <?php echo $this->Form->end(); ?>
        	</div>
        	<?php endif;?>
    	</div>
    <?php echo $this->Form->create('VideoDownload' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
  
    <table class="list">
        <tr>
            <th><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Video'), 'Video.title');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Downloaded by'), 'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Downloaded on'), 'created');?></div></th>
        </tr>
        <?php
        if (!empty($videoDownloads)):
            $i = 0;
            foreach ($videoDownloads as $videoDownload):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td><?php echo $this->Form->input('VideoDownload.'.$videoDownload['VideoDownload']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$videoDownload['VideoDownload']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
                    <td class="actions"><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoDownload['VideoDownload']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
                    <td>
                        <?php
							$videoDownload['Thumbnail']['id'] = (!empty($videoDownload['Video']['default_thumbnail_id'])) ? $videoDownload['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $videoDownload['Thumbnail'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($videoDownload['Video']['title'], false)), 'title' => $this->Html->cText($videoDownload['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $videoDownload['Video']['slug'], 'admin' => false), array('escape' => false));
						?>
                        <?php echo $this->Html->link($this->Html->cText($videoDownload['Video']['title']), array('controller'=> 'videos', 'action'=>'view', $videoDownload['Video']['slug'], 'admin' => false), array('escape' => false));?>
                    </td>
                    <td>
                        <?php echo !empty($videoDownload['User']['username']) ? $this->Html->link($this->Html->cText($videoDownload['User']['username']), array('controller'=> 'users', 'action'=>'view', $videoDownload['User']['username'], 'admin' => false), array('escape' => false)) : __l('Guest');?>
                    </td>
                    <td><?php if(!empty($videoDownload['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($videoDownload['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $videoDownload['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$videoDownload['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($videoDownload['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($videoDownload['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $videoDownload['Ip']['Country']['name']; ?>">
									<?php echo $videoDownload['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($videoDownload['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $videoDownload['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?></td>
                    <td><?php echo $this->Html->cDateTimeHighlight($videoDownload['VideoDownload']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="6"><p class="notice"><?php echo __l('No Video Downloads available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($videoDownloads)) :
       ?>
        <div class="clearfix">
            <div class="admin-select-block grid_left">
                <div>
                    <?php echo __l('Select:'); ?>
                    <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                    <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                </div>
                <div class="admin-checkbox-button">
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