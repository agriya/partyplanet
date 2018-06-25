<div class="Section content-block">
		<?php if($type == ConstUserTypes::VenueOwner): ?>
			<div class="clearfix">Become a Venue Owner</div>
			<div class="Content">
				<ul>
					<li>Create, manage and update business info and specials</li>
					<li>Create and promote Events</li>
					<li>Offer a Guest list service to your clients</li>
					<li>Update venue photos</li>
					<li>Receive standard venue features including maps, an editorial profile, user comments, addresses, hours of operation, and bottle service options</li>
					<li>Build your client base (Regulars)</li>
					<li>Sell tickets &ndash; <?php echo Configure::read('site.name');?>.com offers a complete ticketing system (print tickets, ship tickets, mobile tickets, will-call)</li>
					<li>Offer ticketing for your events</li>
					<li>Receive Homepage real estate in the Featured Venues section with your logo, ensuring that the right demographic sees your brand</li>
					<li>Obtain Featured Events-placement on the Homepage.</li>
					<li>Take advantage of a super-sized, wide-screen image of your Venue on an enhanced Featured Venue page</li>
					<li>Customize the look of your Venue page</li>
					<li>Reap the benefits of no ads or competitors on the Featured Venue page.</li>
					<li>Offer a video walk-through of your venue.</li>
					<li>Bump Up your Venue Page and your Events to the top of the listings.</li>
				</ul>
			</div>
			<div class="FaqLink"><a href="/CommonPages/FAQ_Signup_VenueOwner.aspx">Venue Owner FAQ</a></div>
		<?php else: ?>
			<div class="clearfix">Become a <?php echo Configure::read('site.name');?> Member</div>
			<div class="Content">
				Join the hottest online nightlife community in the world. 
				<ul>
					<li>Set up meetups at events and venues with other <?php echo Configure::read('site.name');?> members and friends</li>
					<li>Get on the guestlist for reduced admission or priveleged access</li>
					<li>View photos and videos from other nightlife enthusiasts from around the world</li>
					<li>Get up-to-date information on special events and promotions in your area</li>
				</ul>
			</div>
			<div class="FaqLink"><?php echo $this->Html->link('Party-Goer FAQ', array('controller' => 'pages', 'action' => 'display', 'party_goer'), array('target' => 'blank','escape' => false));?></div>
		<?php endif; ?>
</div>