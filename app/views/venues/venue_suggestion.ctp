<?php /* SVN: $Id: $ */ ?>
<div class="venues form">
<div class="form-content-block">
<?php echo $this->Form->create('Venue', array('class' => 'normal', 'enctype' => 'multipart/form-data'));?>
<fieldset>
<?php
	echo $this->Form->input('id');
	echo $this->Form->input('form_type',array('type'=>'hidden','value'=>'suggestion'));
	echo $this->Form->input('name',array('label' => __l('Venue Name'),'info'=>'Enter the name of the venue where your event is being held'));
	echo $this->Form->input('address', array('info' => __l('Mention where your venue is located so that your guests don\'t end up searching for the location')));
	echo $this->Form->input('address2', array('info' => __l('Mention where your venue is located so that your guests don\'t end up searching for the location')));
	echo $this->Form->input('zip_code',array('label' => __l('ZIP code')));
	echo $this->Form->autocomplete('City.name', array('label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255'));
	echo $this->Form->input('country_id');
	echo $this->Form->input('street');
	echo $this->Form->input('phone', array('info' => __l('For e.g., xxx-xxx-xxxx', true)));
	echo $this->Form->input('email');
	echo $this->Form->input('website');
	echo $this->Form->input('description',array('info'=>__l('Put in a short description about your venue  so that your guests will have a better idea about where they are going and how to get there!')));
	echo $this->Form->input('door_policy');
	echo $this->Form->input('is_closed');
	echo $this->Form->input('import_beer_price_id',array('type'=>'select','options'=>$BeerPrice,'empty'=>'--Unknown--'));
	echo $this->Form->input('domestic_beer_price_id',array('type'=>'select','options'=>$BeerPrice,'empty'=>'--Unknown--'));
	echo $this->Form->input('well_drink_price_id',array('type'=>'select','options'=>$BeerPrice,'empty'=>'--Unknown--'));
	echo $this->Form->input('soft_drink_price_id',array('type'=>'select','options'=>$BeerPrice,'empty'=>'--Unknown--'));
	echo $this->Form->input('parking_type',array('multiple'=>'checkbox','options'=>$parkingTypes, 'label'=>'Parking Type')); 
	echo $this->Form->input('VenueFeature',array('multiple'=>'checkbox','options'=>$venueFeatures, 'label'=>'Venue Feature'));
	echo $this->Form->input('capacity');
	echo $this->Form->input('employee_size_id',array('type'=>'select','options'=>$EmployeeSize,'empty'=>'--Unknown--'));
	echo $this->Form->input('square_footage_id',array('type'=>'select','options'=>$SquareFootage,'empty'=>'--Unknown--'));
	echo $this->Form->input('venue_category',array('multiple'=>'checkbox','options'=>$venueCategories, 'label'=>'Veneue category')); 
	echo $this->Form->input('venue_music',array('multiple'=>'checkbox','options'=>$venueMusics, 'label'=>'Music'));?>
	<div class="submit-block clearfix">
    	<?php echo $this->Form->end(__l('Send')); ?>
    </div>
</div>
</div>