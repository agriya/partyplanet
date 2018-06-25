<?php /* SVN: $Id: $ */ ?>
<div class="adaptiveIpnLogs index"> <?php echo $this->element('paging_counter');?>
  <table class="list">
    <tr>
      <th><?php echo $this->Paginator->sort('created', __l('Added On'));?></th>
      <th><?php echo $this->Paginator->sort('ip', __l('IP'));?></th>
      <th><?php echo $this->Paginator->sort('post_variable', __l('Post Variable'));?></th>
    </tr>
<?php
	if (!empty($adaptiveIpnLogs)):
		$i = 0;
		foreach ($adaptiveIpnLogs as $adaptiveIpnLog):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
?>
    <tr<?php echo $class;?>>
      <td><?php echo $this->Html->cDateTimeHighlight($adaptiveIpnLog['AdaptiveIpnLog']['created']);?></td>
      <td><?php echo $this->Html->cText($adaptiveIpnLog['Ip']['ip']);?></td>
      <td><?php echo $this->Html->cText($adaptiveIpnLog['AdaptiveIpnLog']['post_variable']);?></td>
    </tr>
<?php
		endforeach;
	else:
?>
    <tr>
      <td colspan="6" class="notice"><?php echo sprintf(__l('No %s available'), __l('Adaptive Ipn Logs'));?></td>
    </tr>
    <?php
endif;
?>
  </table>
<?php
	if (!empty($adaptiveIpnLogs)) {
		echo '<div class="js-pagination clearfix">';
		echo $this->element('paging_links');
		echo '</div>';
	}
?>
</div>