<div class="mail-search">
    <div class="form-content-block">
   	<?php echo $this->Form->create('Message', array('action'=>'search', 'class' => 'mail-search normal')); ?>
	<div id = "simple-search">
		<div class="search-input-block">
			<?php echo $this->Form->input('search', array('label' => '','div' => false)); ?>
		</div>
		<div class="advanced-search-input-block">
            <div class="submit-block clearfix">
            	<?php echo $this->Form->submit('search-mail-button.png', array('div' => false, 'name' => 'data[Message][quick_search]'));?>
            </div>
            <span class="advance_search_show js-show-search"><?php echo __l('Show search options'); ?></span>
        </div>
        <div class="clear"></div>
    </div>
	<div  id = "advanced-search">
        <div class="search-form-left"></div>
        <div class="search-form-center"></div>
		<div class="search-form-right"></div>
        <div class="search-form-block">
        	<div id="search-form-block">
            <span class="advance_search_hide js-hide-search"><?php echo __l('Hide search options'); ?>
                <em class="clear"></em></span>
                <div class="clear"></div>
            	<div>
                	<div class="search-left-block">
                            <?php echo $this->Form->input('from', array('label' => 'From')); ?>
                            <?php echo $this->Form->input('to', array('label' => 'To' )); ?>
                          <?php
							echo $this->Form->input('subject', array('label' => 'Subject'));
							$search_list = array(	'All Mail' => 'All Mail',
													'Inbox' => 'Inbox',
													'Starred' => 'Starred',
													'Sent Mail' => 'Sent Mail',
													'Drafts' => 'Drafts',
													'Spam' => 'Spam',
													'Trash' => 'Trash',
													'Mail & Spam & Trash' => 'Mail & Spam & Trash',
													'Read Mail' => 'Read Mail',
													'Unread Mail' => 'Unread Mail'
												);
							echo $this->Form->input('search_by', array('label' => 'Search', 'options' => $search_list));
						?>
                    </div>
                    <div class="search-right-block">
                       	<?php
					   		echo $this->Form->input('has_the_words', array('label' => 'Has the words'));
                        	echo $this->Form->input('doesnt_have', array('label' => 'Doesn\'t have'));
						?>
                        <div class="search-check-box">
                        	<?php echo $this->Form->input('has_attachment', array('type' => 'checkbox' , 'value' => '1' , 'label' => 'Has attachment','div' => false)); ?>
                        </div>
                        <div class="from-to-block">
                            <?php echo $this->Form->input('from_date',array('type'=>'text','readonly'=>'readonly','class'=>'js-datepicker','label'=>__l('From'),'div' => false)); ?>
							<?php echo $this->Form->input('to_date',array('type'=>'text','readonly'=>'readonly','class'=>'js-datepicker','label'=>__l('To'),'div' => false)); ?>
                        </div>
					</div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
           </div>
        <div class="clear"></div>
        <div class="search-form-bottom-left"></div>
        <div class="search-form-bottom-center">
            <div class="submit-block clearfix">
    			<?php echo $this->Form->submit('search-mail-button.png',array('class' => 'search', 'name' => 'data[Message][advanced_search]'));?>
            </div>
             <div class="clear"></div>
        </div>
        <div class="search-form-bottom-right"></div>

	</div>
	 <?php echo $orm->end();?>
	 </div>
</div>