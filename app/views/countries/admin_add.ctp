<?php /* SVN: $Id: admin_add.ctp 8966 2010-06-19 11:35:48Z subingeorge_082at09 $ */ ?>
<div class="countries form">
    <div>
        <div>
            <h2><?php echo __l('Add Country'); ?></h2>
        </div>
        <div>
            <div class="form-content-block">
            <?php echo $this->Form->create('Country', array('class' => 'normal','action'=>'add'));?>
			<legend class="crumb"><?php echo $this->Html->link(__l('Countries'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Country');?></legend>
        	      	<div class="required">
        		<?php echo $this->Form->input('name',array('label' => __l('Name')));?></div>
        		<?php echo $this->Form->input('fips_code',array('label' => __l('Fips_code')));
        		echo $this->Form->input('iso_alpha2',array('label' => __l('Iso_alpha2')));
        		echo $this->Form->input('iso_alpha3',array('label' => __l('Iso_alpha3')));
        		echo $this->Form->input('iso_numeric',array('label' => __l('Iso_numeric')));
        		echo $this->Form->input('capital',array('label' => __l('Capital')));
        		echo $this->Form->input('currencyName',array('label' => __l('Currency')));
        		echo $this->Form->input('currency',array('label' => __l('Currency Code')));
        		echo $this->Form->input('population', array('label' => __l('Population'),'info' => 'Eg. 2001600'));
        	?>
            <div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Add'));?>
             </div>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
