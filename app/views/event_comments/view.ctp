<?php /* SVN: $Id: $ */ ?>
<div class="eventComments view">
<h2><?php echo __l('Event Comment');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($eventComment['EventComment']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($eventComment['EventComment']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($eventComment['EventComment']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($eventComment['User']['username']), array('controller' => 'users', 'action' => 'view', $eventComment['User']['username']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Event');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($eventComment['Event']['title'],false), array('controller' => 'events', 'action' => 'view', $eventComment['Event']['slug']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Comment');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($eventComment['EventComment']['comment']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Active');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cBool($eventComment['EventComment']['is_active']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Ip');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($eventComment['EventComment']['ip']);?></dd>
	</dl>
</div>

