<?php /* SVN: $Id: $ */ ?>
<div class="forums form">
    <div id="breadcrumb" class="crumb">
    	<?php echo $this->Html->addCrumb(__l('Forums'), array('controller' => 'forum_categories', 'action' => 'index')); ?>
    	<?php echo $this->Html->addCrumb(__l('Add')); ?>
    	<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
         echo $this->Html->getCrumbs(' &raquo; ');
      	 else:
    	 echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
     	 endif;?>
    </div>
    <?php if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
       <h2><?php echo __l('Add Forum');?></h2>
	<?php } ?>
    <?php echo $this->Form->create('Forum', array('class' => 'normal'));?>
	<?php
        echo $this->Form->input('forum_category_id', array('empty' => '-- Please Select --'));
        if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) :
            echo $this->Form->input('user_id', array('empty' => '-- Please Select --'));
        else :
       		echo $this->Form->input('user_id',array('type' => 'hidden', 'value' => $this->Auth->user('id')));
        endif;
		echo $this->Form->input('title');
		echo $this->Form->input('description');
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin) :
    		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
        endif;
    ?>
    <div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>

</div>