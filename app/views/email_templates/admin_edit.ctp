<div class="js-response">
<h2 class="admin-title"><?php echo $this->Html->cText($this->request->data['EmailTemplate']['name'], false); ?></h2>
<div class="form-content-block">
<?php
	echo $this->Form->create('EmailTemplate', array('id' => 'EmailTemplateAdminEditForm'.$this->request->data['EmailTemplate']['id'], 'class' => 'normal js-insert js-ajax-form', 'action' => 'edit'));
	echo $this->Form->input('id');
	echo $this->Form->input('from', array('id' => 'EmailTemplateFrom'.$this->request->data['EmailTemplate']['id'], 'info' => __l('(e.g. "displayname &lt;email address>")')));
	echo $this->Form->input('reply_to', array('id' => 'EmailTemplateReplyTo'.$this->request->data['EmailTemplate']['id'], 'info' => __l('(e.g. "displayname &lt;email address>")')));
	echo $this->Form->input('subject', array('class' => 'js-email-subject', 'id' => 'EmailTemplateSubject'.$this->request->data['EmailTemplate']['id']));
?>
<span class="email-template"><?php echo __l('Email Type');?></span>
<?php
	 echo $this->Form->input('is_html', array('label' => __l('Is Html'),'type' => 'radio', 'legend' =>false, 'options' => array('0' => 'text', '1' => 'html')));
	echo $this->Form->input('email_content', array('class' => 'js-email-content', 'id' => 'EmailTemplateEmailContent'.$this->request->data['EmailTemplate']['id'])); ?>
	<div class="submit-block clearfix">
    	<?php echo $this->Form->end(__l('Update')); ?>
    </div>
</div>
</div>