<?php /* SVN: $Id: index.ctp 735 2009-07-21 16:01:02Z siva_063at09 $ */ ?>
<div class="photoComments index">
    <h3><?php echo __l('Comments');?></h3>
    <?php echo $this->element('paging_counter');?>
    <ol class="list clearfix comments comment-list js-index-photo-comment-response" start="<?php echo $this->Paginator->counter(array(
        'format' => '%start%'
    ));?>">
    <?php
       if (!empty($photoComments)):
        $i = 0;
        foreach ($photoComments as $photoComment):
        	$class = null;
        	if ($i++ % 2 == 0) :
        		$class = 'altrow';
            endif;
            ?>
        	<li class="list-row clearfix <?php echo $class;?> comment clearfix" id="comment-<?php echo $photoComment['PhotoComment']['id']?>">
        		<div class="grid_2 omega alpha">
					<?php 
						echo $this->Html->getUserAvatar($photoComment['User'], 'micro_medium_thumb');?>
				</div>
        		<div class="grid_14 omega alpha">
                <div class="clearfix">
                    <h3 class="grid_left">
        			<?php echo $this->Html->link('#', '#comment-' . $photoComment['PhotoComment']['id'], array('class' => 'js-scrollto'));?>
        		     <?php echo $this->Html->link($this->Html->cText($photoComment['User']['username']), array('controller' => 'users', 'action' => 'view', $photoComment['User']['username']), array('title' => $photoComment['User']['username'], 'escape' => false));?>
                      <?php echo __l('said');?>
                    </h3>
				     <p class="meta posted-date grid_right"><?php echo sprintf(__l('posted %s'), $this->Html->cDateTimeHighlight($photoComment['PhotoComment']['created'])); ?></p>
                    </div>
              		<div><?php echo $this->Html->cText($photoComment['PhotoComment']['comment']);?></div>
            		<?php if ($photoComment['User']['id'] == $this->Auth->user('id')) : ?>
					       <?php  echo $this->Html->link(__l('Delete'), array('controller' => 'photo_comments', 'action' => 'delete', $photoComment['PhotoComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                	<?php endif; ?>
        		
        		</div>
        	</li>
            <?php
        endforeach;
    else:
        ?>
    	<li class="notice-block">
    		<p class="notice"><?php echo __l('No comments available');?></p>
    	</li>
        <?php
    endif;
    ?>
    </ol>
    <div class="js-pagination">
        <?php
        if (!empty($photoComments)) :
            echo $this->element('paging_links');
        endif;
        ?>
    </div>
</div>