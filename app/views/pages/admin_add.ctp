<?php /* SVN: $Id: admin_add.ctp 1020 2009-10-06 16:00:36Z boopathi_026ac09 $ */ ?>
<div id="add">
    <?php echo $this->element('js_tiny_mce_setting', array('cache' => array('config' => 'sec')));?>
     <div class="pages form form-content-block">
        <?php echo $this->Form->create('Page', array('class' => 'normal'));?>
        <fieldset>
     		<legend class="crumb"><?php echo $this->Html->link(__l('Pages'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Page');?></legend>
            <?php
                echo $this->Form->input('title', array('between' => '', 'label' => 'Page title'));
                echo $this->Form->input('content', array('type' => 'textarea', 'class' => 'js-editor', 'label' => 'Body'));
                echo $this->Form->input('slug');
                ?>
                	<div class="submit-block clearfix">
                <?php
                echo $this->Form->submit('Add', array('name' => 'data[Page][Add]'));
                ?>
                </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>