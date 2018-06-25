<div class="clearfix">
	<div class="grid_18 js-admin-stats-block dashboard-side1 omega alpha">
	<?php echo $this->element('admin-charts-stats'); ?>
    </div>
<div class="grid_6 dashboard-side2 omega alpha grid_right">
<div class="city-lang-block clearfix">
					<div class="clearfix language-form-block grid_right">
						<?php
							$languages = $this->Html->getLanguage();
							if(Configure::read('user.is_allow_user_to_switch_language') && !empty($languages)) :
								echo $this->Form->create('Language', array('action' => 'change_language', 'class' => 'normal'));
								echo $this->Form->input('language_id', array('label'=>__l('Language'),'class' => 'js-autosubmit', 'empty' => __l('Please Select'), 'options' => $languages, 'value' => isset($_COOKIE['CakeCookie']['user_language']) ?  $_COOKIE['CakeCookie']['user_language'] : Configure::read('site.language')));
								echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
						?>
						<div class="hide">
							<?php echo $this->Form->submit('Submit');  ?>
						</div>
						<?php
								echo $this->Form->end();
							endif;
						?>
					</div>
</div>



    <div class="dashboard-center-block">
    <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo __l('Timings'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block recent-user">
            <div class="admin-dashboard-links">
                <p>
                	<?php $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime('now')) . ' ' . Configure::read('site.timezone_offset') . '"'; ?>
                    <?php echo __l('Current time: '); ?><span <?php echo $title; ?>><?php echo strftime(Configure::read('site.datetime.format')); ?></span>
                </p>
                <p>
                    <?php echo __l('Last login: '); ?><?php echo $this->Html->cDateTimeHighlight($this->Auth->user('last_logged_in_time')); ?>
                </p>
            </div>
		</div>
	</div>
 <div class="js-cache-load js-cache-load-recent-users {'data_url':'admin/users/recent_users', 'data_load':'js-cache-load-recent-users'}">
			<?php echo $this->element('users-admin_recent_users', array('cache' => array('config' => 'site_element_cache_5_hours'))); ?>
        </div>
        <div class="js-cache-load js-cache-load-online-users {'data_url':'admin/users/online_users', 'data_load':'js-cache-load-online-users'}">
        	<?php echo $this->element('users-admin_online_users', array('cache' => array('config' => 'site_element_cache_5_hours'))); ?>
        </div>
	       <div class="dashboard-center-block">
    <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
              <div class="admin-dashboard-links">
                <h4 class="version-info">
                    <?php echo __l('Version').' ' ?>
					<span>
					<?php echo Configure::read('site.version'); ?>
					</span>
                </h4>
                <p>
                    <?php echo $this->Html->link(__l('Product Support'), 'http://customers.agriya.com/', array('target' => '_blank', 'title' => __l('Product Support'))); ?>
                </p>
                <p>
                    <?php echo $this->Html->link(__l('Product Manual'), 'http://dev1products.dev.agriya.com/doku.php?id=partyplanet' ,array('target' => '_blank','title' => __l('Product Manual'))); ?>
                </p>
                <p>
                    <?php echo $this->Html->link(__l('CSSilize'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => __l('CSSilize'))); ?>
					<small><?php echo __l("PSD to XHTML Conversion and ") . Configure::read('site.name') .__l(" theming");?></small>
                </p>
                <p>
                    <?php echo $this->Html->link(__l('Agriya Blog'), 'http://blogs.agriya.com/' ,array('target' => '_blank','title' => __l('Agriya Blog'))); ?>
					<small>Follow Agriya news</small>
                </p>
            </div>
		</div>
	</div>
	</div>
</div>