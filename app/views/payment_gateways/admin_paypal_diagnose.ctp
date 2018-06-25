<div class="paymentGateways form space">
<?php
$test_mode = $paymentGatewaySettings[0]['PaymentGateway']['is_test_mode'];
?>
<?php
$paymentGatewayName = "";
foreach($paymentGatewaySettings as $paymentGatewaySetting) {
	$gatewayid = array();
	if (!in_array($paymentGatewaySetting['PaymentGateway']['id'],$gatewayid)) {
		$gatewayid[] = $paymentGatewaySetting['PaymentGateway']['id'];
	}
	$test_mode = $paymentGatewaySetting['PaymentGateway']['is_test_mode'];

	if($paymentGatewayName != $paymentGatewaySetting['PaymentGateway']['name']) {
?>
<h3><?php echo __l('Diagnose '); ?><?php echo $this->Html->link( $paymentGatewaySetting['PaymentGateway']['display_name'].__l(' settings'), array('action'=>'edit', $gatewayid[0]), array('class' => 'js-edit ','escape'=>false, 'title' => __l('PayPal settings')));?> </h3>
<table class="list diagonse">
  <tbody>
    <tr>
      <th><?php echo  __l('Settings');?></th>
      <th><?php echo  __l('Live Values');?> </th>
    </tr>
<?php foreach($paymentGatewaySettings as $paymentsetting) {
if($paymentsetting['PaymentGatewaySetting']['payment_gateway_id'] == $gatewayid[0]){?>
    <tr>
      <td><?php echo Inflector::humanize($paymentsetting['PaymentGatewaySetting']['key']); ?></td>
      <td><div class="offset1 span5 hor-space pull-left">
<?php $current_value = $paymentsetting['PaymentGatewaySetting']['live_mode_value']; ?>
          <span class="label label-<?php echo ($current_value) ? 'success' : 'warning'; ?>"><?php echo ($current_value) ? __l($current_value) : __l('No Value'); ?></span>
<?php if(!empty($paymentsetting['PaymentGatewaySetting']['key']) && Inflector::humanize($paymentsetting['PaymentGatewaySetting']['key']) == 'Payee Account') {?>
          <div>
            <p class="white-bg space"><?php echo __l('Your PayPal account has to be in "verified" status.');?> <a target="_blank" href="https://www.paypal.com/verified/pal=<?php echo $current_value; ?>"><?php echo __l('Check');?></a> <?php echo __l('your status in PayPal.');?></p>
          </div>
<?php }?>
        </div></td>
    </tr>
<?php } ?>
<?php }?>
    <tr>
      <td><?php echo  __l('Live Mode?');?></td>
      <td><div class="offset1 span5 hor-space pull-left"> <span class="label label-<?php  echo ($test_mode) ? 'warning' : 'success'; ?>"><?php echo ($test_mode) ? __l('No') : __l('Yes'); ?></span> </div></td>
    </tr>
  </tbody>
</table>
<div class="info-details"><?php echo __l('Note: Site\'s PayPal account should not be used for any users account.'); ?></div>
<div class="clearfix cancel-block compose-button">
<?php
								echo $this->Html->link('<i class="icon-cog"></i><span class="space">'.__l('Test API Setting').'</span>', array('action' => 'admin_paypal_diagnose/verify', $gatewayid[0]), array('escape' => false, 'title' => __l('Test API Setting'), 'class' => 'btn'));
								?>
  <span class="js-tooltip space" data-original-title="On clicking this, will try to transfer 0.01 USD to same admin to admin account. This will verify whether your PayPal API credentials are properly configured"><i class="icon-question-sign"></i></span> </div>
<?php  $paymentGatewayName = $paymentGatewaySetting['PaymentGateway']['name'];
}
?>
<?php } ?>