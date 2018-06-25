<?php /* SVN: $Id: $ */ ?>
<?php if (empty($this->request->params['prefix'])): ?>
	<div class="crumb">
	<?php $this->Html->addCrumb(__l('Venues'), array('controller' => 'venues', 'action' => 'index')); ?>
	<?php $this->Html->addCrumb($this->Html->cText($this->request->data['Venue']['name'], false)); ?>
	<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
	echo $this->Html->getCrumbs(' &raquo; ');
	else:
	echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
	 endif;?>
	</div>
<?php endif; ?>
<?php if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) { ?>
	<h2><?php echo __l('Edit Venue');?></h2>
<?php } else { ?>
	<h2><?php echo __l('Edit Venue');?></h2>
<?php } ?>
<div class="venues form">

	<?php echo $this->Form->create('Venue', array('class' => 'normal venue-edit js-show-map', 'enctype' => 'multipart/form-data'));?>
		<?php echo $this->Form->input('id'); ?>
		<div class="js-tabs">
			<ul class="clearfix menu-tabs">
				<li> <?php echo $this->Html->link(__l('General') , '#tabs-1');?></li>
				<li> <?php echo $this->Html->link(__l('Categories') ,'#tabs-2');?></li>
				<li> <?php echo $this->Html->link(__l('Inside Info') ,'#tabs-3');?></li>
				<li><?php echo $this->Html->link(__l('Business Info') ,'#tabs-4');?></li>
				<li><?php echo $this->Html->link(__l('Upload') , '#tabs-5');?></li>
			</ul>
         <div id="tabs-1" class="form-content-block">
				<fieldset class="group-block round-5">
					<legend class="round-5 crumb"><?php echo __l('General Info'); ?></legend>
					<?php
						if(!empty($this->request->params['admin'])):		
							echo $this->Form->input('user_id', array( 'label' => __l('Users')));
						endif;
						echo $this->Form->input('venue_type_id', array('label' => __l('Venue Type'),'empty'=>'select','info'=>__l('Mention the category your venue falls under')));
						echo $this->Form->input('name',array('label' => __l('Venue Name'),'info'=>'Enter the name of the venue where your event is being held'));
						echo $this->Form->input('address', array('info' => __l('Mention where your venue is located so that your guests don\'t end up searching for the location')));
						echo $this->Form->input('address2', array('info' => __l('Mention where your venue is located so that your guests don\'t end up searching for the location')));
						echo $this->Form->input('zip_code', array('label' => __l('ZIP code')));
						echo $this->Form->autocomplete('City.name', array('id'=>'VenueCityName', 'label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255'));
						echo $this->Form->input('country_id', array('type' => 'hidden'));
						echo $this->Form->input('street', array('label' => __l('Cross Street')));
						echo $this->Form->input('phone', array('info' => __l('For e.g., xxx-xxx-xxxx', true)));
					    echo $this->Form->input('email');
						echo $this->Form->input('website', array('info' => __l('eg. http://www.example.com')));
						echo $this->Form->input('description',array('info'=>__l('Put in a short description about your venue  so that your guests will have a better idea about where they are going and how to get there!')));
						echo $this->Form->input('latitude', array('type' => 'hidden', 'id' => 'latitude'));
						echo $this->Form->input('longitude', array('type' => 'hidden', 'id' => 'longitude'));
						if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
						echo $this->Form->input('is_feature', array('type' => 'checkbox', 'label' => __l('Set as Featured')));
						endif;
					?>
					<div id="js-map-container" class="hide"></div>
				</fieldset>
				<?php if (!empty($venueSponsors)): ?>
					<fieldset class="group-block round-5">
						<legend class="round-5"><?php echo __l('Venue Sponsors'); ?></legend>
						<div class="clearfix">
							<?php echo $this->Form->input('VenueSponsor', array('multiple' => 'checkbox', 'options' => $venueSponsors, 'label' => false)); ?>
						</div>
					</fieldset>
				<?php endif; ?>
				</div>
   	         <div id="tabs-2" class="form-content-block">
				<fieldset class="group-block round-5">
					<legend class="round-5"><?php echo __l('Venue Categories'); ?></legend>
					<div class="clearfix">
						<?php echo $this->Form->input('venue_category', array('multiple' => 'checkbox', 'options' => $venueCategories, 'label' => false)); ?>
					</div>
				</fieldset>
			
				<fieldset class="group-block round-5">
					<legend class="round-5"><?php echo __l('Venue Music Types'); ?></legend>
					<div class="clearfix">
						<?php echo $this->Form->input('venue_music', array('multiple' => 'checkbox', 'options' => $venueMusics, 'label' => false)); ?>
					</div>
				</fieldset>
		</div>
		<div id="tabs-3" class="form-content-block">
				<fieldset class="group-block round-5">
					<legend class="round-5"><?php echo __l('Insider Info'); ?></legend>
					<?php
						echo $this->Form->input('is_closed');
						echo $this->Form->input('import_beer_price_id', array('type' => 'select', 'options' => $BeerPrice, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('domestic_beer_price_id', array('type' => 'select', 'options' => $BeerPrice, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('well_drink_price_id', array('type' => 'select', 'options' => $BeerPrice, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('soft_drink_price_id', array('type' => 'select', 'options' => $BeerPrice, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('food_sold_id', array('type' => 'select', 'options' => $FoodSold, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('live_band_id', array('type' => 'select', 'options' => $LiveBand, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('guest_dj_id', array('type' => 'select', 'options' => $LiveBand, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('door_policy');
					?>
				</fieldset>
				<fieldset class="group-block round-5">
					<legend class="round-5"><?php echo __l('Parking Types'); ?></legend>
					<div class="clearfix">
						<?php  echo $this->Form->input('parking_type', array('multiple' => 'checkbox', 'options' => $parkingTypes, 'label' => false)); ?>
					</div>
				</fieldset>
				<?php if (!empty($venueFeatures)): ?>
					<fieldset class="group-block round-5">
						<legend class="round-5"><?php echo __l('Venue Features'); ?></legend>
						<div class="clearfix">
							<?php echo $this->Form->input('venue_feature', array('multiple' => 'checkbox', 'options' => $venueFeatures, 'label' => false)); ?>
						</div>
					</fieldset>
				<?php endif; ?>
				</div>
				<div id="tabs-4" class="form-content-block">
				<fieldset class="group-block round-5">
					<legend class="round-5"><?php echo __l('Business Info'); ?></legend>
					<?php
						echo $this->Form->input('capacity', array('label' => __l('Max Capacity')));
						echo $this->Form->input('employee_size_id', array('type' => 'select', 'options' => $EmployeeSize, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('square_footage_id',array('type' => 'select', 'options' => $SquareFootage, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('sales_volume_id',array('type' => 'select', 'options' => $SalesVolume, 'empty' => __l('-- Unknown --')));
						echo $this->Form->input('contact_name');
						echo $this->Form->input('contact_phone');
						echo $this->Form->input('contact_email');
						echo $this->Form->input('contact_fax');
						echo $this->Form->input('contact_preference_id', array('type' => 'select', 'options' => $cellproviders, 'empty' => __l('Please Select')));
					?>
				</fieldset>
					</div>
				<div id="tabs-5" class="form-content-block">
				<fieldset class="group-block round-5">
						<legend class="round-5"><?php echo __l('Venue Image'); ?></legend>
                            <div class="profile-image">
                            <?php
    							//if (!empty($this->request->data['Attachment']['id'])):
    								echo $this->Html->showImage('Venue', $this->request->data['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($this->request->data['Venue']['name'], false)), 'title' => $this->Html->cText($this->request->data['Venue']['name'], false)));
    							//endif;
    						?>
						</div>
				
							<?php echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => false));?>
				
					</fieldset>
					<fieldset class="group-block round-5">
						<legend class="round-5"><?php echo __l('Venue Logo'); ?></legend>
    						<div class="profile-image">
        						<?php
        							if (!empty($this->request->data['VenueLogo']['id'])):
        								echo $this->Html->showImage('VenueLogo', $this->request->data['VenueLogo'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($this->request->data['Venue']['name'], false)), 'title' => $this->Html->cText($this->request->data['Venue']['name'], false)));
        							endif;
        						?>
                            </div>
							<?php echo $this->Form->input('Logo.filename', array('type' => 'file', 'label' => false));?>
				
					</fieldset>
				
					<?php if($this->request->data['Venue']['is_venue_enhanced_page'] == 1 && $this->request->data['Venue']['is_paid'] == 1) { ?>
					<fieldset class="group-block round-5">
						<legend class="round-5"><?php echo __l('Wide Screen'); ?></legend>
						<?php
							if (!empty($this->request->data['WideScreen']['id'])):
								echo $this->Html->showImage('Venue', $this->request->data['WideScreen'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($this->request->data['Venue']['name'], false)), 'title' => $this->Html->cText($this->request->data['Venue']['name'], false)));
							endif;
						?>
						<div class="edit-img">
							<?php echo $this->Form->input('WideScreen.filename', array('type' => 'file', 'label' => false));?>
						</div>
					</fieldset>
					<?php } ?>
					</div>
				</div>
    			<div class="submit-block clearfix">
            		<?php echo $this->Form->submit(__l('Update Venue'));?>
                </div>
            	
		</div>
            <?php echo $this->Form->end(); ?>

