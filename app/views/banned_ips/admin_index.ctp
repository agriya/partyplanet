<?php /* SVN: $Id: admin_index.ctp 1711 2010-05-04 11:12:13Z vinothraja_091at09 $ */ ?>
<div class="bannedIps index js-response">
<div class="page-count-block clearfix">
	<div class="grid_left">
	<?php echo $this->element('paging_counter'); ?>
	</div>
<div class="add-block clearfix grid_right">
        <?php echo $this->Html->link(__l('Add'), array('controller' => 'banned_ips', 'action' => 'add'), array('class' => 'add','title' => __l('Add'))); ?>
  </div>
</div>
   <?php echo $this->Form->create('BannedIp' , array('class' => 'normal clearfix', 'action' => 'update')); ?>
		<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
		<table class="list">
			<tr>
				<th><?php echo __l('Select'); ?></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Victims'), 'address');?></div></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Reason'), 'reason');?></div></th>
				<th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Redirect to'), 'redirect');?></div></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date Set'), 'thetime');?></div></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Expiry Date'), 'timespan');?></div></th>
			</tr>
			<?php
			if (!empty($bannedIps)):
				$i = 0;
				foreach ($bannedIps as $bannedIp):
					$class = null;
					if ($i++ % 2 == 0) :
						$class = ' class="altrow"';
					endif;
					?>
					<tr<?php echo $class;?>>
						<td>
							<div class="action-content"><div class="actions"><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $bannedIp['BannedIp']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></div></div>
							<?php echo $this->Form->input('BannedIp.'.$bannedIp['BannedIp']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$bannedIp['BannedIp']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
						</td>
						<td class="dl">
							<?php
								if ($bannedIp['BannedIp']['referer_url']) :
									echo $bannedIp['BannedIp']['referer_url'];
								else:
									echo long2ip($bannedIp['BannedIp']['address']);
									if ($bannedIp['BannedIp']['range']) :
										echo ' - '.long2ip($bannedIp['BannedIp']['range']);
									endif;
								endif;
							?>
						</td>
						<td class="dl"><?php echo $this->Html->cText($bannedIp['BannedIp']['reason']);?></td>
						<td class="dl"><?php echo $this->Html->cText($bannedIp['BannedIp']['redirect']);?></td>
						<td class="dc"><?php echo _formatDate('M d, Y h:i A', $bannedIp['BannedIp']['thetime']); ?></td>
						<td class="dc"><?php echo ($bannedIp['BannedIp']['timespan'] > 0) ? _formatDate('M d, Y h:i A', $bannedIp['BannedIp']['thetime']) : __l('Never');?></td>
					</tr>
			<?php
				endforeach;
			else:
			?>
				<tr>
					<td colspan="7" class="notice"><?php echo __l('No Banned IPs available');?></td>
				</tr>
			<?php
			endif;
			?>
		</table>
		<?php if (!empty($bannedIps)): ?>
			<div class="admin-select-block">
				<?php echo __l('Select:'); ?>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
			</div>
			<div class="js-pagination">
				<?php echo $this->element('paging_links'); ?>
			</div>
			<div class="admin-checkbox-button clearfix">
				<?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
			</div>
			<div class="hide">
				<?php echo $this->Form->submit(__l('Submit'));  ?>
			</div>
		<?php endif; ?>
    <?php echo $this->Form->end(); ?>
</div>