<?php /* SVN: $Id: $ */ ?>
<div class="eventCategories form">

<?php echo $this->Form->create('EventCategory', array('class' => 'normal'));?>
<legend><?php echo $this->Html->link(__l('Event Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Event Category');?></legend>
<?		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
?>
		<div class="submit-block clearfix">
            <?php echo $this->Form->end(__l('Update')); ?>
        </div>

</div>