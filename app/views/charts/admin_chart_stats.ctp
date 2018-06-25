<div class="js-cache-load js-cache-load-admin-charts {'data_url':'admin/charts/chart_transactions/is_ajax_load:1', 'data_load':'js-loadadmin-chart-transactions-ctp'}">
<?php echo $this->element('chart-admin_chart_transactions'); ?>
</div>
<?php echo $this->element('chart-admin_chart_users', array('user_type_id'=> ConstUserTypes::User)); ?>
<?php echo $this->element('chart-admin_chart_user_logins', array('user_type_id'=> ConstUserTypes::User)); ?>
<?php echo $this->element('chart-admin_chart_venues'); ?>
<?php echo $this->element('chart-admin_chart_events'); ?>
<?php echo $this->element('chart-admin_chart_modules'); ?>



