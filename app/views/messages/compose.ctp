<ul class="menu-tabs clearfix">
	<li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
	<li class="active"><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
	<li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'contacts', 'action' => 'invite', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
</ul>
 <?php echo $this->element('message_message-left_sidebar', array('cache' => array('config' => 'sec'))); ?>
<div class="messages index">
<h2><?php echo __l('Compose') ?></h2>
<?php // echo $this->element('mail-search'); ?>
<?php echo $this->Form->create('Message', array('action' => 'compose', 'class' => 'compose normal', 'enctype' => 'multipart/form-data')); ?>
<?php
	if (!empty($all_parents)) :?>
	   <div class="form-content-block js-corner round-5">
      <?php	foreach($all_parents as $parent_message) : ?>
            <div class="message-content-block">
                <div class="clearfix">
                   <div class="mail-sender-name grid_left">
                        <div class="view-star grid_left">
                          	<?php echo $this->Html->cText($parent_message['OtherUser']['username']); ?>
                        </div>
                         <span class="sender-name grid_left">
        				   <?php echo __l('to me'); ?>
                        </span>
        			</div>
        		</div>
              <div class="message-information">
                 <?php echo $this->Html->cText($parent_message['MessageContent']['message']); ?>
              </div>
              </div>
            <?php
        endforeach;
        ?>
         </div>
        <?php
    endif;
?>
<div class="compose-block clearfix">
<div class="message-block-right grid_right" >
 <?php echo $this->Form->submit(__l('Send'), array('class' => 'js-without-subject', 'name' => 'data[Message][send]')); ?>
 <?php echo $this->Form->submit(__l('Save'), array('value' => 'draft', 'name' => 'data[Message][save]')); ?>
 <div class="cancel-block">
 <?php echo $this->Html->link(__l('Discard') , array('controller' => 'messages', 'action' => 'inbox') , array('class' => 'js-compose-delete compose-delete','title' => __l('Discard')) , null, false); ?></div>
  <div class="cancel-block">
 <?php echo $this->Html->link(__l('Cancel'), array('controller' => 'messages', 'action' => 'inbox') , array('title' => __l('Cancel'))); ?>
 </div></div>
 </div>
 <div class="compose-box">
	<fieldset>
			<?php
				echo $this->Form->autocomplete('to', array('type' => 'text', 'id' => 'message-to', 'acFieldKey' => 'User.id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '255'));
				echo $this->Form->input('parent_message_id', array('type' => 'hidden'));
				echo $this->Form->input('type', array('type' => 'hidden'));
			?>
			<?php
				echo $this->Form->input('subject', array('id' => 'MessSubject', 'maxlength' => '100', 'label'=>__l('Subject')));
            ?>
            <div class="atachment">
			<?php echo $this->Form->input('Attachment.filename. ', array('type' => 'file', 'label' => '','size' => '33', 'class' => 'multi file attachment browse-field')); ?>
			</div>
			<p class="more-attachment clearfix"><?php echo $this->Html->link(__l('Add more attachment'),array('#'),array('class'=>'js-attachmant add','title' => __l('Add more attachment')));?></p>
			<div class="js-attachment-list">
				<?php 
					if(!empty($parent_message['MessageContent']['Attachment'])) {
				?>
						 <ol class="clearfix attachment-list">
				<?php
						foreach($parent_message['MessageContent']['Attachment'] as $attachment) {
				?>
						<li>
							<div class="js-old-attachmant-div-<?php echo $attachment['id']; ?>">
							<?php 
								echo $attachment['filename'];
								echo $this->Form->input('Attachment.id. ', array('type' => 'hidden','value'=>$attachment['id']));
								echo $this->Html->link(__l('Remove attachment'), array('#'), array('class'=>'delete js-old-attachmant {"id" : "'.$attachment['id'].'"}','title' => __l('Remove attachment')));
							?>
							</div>
						</li>
				<?php
						}
				?>
						</ol>
				<?php
					}
				?>
			</div>

			<?php
			if(!empty($this->request->params['named']['project_id'])):
				echo $this->Form->input('project_id', array('type' => 'hidden','value'=>$this->request->params['named']['project_id'])); 
			endif;
			echo $this->Form->input('message', array('type' => 'textarea', 'label' => '')); 
			 echo $this->Form->input('message_content_id', array('type' => 'hidden')); ?>
	</fieldset>
</div>
<div class="compose-block clearfix">
<div class="message-block-right grid_right">
	<?php echo $this->Form->submit(__l('Send'), array('class' => 'js-without-subject')); ?>
	<?php echo $this->Form->submit(__l('Save'), array('value' => 'draft', 'name' => 'data[Message][save]')); ?>
	<div class="cancel-block"><?php echo $this->Html->link(__l('Discard') , array('controller' => 'messages', 'action' => 'inbox') , array('class' => 'js-compose-delete compose-delete','title' => __l('Discard')) , null, false); ?></div>
     <div class="cancel-block"><?php echo $this->Html->link(__l('Cancel'), array('controller' => 'messages', 'action' => 'inbox') , array('title' => __l('Cancel'))); ?>
</div></div>
</div>
<?php echo $this->Form->end(); ?>
</div>