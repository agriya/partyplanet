<?php /* SVN: $Id: $ */ ?>
<?php if (empty($this->request->params['requested']) && empty($this->request->params['isAjax']) && empty($this->request->params['prefix'])): ?>
	<div class="crumb">
		<?php
			$this->Html->addCrumb(__l('Plan Your Party'));
			echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		?>
	</div>
<?php endif; ?>
<h2><?php echo __l('Plan Your Party');?></h2>
<div class="partyPlanners form form-content-block">
<?php
if(!$success){?>
<?php echo $this->Form->create('PartyPlanner', array('class' => 'normal clearfix'));?>
  <fieldset class="group-block round-5">
  <div class="info-details"> <?php echo __l('Thanks for choosing to plan your party. Please fillout form below and Admin will follow up with you shortly on your event.'); ?> </div>
	<?php
		echo $this->Form->input('name');
		if($this->Auth->user('user_type_id')==ConstUserTypes::Admin){
			echo $this->Form->input('user_id');
		}else{
			echo $this->Form->input('user_id',array('type'=>'hidden'));
		}
		echo $this->Form->input('company');
		echo $this->Form->input('address1');
		echo $this->Form->input('address2');
		echo $this->Form->input('city_id',array('empty'=>__l('Please Select')));
		echo $this->Form->input('zip_code',array('label' => __l('ZIP code')));
		echo $this->Form->input('country_id',array('empty'=>__l('Please Select')));
		echo $this->Form->input('email',array('label'=>__l('Your email')));
		echo $this->Form->input('phone',array('label'=>__l('Contact phone')));
		echo $this->Form->input('fax',array('label'=>__l('Contact fax')));
		echo $this->Form->input('cell_provider_id',array('label'=>__l('Contact preference'),'empty'=>__l('Please Select')));
		echo $this->Form->input('date',array('class'=>'js-date','label'=>__l('Date of party'),'empty'=>__l('Please Select')));
		echo $this->Form->input('guest_count',array('label'=>__l('Guest count')));
		echo $this->Form->input('venue',array('label'=>__l('Preferred Venue (if known)')));
		echo $this->Form->input('city_party_will_be_in',array('label'=>__l('City party will be in')));
		echo $this->Form->input('party_type_id',array('label'=>__l('What kind of party is this?'),'empty'=>__l('Please Select')));
		echo $this->Form->input('is_guest_will_to_pay_cover_charges',array('label'=>__l('Guest will to pay cover charges')));
		echo $this->Form->input('is_interested_in_bottle_service',array('label'=>__l('Interested in bottle service'))); ?>
        </fieldset>
        <fieldset class="group-block round-5">
            <legend class="round-5"><?php echo __l('Bar Service Type');?></legend>
            <div class="clearfix"><?php echo $this->Form->input('BarServiceType',array('multiple'=>'checkbox', 'label'=> false)); ?></div>
        </fieldset>
        <fieldset class="group-block round-5">
            <legend class="round-5"><?php echo __l('Food catering');?></legend>
   	    	<div class="clearfix"><?php echo $this->Form->input('FoodCatering',array('multiple'=>'checkbox', 'label'=> false)); ?></div>
        </fieldset>
        <fieldset class="group-block round-5">
         <legend class="round-5"><?php echo __l('Entertainment');?></legend>
          <div class="clearfix"><?php echo $this->Form->input('Entertainment',array('multiple'=>'checkbox', 'label'=> false)); ?></div>
             <?php echo $this->Form->input('comment'); ?>
        </fieldset>
        <fieldset class="group-block round-5">
            <legend class="round-5"><?php echo __l('Music type');?></legend>
            <div class="clearfix">
    		<?php echo $this->Form->input('MusicType',array('multiple'=>'checkbox', 'label'=> false)); ?>
    		</div>
		</fieldset>
		<fieldset class="group-block round-5">
		   <legend class="round-5"><?php echo __l('Event scene');?></legend>
    		<div class="clearfix">
    		<?php echo $this->Form->input('EventScene',array('multiple'=>'checkbox', 'label'=> false)); ?>
    		</div>
    	</fieldset>

<?php 
	if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) { 
		echo $this->Form->input('is_contacted', array('label' => __l('Contacted'), 'type' => 'checkbox'));

}?>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Submit'));?>
    </div>
        <?php echo $this->Form->end(); 

}else{
?>
<div class="submit-info"> <?php echo __l('Your information has been submitted! You will be contacted by one of our representatives very shortly to help plan your party'); ?> </div>
<?php
}
?>
</div>