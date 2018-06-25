<?php /* SVN: $Id: $ */ ?>

<div class="crumb">
	<?php $this->Html->addCrumb(__l('Forums'), array('controller' => 'forums', 'action' => 'index')); ?>
	<?php $this->Html->addCrumb($this->Html->cText($this->request->data['Forum']['title'], false)); ?>
	<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
     echo $this->Html->getCrumbs(' &raquo; ');
  	 else:
	 echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
 	 endif;?>
</div>
<div class="forums form">
<?php echo $this->Form->create('Forum', array('class' => 'normal'));?>
	<fieldset>
 	<?php
    	echo $this->Form->input('forum_category_id');
    	if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) :
            echo $this->Form->input('user_id');
    	endif;
		echo $this->Form->input('title');
		echo $this->Form->input('description');
        if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) :
    		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
    	endif;
    ?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Update'));?>
    </div>
     <?php echo $this->Form->end(); ?>
</div>
