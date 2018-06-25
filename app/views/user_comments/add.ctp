<?php /* SVN: $Id: $ */ ?>
<div class="userComments form clearfix js-add-user-comment-response">
          <?php echo $this->Form->create('UserComment', array('class' => "normal js-comment-form {container:'js-add-user-comment-response',responsecontainer:'js-index-user-comment-response'}"));?>
              	<fieldset>
        	<?php
        	      echo $this->Form->input('comment_user_id', array('type' => 'hidden'));?>
        	 	<div class="required">
        	 		<?php echo $this->Form->input('comment', array('label' => __l('Write on').'  '.$user['User']['username'].__l("'s"). ' '. __l('pad'),'type' => 'textarea'));?>
        		</div>
        	    </fieldset>
        	<div class="submit-block clearfix">
                <?php echo $this->Form->submit(__l('Submit'));?>
            </div>
                <?php echo $this->Form->end(); ?>
      
</div>