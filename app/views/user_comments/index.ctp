<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userComments index js-response-comments js-response">
<?php if (!empty($userComments)):
echo $this->element('paging_counter');
endif;?>
<ol class="list comment-list js-index-user-comment-response" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
    <?php
    if (!empty($userComments)):
    $i = 0;
    foreach ($userComments as $userComment):
    	$class = null;
    	if ($i++ % 2 == 0) {
    		$class = 'altrow';
    	}
    ?>
<li class="list-row comment clearfix <?php echo $class; ?>" id="comment-<?php echo $userComment['UserComment']['id']; ?>" >
      	<div class="grid_2 omega alpha">
       	 <?php
			echo $this->Html->getUserAvatar($userComment['User'], 'micro_medium_thumb');
			//echo $this->Html->link($this->Html->showImage('UserAvatar',$userComment['User']['UserAvatar'], array('dimension' => 'micro_medium_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($userComment['User']['username'], false)), 'title' => $this->Html->cText($userComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $userComment['User']['username']), array('escape' => false));
		?>
       </div>
       	<div class="grid_14 omega alpha">
       	<div class="clearfix">
          <h3 class="grid_left">
             <?php echo $this->Html->link('#', '#comment-'.$userComment['UserComment']['id'], array('class' => 'js-scrollto')); ?>
               <span class="author"><?php echo $this->Html->link($userComment['User']['username'], array('controller' => 'users', 'action' => 'view', $userComment['User']['username']), array('title' => $userComment['User']['username'], 'escape' => false)); ?></span>
               <?php echo __l('said');?>
           </h3>
           <p class="meta clearfix grid_right posted-date">
            <span class="publish"><?php echo __l('Posted'); ?></span>
            <span class="date">
                <?php echo $this->Html->cDateTimeHighlight($userComment['UserComment']['created']); ?>
            </span>
            </p>
        </div>
 	     <?php echo $this->Html->cText($this->Html->truncate($userComment['UserComment']['comment']));?>
          <?php if ($userComment['User']['id'] == $this->Auth->user('id')) { ?>
            <div class="actions">
      			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'user_comments', 'action' => 'delete', $userComment['UserComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
    		</div>
		<?php } ?>
        </div>
     </li>

<?php
    endforeach;
else:
?>
	<li>
        <p class="notice"> <?php echo sprintf('There are no comments for %s. Be the first to submit a comment.',$username); ?> </p>
	</li>
<?php
endif;
?>
</ol>
<div class='js-pagination'>
<?php
if (!empty($userComments)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>
