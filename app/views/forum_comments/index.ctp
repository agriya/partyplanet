<?php /* SVN: $Id: index.ctp 735 2009-07-21 16:01:02Z siva_063at09 $ */ ?>
<div class="forumComments index js-response-comments js-response">
    <h3><?php echo __l('Forum Discussion');?></h3>
    <?php echo $this->element('paging_counter');?>
    <ol class="list comment-list js-index-forum-comment-response" start="<?php echo $this->Paginator->counter(array(
        'format' => '%start%'
    ));?>">
    <?php
    if (!empty($forumComments)): 
        $i = 0;
        foreach ($forumComments as $forumComment):
        	$class = null;
        	if ($i++ % 2 == 0) :
        		$class = 'altrow';
            endif;
            ?>
        	<li class="list-row clearfix <?php echo $class;?> comment" id="comment-<?php echo $forumComment['ForumComment']['id']?>">
                	<div class="grid_2 omega alpha">
                    <?php
							echo $this->Html->getUserAvatar($forumComment['User'], 'micro_medium_thumb');
							//echo $this->Html->link($this->Html->showImage('UserAvatar', $forumComment['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($forumComment['User']['username'], false)), 'title' => $this->Html->cText($forumComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $forumComment['User']['username']), array('escape' => false));?>
                    </div>
                    <div class="grid_14 omega alpha">
                    <div class="clearfix">
                        <h3 class="grid_left">
                        <?php echo $this->Html->link('#', '#comment-' . $forumComment['ForumComment']['id'], array('class' => 'js-scrollto'));?>
        				<?php	echo $this->Html->cText($forumComment['User']['username']); ?>
                        	<?php if(!empty($forumComment['User']['UserProfile']['Country']['name'])): ?>

    							<?php
    										echo $this->Html->image('flags/'.strtolower($forumComment['User']['UserProfile']['Country']['country_code']).'.gif', array('alt'=> sprintf(__l('[Image: %s]'),$this->Html->cText($forumComment['User']['UserProfile']['Country']['name'])), 'title' => $forumComment['User']['UserProfile']['Country']['name']));
    										echo $this->Html->cText($forumComment['User']['UserProfile']['Country']['name']);
    						?>
       					<?php endif;?>
                        </h3>
                         <p class="meta posted-date grid_right">
        			           <?php echo sprintf(__l('Posted %s'), $this->Html->cDate($forumComment['ForumComment']['created'])); ?>
                		</p>
        		</div>
        		<div class="js-desc-to-trucate {len:'90'}"><?php echo $this->Html->cText($forumComment['ForumComment']['comment']);?></div>
             <?php if ($forumComment['Forum']['user_id'] == $this->Auth->user('id')) : ?>
                    <div class="actions">
                        <?php echo $this->Html->link(__l('Delete'), array('controller' => 'forum_comments', 'action' => 'delete', $forumComment['ForumComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                    </div>
                <?php endif; ?>
                </div>
        	</li>
            <?php
        endforeach;
    else:
        ?>
    	<li>
    		<p class="notice"><?php echo __l('No comments available');?></p>
    	</li>
        <?php
    endif;
    ?>
    </ol>
    
    <div class='js-pagination'>
        <?php
        if (!empty($forumComments)) :
            echo $this->element('paging_links');
        endif;
        ?>
    </div>
</div>