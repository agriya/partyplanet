<?php /* SVN: $Id: admin_view.ctp 17349 2012-02-07 05:54:33Z beautlin_108ac10 $ */ ?>
<div id="breadcrumb">
	<?php 
		echo $this->Html->addCrumb(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'index'));
		echo $this->Html->addCrumb(__l('Contact'));
		echo $this->Html->getCrumbs(' &raquo; ');
	?>
</div>

<div class="contacts view clearfix">
    <dl class="clearfix party-planner"><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($contact['Contact']['created']);?></dd>
			<?php if(!empty($contact['User']['username'])):?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($contact['User']['username']), array('controller' => 'users', 'action' => 'view', $contact['User']['username'], 'admin' => false), array('escape' => false));?></dd>
      <?php endif;?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Contact Type');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['ContactType']['name']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('First Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['Contact']['first_name']);?></dd>
		<?php if(!empty($contact['Contact']['last_name'])):?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Last Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['Contact']['last_name']);?></dd>
			<?php endif;?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['Contact']['email']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Subject');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['Contact']['subject']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Message');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['Contact']['message']);?></dd>
		<?php if(!empty($contact['Contact']['telephone'])):?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Telephone');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($contact['Contact']['telephone']);?></dd>
			<?php endif;?>
	</dl>
	
</div>

