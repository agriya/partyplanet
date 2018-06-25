
	<h3><?php echo __l('Venue')?><span><?php echo __l(' Details:')?></span></h3>
	<dl class="list  clearfix">
		<dt><?php echo __l('Name:'); ?></dt>
			<dd><?php echo $this->Html->cText($event['Venue']['name'], false); ?></dd>
		<?php if (!empty($event['Venue']['address'])): ?>
			<dt><?php echo __l('Address:'); ?></dt>
				<dd><?php echo $this->Html->cText($event['Venue']['address'], false); ?></dd>
				<?php endif; ?>
			<dt><?php echo __l('City:'); ?></dt>
			<dd><?php echo $this->Html->cText($event['Venue']['City']['name'], false); ?></dd>
			<dt><?php echo __l('Country:'); ?></dt>
			<dd><?php echo $this->Html->cText($event['Venue']['Country']['name'], false); ?></dd>
		<?php if (!empty($event['City']['name'])): ?>
			<dt><?php echo __l('City:'); ?></dt>
				<dd><?php echo $this->Html->cText($event['City']['name'], false); ?></dd>
		<?php endif; ?>
				<?php if (!empty($event['Venue']['zip_code'])): ?>
			<dt><?php echo __l('ZIP code:'); ?></dt>
				<dd><?php echo $this->Html->cText($event['Venue']['zip_code'], false); ?></dd>
		<?php endif; ?>
	</dl>
