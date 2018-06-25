<?php /* SVN: $Id: $ */ ?>
<div class="partyPlanners view clearfix">
	<dl class="clearfix party-planner"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['name']);?></dd>
        <?php if(!empty($partyPlanner['User']['username'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($partyPlanner['User']['username']), array('controller' => 'users', 'action' => 'view', $partyPlanner['User']['username'],'admin'=>false), array('escape' => false));?></dd>
			<?php endif;?>
			<?php if(!empty($partyPlanner['PartyPlanner']['address1'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Address1');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['address1']);?></dd>
			<?php endif;?>
			<?php if(!empty($partyPlanner['PartyPlanner']['address2'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Address2');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['address2']);?></dd>
			<?php endif;?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('City');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['City']['name']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('ZIP code');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $partyPlanner['PartyPlanner']['zip_code'];?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Country');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['Country']['name']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['email']);?></dd>
		<?php if(!empty($partyPlanner['PartyPlanner']['phone'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Phone');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['phone']);?></dd>
        <?php endif;?>
        <?php if(!empty($partyPlanner['PartyPlanner']['fax'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Fax');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['fax']);?></dd>
			<?php endif;?>
			<?php if(!empty($partyPlanner['CellProvider']['name'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Cell Provider');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['CellProvider']['name']);?></dd>
			<?php endif;?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Date');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDate($partyPlanner['PartyPlanner']['date']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Guest Count');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($partyPlanner['PartyPlanner']['guest_count']);?></dd>
			<?php if(!empty($partyPlanner['PartyPlanner']['venue'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Venue');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['venue']);?></dd>
			<?php endif?>
			<?php if(!empty($partyPlanner['PartyPlanner']['city_party_will_be_in'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('City Party Will Be In');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['city_party_will_be_in']);?></dd>
			<?php endif;?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Party Type');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyType']['name']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Is Guest Will To Pay Cover Charges');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo ($partyPlanner['PartyPlanner']['is_guest_will_to_pay_cover_charges']) ? __l('Yes') : __l('No');?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Is Interested In Bottle Service');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo ($partyPlanner['PartyPlanner']['is_interested_in_bottle_service'])?__l('Yes'): __l('No');?></dd>
			<?php if(!empty($partyPlanner['PartyPlanner']['comment'])):?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Comment');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['comment']);?></dd>
			<?php endif?>
	</dl>
</div>

