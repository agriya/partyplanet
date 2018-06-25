<?php /* SVN: $Id: $ */ ?>
<div class='js-response'>
	<h2><?php echo __l('Advanced') . ' '; ?><span><?php echo __l('Search'); ?></span></h2>
	<?php
		$cities = array();
		foreach ($venueCities as $venueCity):
			$cities[$venueCity['City']['id']]= $this->Html->link(__l(sprintf('%s (%s)',$venueCity['City']['name'], $venueCity[0]['venue_count'])), array('controller'=> 'venues', 'action' => 'index', 'city' => $venueCity['City']['slug']), array('title' => __l(sprintf('%s (%s)', $venueCity['City']['name'], $venueCity[0]['venue_count'])), 'escape' => false));
		endforeach;
		$venuetypes = array();
		foreach ($venueTypes as $venueType):
			$count = 0;
			if (!empty($venueTypeVenueCount[$venueType['VenueType']['id']])):
				$count = $venueTypeVenueCount[$venueType['VenueType']['id']];
			endif;
			$venuetypes[$venueType['VenueType']['id']] = $this->Html->link(__l(sprintf('%s (%s)', $venueType['VenueType']['name'], $count)), array('controller'=> 'venues', 'action' => 'index', 'category' => $venueType['VenueType']['slug']), array('title' => __l(sprintf('%s (%s)', $venueType['VenueType']['name'], $count)), 'escape' => false));
		endforeach;
		$musictypes = array();
		foreach ($musicTypes as $musicType):
			$count = 0;
			if (!empty($musicTypeVenueCount[$musicType['MusicType']['id']])):
				$count = $musicTypeVenueCount[$musicType['MusicType']['id']];
			endif;
			$musictypes[$musicType['MusicType']['id']] = $this->Html->link(__l(sprintf('%s (%s)', $musicType['MusicType']['name'], $count)), array('controller'=> 'venues', 'action' => 'index', 'music' => $musicType['MusicType']['slug']), array('title' => __l(sprintf('%s (%s)', $musicType['MusicType']['name'], $count)), 'escape' => false));
		endforeach; ?>
		<div class="form-content-block event-search-block">
		<?php echo $this->Form->create('Venue', array('class' => 'normal clearfix', 'action' => 'index/type:search', 'id' => 'VenueFilterForm'));
    		echo $this->Form->input('zip_code', array('label' => __l('ZIP code')));
    	?>
	<div class="or"><?php echo __l('OR'); ?></div>
	<?php if (!empty($cities)): ?>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('City'); ?></legend>
			<?php echo $this->Form->input('City', array('multiple' => 'checkbox', 'options' => $cities, 'escape' => false, 'label' => false)); ?>
		</fieldset>
	<?php endif; ?>
	<?php if (!empty($venuetypes)): ?>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Venue Type'); ?></legend>
				<?php echo $this->Form->input('VenueType',array('multiple'=>'checkbox','options'=>$venuetypes, 'escape'=>false, 'label' => false)); ?>
		</fieldset>
	<?php endif; ?>
	<?php if (!empty($musictypes)): ?>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Music Type'); ?></legend>
			<?php echo $this->Form->input('Venue.MusicType',array('multiple'=>'checkbox','options'=>$musictypes, 'escape'=>false, 'label' => false)); ?>
		</fieldset>
	<?php endif; ?>
	<div class="submit-block clearfix">
    	<?php echo $this->Form->submit(__l('Search')); ?>
    </div>
        <?php echo $this->Form->end(); ?>
	</div>
</div>