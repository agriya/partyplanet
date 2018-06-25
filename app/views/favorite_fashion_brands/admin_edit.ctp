<?php /* SVN: $Id: $ */ ?>
<div class="favoriteFashionBrands form">
<div class="form-content-block">
<?php echo $this->Form->create('FavoriteFashionBrand', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Favorite Fashion Brands'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Favorite Fashion Brand');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>
