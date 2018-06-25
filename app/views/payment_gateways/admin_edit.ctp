<?php /* SVN: $Id: $ */ ?>

<div class="paymentGateways form"> <?php echo $this->Form->create('PaymentGateway', array('class' => 'normal'));?>
  <fieldset>
<?php
			echo $this->Form->input('id');
			echo $this->Form->input('name', array('type' => 'hidden', 'value' => $this->request->data['PaymentGateway']['name']));
			echo $this->Form->input('display_name', array('readonly' => 'readonly', 'label' => __l('Name')));
			echo $this->Form->input('is_test_mode', array('label' => __l('Test Mode?')));
			foreach($paymentGatewaySettings as $paymentGatewaySetting) {
				$options['type'] = $paymentGatewaySetting['PaymentGatewaySetting']['type'];
				if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_contest_listing'):
					$options['label'] = __l('Enable for Contest listing');
				elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_signup'):
					$options['label'] = __l('Enable for Signup');
				elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_add_to_wallet'):
					$options['label'] = __l('Enable for add to wallet');
				endif;
				$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['test_mode_value'];
				$options['div'] = array('id' => "setting-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
				if($options['type'] == 'checkbox' && !empty($options['value'])):
					$options['checked'] = 'checked';
				else:
					$options['checked'] = '';
				endif;
				if($options['type'] == 'select'):
					$selectOptions = explode(',', $paymentGatewaySetting['PaymentGatewaySetting']['options']);
					$paymentGatewaySetting['PaymentGatewaySetting']['options'] = array();
					if(!empty($selectOptions)):
						foreach($selectOptions as $key => $value):
							if(!empty($value)):
								$paymentGatewaySetting['PaymentGatewaySetting']['options'][trim($value)] = trim($value);
							endif;
						endforeach;
					endif;
					$options['options'] = $paymentGatewaySetting['PaymentGatewaySetting']['options'];
				endif;
				if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description']) && empty($options['after'])):
					$options['help'] = "{$paymentGatewaySetting['PaymentGatewaySetting']['description']}";
				else:
					$options['help'] = '';
				endif;
				if ($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_contest_listing' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_signup' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_add_to_wallet'):
					echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options);
				endif;
			}
?>
  <div class="clearfix test-mode-heading">
    <div class="test-mode-left">
      <label><?php echo __l('Test Mode'); ?></label>
    </div>
    <div class="test-mode-right">
      <label><?php echo __l('Live Mode'); ?></label>
    </div>
  </div>
<?php
			$j = $z = $k = 0;
			foreach($paymentGatewaySettings as $paymentGatewaySetting) {
				$options['type'] = $paymentGatewaySetting['PaymentGatewaySetting']['type'];
				$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['test_mode_value'];
				$options['div'] = array('id' => "setting-test-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
				if($options['type'] == 'checkbox' && $options['value']):
					$options['checked'] = 'checked';
				endif;
				if($options['type'] == 'select'):
					$selectOptions = explode(',', $paymentGatewaySetting['PaymentGatewaySetting']['options']);
					$paymentGatewaySetting['PaymentGatewaySetting']['options'] = array();
					if(!empty($selectOptions)):
						foreach($selectOptions as $key => $value):
							if(!empty($value)):
								$paymentGatewaySetting['PaymentGatewaySetting']['options'][trim($value)] = trim($value);
							endif;
						endforeach;
					endif;
					$options['options'] = $paymentGatewaySetting['PaymentGatewaySetting']['options'];
				endif;
				$options['label'] = false;
				if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description']) && empty($options['after'])):
					$options['help'] = "{$paymentGatewaySetting['PaymentGatewaySetting']['description']}";
				else:
					$options['help'] = '';
				endif;
?>
<?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'payee_account' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'receiver_emails'): ?>
<?php if($z == 0):?>
  <fieldset class="form-block round-5">
  <h3><?php echo __l('Payee Details'); ?></h3>
<?php endif; ?>
  <div class="clearfix test-mode-content"> <span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
    <div class="test-mode-left">
<?php
	echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options);
?>
    </div>
    <div class="test-mode-right">
<?php
	$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
	$options['div'] = array('id' => "setting-live-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
	echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
?>
    </div>
  </div>
  <?php if ($z == 1): ?>
  </fieldset>
  <?php endif;?>
  <?php $z++;?>
  <?php endif; ?>
  <?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_AppID' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_Signature' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_Password' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_UserName'):?>
<?php if($k == 0):?>
  <fieldset class="form-block1 round-5">
  <h3><?php echo __l('Adaptive Payment Details'); ?></h3>
  <div class="info-details">
    <p><?php echo __l('Adaptive used to send money to host.');?></p>
    <p><?php echo __l('Create Adaptive API from PayPal profile. Refer').' ';?><a href='https://www.paypal.com/in/cgi-bin/webscr'>https://www.paypal.com/in/cgi-bin/webscr</a></p>
  </div>
<?php endif;?>
  <div class="clearfix test-mode-content"> <span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
    <div class="test-mode-left"><?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?></div>
    <div class="test-mode-right">
<?php
	$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
	$options['div'] = array('id' => "setting-live-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
	echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
?>
    </div>
  </div>
<?php if($k == 3): ?>
  </fieldset>
<?php endif;?>
<?php $k++;?>
<?php endif;?>
<?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'MRB_ID' ):?>
  <fieldset class="form-block1 round-5">
  <h3><?php echo __l('Merchant Referral Bonus ID'); ?></h3>
  <div class="info-details">
    <p><?php echo __l('Copy your ID, which is at the end of the Referral Email URL: ');?></p>
    <p><a href='https://www.paypal.com/in/cgi-bin/webscr'>https://www.paypal.com/cgi-bin/webscr?cmd=_web-referrals-mrb</a></p>
  </div>
  <div class="clearfix test-mode-content"> <span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
    <div class="test-mode-left"> <?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?> </div>
    <div class="test-mode-right">
      <?php
								$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
								$options['div'] = array('id' => "setting-live-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
								echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
							?>
    </div>
  </div>
  </fieldset>
<?php endif;?>
<?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'masspay_API_UserName' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'masspay_API_Password' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'masspay_API_Signature'):?>
<?php if($j == 0):?>
  <fieldset class="form-block1 round-5">
  <h3><?php echo __l('Mass Payment Details'); ?></h3>
  <div class="info-details">
    <p><?php echo __l('Masspay used to send money to user.');?></p>
    <p><?php echo __l('Create masspay API from PayPal profile. Refer').' ';?><a href='https://www.paypal.com/in/cgi-bin/webscr'>https://www.paypal.com/in/cgi-bin/webscr</a></p>
  </div>
<?php endif;?>
  <div class="clearfix test-mode-content"> <span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
    <div class="test-mode-left"> <?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?> </div>
    <div class="test-mode-right">
<?php
								$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
								$options['div'] = array('id' => "setting-live-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
								echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
							?>
    </div>
  </div>
<?php if($j == 2):?>
  </fieldset>
<?php endif;?>
<?php $j++;?>
<?php endif;?>
<?php if ($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'authorize_net_api_key' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'authorize_net_trans_key'): ?>
  <div class="clearfix test-mode-content"> <span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
    <div class="test-mode-left"> <?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?> </div>
    <div class="test-mode-right">
<?php
	$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
	$options['div'] = array('id' => "setting-live-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
	echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
?>
    </div>
  </div>
<?php endif;?>
  <?php if ($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'pagseguro_payee_account' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'pagseguro_token'): ?>
  <div class="clearfix test-mode-content"> <span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
    <div class="test-mode-left"> <?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?> </div>
    <div class="test-mode-right">
<?php
	$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
	$options['div'] = array('id' => "setting-live-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
	echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
?>
    </div>
  </div>
<?php endif;?>
<?php
		}
?>
  </fieldset>
  <div class="submit-block clearfix"> <?php echo $this->Form->submit(__l('Update'));?> </div>
  <?php echo $this->Form->end();?> </div>