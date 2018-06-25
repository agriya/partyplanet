<?php /* SVN: $Id: $ */ ?>
<?php if (empty($this->request->params['prefix'])): ?>
	<div class="crumb">
			<?php $this->Html->addCrumb(__l('Venues'), array('controller' => 'venues', 'action' => 'index')); ?>
			<?php $this->Html->addCrumb(__l('Add a Venue')); ?>
             	<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
                 echo $this->Html->getCrumbs(' &raquo; ');
  	             else:
	              echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
 	              endif;?>
		</div>
<?php endif; ?>
<?php if($this->Auth->user('user_type_id') == ConstUserTypes::User):?>
<div class="event-link">
 <?php echo __l('Venue Owner can only add venues.Others not allowed to add venues'); ?>
  </div>
<?php else:
?>
<div class="venues form">
	<div class="clearfix">
	<?php echo $this->Form->create('Venue', array('class' => 'js-show-map normal', 'enctype' => 'multipart/form-data')); ?>
			<!--fieldset class="group-block round-5">
				<legend class="round-5"><?php echo __l('Premium Services'); ?></legend>
				<h3><?php //echo __l('Have Your Venue Go Live Immediately!'); ?></h3>
				<span><?php //echo __l('Please select either one or both of the options below: '); ?></span>
				<h3><?php //echo __l('Featured Venue (Prominent placement throughout'). '   '.Configure::read('site.name').')'; ?></h3>
				<?php //echo $this->Form->input('is_featured', array( 'label' => __l('Check to make featured for:'))) . ' ' . $this->Form->input('featured_venue_subscription_id', array( 'label' => __l(''))); ?>
				<h3><?php //echo __l('Enhanced Venue Page (More features, looks better)'); ?></h3>
				<?php //echo $this->Form->input('is_venue_enhanced_page', array( 'label' => __l('check to upgrade') . ' (' . __l('one time fee of') . ' ' . Configure::read('site.currency') . Configure::read('site.is_venue_enhanced_amount') . ')' )); ?>
				<h3><?php //echo __l('Generic Listing (without any options chosen)'); ?></h3>
				<?php //echo $this->Form->input('is_bump_up', array( 'label' => __l('check to upgrade') . ' (' . __l('one time fee of') . ' ' . Configure::read('site.currency') . Configure::read('site.is_venue_bumpup_amount') . ')' )); ?>
			</fieldset--> 
			<fieldset class="group-block round-5">
				<legend class="round-5 crumb"><?php echo __l('General Info'); ?></legend>
			<?php
				if(!empty($this->request->params['admin'])):
					echo $this->Form->input('user_id', array( 'label' => __l('Users')));
				endif;
				echo $this->Form->input('venue_type_id', array('label' => __l('Venue Type'), 'empty' => __l('Please Select'), 'info' => __l('Mention the category your venue falls under')));
				echo $this->Form->input('name', array('id'=>'AddVenueName','label' => __l('Venue Name'),'info' => __l('Enter the name of the venue where your event is being held')));
				echo $this->Form->input('address', array('info' => __l('Mention where your venue is located so that your guests don\'t end up searching for the location')));
				echo $this->Form->input('address2', array('info' => __l('Mention where your venue is located so that your guests don\'t end up searching for the location')));
				echo $this->Form->input('zip_code', array('label' => __l('ZIP code')));
				echo $this->Form->autocomplete('City.name', array('id'=>'VenueCityName', 'label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255'));
				echo $this->Form->input('country_id', array('type' => 'hidden'));
				echo $this->Form->input('street', array('label' => __l('Cross Street')));
				echo $this->Form->input('phone', array('info' => __l('For e.g., xxx-xxx-xxxx', true)));
				echo $this->Form->input('email');
				echo $this->Form->input('website', array('info' => __l('eg. http://www.example.com')));
				echo $this->Form->input('description',array('info' => __l('Put in a short description about your venue  so that your guests will have a better idea about where they are going and how to get there!')));
			?>
			</fieldset>
			<fieldset class="group-block round-5">
				<legend class="round-5 crumb"><?php echo __l('Venue Image'); ?></legend>
				<?php
					echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Venue Image'), 'info' => __l('Add an image of your venue')));
					echo $this->Form->input('latitude', array('type' => 'hidden', 'id' => 'latitude'));
					echo $this->Form->input('longitude', array('type' => 'hidden', 'id' => 'longitude'));
				?>
				<div id="js-map-container" class="hide"></div>
			</fieldset>
			<?php if(!empty($venueSponsors)) { ?>
				<fieldset class="group-block round-5 ">
					<legend class="round-5 crumb"><?php echo __l('Venue Sponsors'); ?></legend>
					<div class="clearfix">
						<?php echo $this->Form->input('VenueSponsor',array('multiple' => 'checkbox','options' => $venueSponsors, 'label' => false)); ?>
					</div>
				</fieldset>
			<?php } ?>
			<fieldset class="group-block round-5">
				<legend class="round-5 crumb"><?php echo __l('Venue Categories'); ?></legend>
				<div class="clearfix">
					<?php echo $this->Form->input('venue_category',array('multiple' => 'checkbox', 'options' => $venueCategories, 'label' => false)); ?>
				</div>
			</fieldset>
			<fieldset class="group-block round-5">
				<legend class="round-5 crumb"><?php echo __l('Venue Music Types'); ?></legend>
				<div class="clearfix">
					<?php echo $this->Form->input('venue_music',array('multiple' => 'checkbox', 'options' => $venueMusics, 'label' => false)); ?>
				</div>
			</fieldset>
			<?php 	if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):?>
			<fieldset class="group-block round-5">
             <legend class="round-5 crumb"><?php echo __l('Admin Actions'); ?></legend>
				<?php echo $this->Form->input('is_feature', array('label' => __l('Set as Featured'), 'type' => 'checkbox')); ?>
				<?php
					$page = '';
					if (isset($this->request->params['named']['page'])) {
						$page = $this->request->params['named']['page'];
					} elseif(isset($this->request->data['Venue']['page'])) {
						$page = $this->request->data['Venue']['page'];
					}
				?>
				<?php echo $this->Form->input('page',array('type'=>'hidden','value' => $page)); ?>
			</fieldset>
			<?php endif; ?>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Insert New Venue'));?>
        </div>
            <?php echo $this->Form->end(); ?>
	</div>
</div>
<?php endif;?>