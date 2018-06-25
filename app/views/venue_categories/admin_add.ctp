<?php /* SVN: $Id: $ */ ?>
<div class="venueCategories form">
<div id="breadcrumb" class="crumb">
<legend class="crumb"><?php echo $this->Html->link(__l('Venue Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Venue Category');?></legend>
<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>

<?php echo $this->Form->create('VenueCategory', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Add'));?>
        </div>
        <?php echo $this->Form->end(); ?>
</div>
