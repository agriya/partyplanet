<?php /* SVN: $Id: paynow.ctp 1960 2010-05-21 14:46:46Z jayashree_028ac09 $ */ ?>
<div class="guestListUsers paynow">

<h2><?php echo __l('Pay Guest List User Fee');?></h2>
 <?php echo $this->Form->create('GuestListUser',array('action' => 'paynow'), array('class' => 'normal'));?>
 <?php echo $this->Form->input('GuestListUser.id',array('type'=>'hidden'));
    $total_amount = $guestListUser['GuestList']['Event']['ticket_fee'] * $guestListUser['GuestListUser']['in_party_count'];
 
 //print_r($paymentGateways);
 //exit;
 ?>
<div class="clearfix grid_9 omega alpha">
      <dl class="list event-list clearfix">
        <dt><?php echo __l('Event Name:')?></dt>
        <dd><?php echo $this->Html->cText($guestListUser['GuestList']['Event']['title'],false).__l(' @ ').$this->Html->cText($guestListUser['GuestList']['Event']['Venue']['name'],false); ?></dd>
        <dt><?php echo __l('Date:')?></dt>
        <dd>
            <?php
                echo $this->Html->cDate($guestListUser['GuestList']['Event']['start_date']);                
            ?>
        </dd>
        <dt><?php echo __l('Time:')?></dt>
        <dd>
            <?php
                if($guestListUser['GuestList']['Event']['is_all_day']==1):
                    echo __l('Whole Day');
                else:
                    echo $this->Html->cTime($guestListUser['GuestList']['Event']['start_time']).' - '. $this->Html->cTime($guestListUser['GuestList']['Event']['end_time']);
                 endif;
            ?>
        </dd>
        <dt><?php echo __l('Venue Name:')?></dt>
        
        <dd>
            <?php echo $this->Html->cText($guestListUser['GuestList']['Event']['Venue']['name'],false); ?>
        </dd>        
        <dt><?php echo __l('Guest List User Fee:')?></dt>
        <dd>
            <?php
                echo $this->Html->cInt($total_amount)           
            ?>
        </dd>
    </dl>
    <fieldset class="group-block round-5">
    <legend class="round-5"><?php echo __l('Payment Type');?></legend>
    <?php echo $this->Form->input('payment_type_id', array('legend' => false, 'type' => 'radio', 'options' => $paymentGateways['paymentTypes'], 'class' => 'js-payment-type'));?>
    <div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Pay')); ?>
    </div>
</fieldset>
</div>

<?php echo $this->Form->end();?>
</div>