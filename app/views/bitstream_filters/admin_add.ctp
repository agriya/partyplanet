<?php /* SVN: $Id: admin_add.ctp 960 2009-09-18 15:58:52Z siva_063at09 $ */ ?>
<div class="bitstreamFilters form">
<div class="form-content-block">
<?php echo $this->Form->create('BitstreamFilter', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Bitstream Filters'), array('action' => 'index'),array('title' => __l('Bitstream Filters')));?> &raquo; <?php echo __l('Add Bitstream Filter');?></legend>
    	<?php echo $this->Form->input('name'); ?>
    	<?php echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active')));?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>
