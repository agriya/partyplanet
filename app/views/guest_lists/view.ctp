<?php /* SVN: $Id: $ */ ?>
<div class="guestLists view">
<h2><?php echo __l('Guest List');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($guestList['GuestList']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($guestList['GuestList']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($guestList['GuestList']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($guestList['GuestList']['name']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Details');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($guestList['GuestList']['details']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Guest Limit');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($guestList['GuestList']['guest_limit']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Event');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($guestList['Event']['title'],false), array('controller' => 'events', 'action' => 'view', $guestList['Event']['slug']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Maximum Guest Limit');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($guestList['GuestList']['maximum_guest_limit']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Maximum Guest Of Guest');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($guestList['GuestList']['maximum_guest_of_guest']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Website Close Time');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cTime($guestList['GuestList']['website_close_time']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Guest Close Time');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cTime($guestList['GuestList']['guest_close_time']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Fax');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($guestList['GuestList']['fax']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($guestList['GuestList']['email']);?></dd>
	</dl>
</div>

