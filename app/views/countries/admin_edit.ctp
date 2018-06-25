<?php /* SVN: $Id: admin_edit.ctp 8670 2010-06-18 07:06:14Z vidhya_112act10 $ */ ?>

           
       
            <?php echo $this->Form->create('Country', array('action' => 'edit', 'class' => 'normal'));?>
			<legend class="crumb"><?php echo $this->Html->link(__l('Countries'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Country');?></legend>
            <?php
            echo $this->Form->input('id');
            echo $this->Form->input('name',array('label' => __l('Name')));
            echo $this->Form->input('fips_code',array('label' => __l('Fips_code')));
    		echo $this->Form->input('iso_alpha2',array('label' => __l('Iso_alpha2')));
    		echo $this->Form->input('iso_alpha3',array('label' => __l('Iso_alpha3')));
    		echo $this->Form->input('iso_numeric',array('label' => __l('Iso_numeric')));
    		echo $this->Form->input('capital',array('label' => __l('Capital')));
    		echo $this->Form->input('currencyName',array('label' => __l('Currency')));
    		echo $this->Form->input('currency',array('label' => __l('Currency Code')));
    		echo $this->Form->input('population', array('label' => __l('Population'),'info' => 'Eg: 2001600'));
            ?>
            <div class="submit-block clearfix">
                <?php echo $this->Form->submit(__l('Update'));?>
            </div>
            <?php echo $this->Form->end(); ?>
          
  
