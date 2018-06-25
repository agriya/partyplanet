<div class="party-block">
		<ul class="party-list">
			<li>
				<a class="js-toggle-div {'divClass':'js-venue-type'}" href="javascript:void(0);" title="Venue Type">Venue Type</a>
				<ul class="search-sub-list js-venue-type clearfix">
					<?php foreach($venueTypes as $venueType) { ?>
						<li><?php echo $this->Html->link(__l($venueType['VenueType']['name']), array('controller' => 'venues', 'action' => 'index','category'=>$venueType['VenueType']['slug']), array('title'=>__l($venueType['VenueType']['name']),'escape' => false));?></li>
					<?php } ?>
				</ul>
			</li>
			<li>
				<a class="js-toggle-div {'divClass':'js-event-type'}" href="javascript:void(0);" title="Event Category">Event Category</a>
				<ul class="search-sub-list js-event-type clearfix">
					<?php foreach($eventCategories as $eventCategory) { ?>
						<li><?php echo $this->Html->link(__l($eventCategory['EventCategory']['name']), array('controller' => 'events', 'action' => 'index','category'=>$eventCategory['EventCategory']['slug']), array('title'=>__l($eventCategory['EventCategory']['name']),'escape' => false));?></li>
					<?php } ?>
				</ul>
			</li>
			<li>
				<a class="js-toggle-div {'divClass':'js-music-type'}" href="javascript:void(0);" title="Music Type">Music Type</a>
				<ul class="js-music-type search-sub-list clearfix">
					<?php foreach($eventMusics as $eventMusic) {?>
						<li><?php echo $this->Html->link(__l($eventMusic['MusicType']['name']), array('controller' => 'events', 'action' => 'index','music'=>$eventMusic['MusicType']['slug']), array('title'=>__l($eventMusic['MusicType']['name']),'escape' => false));?></li>
					<?php } ?>
				</ul>
			</li>
		</ul>

</div>