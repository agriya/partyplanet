<?php /* SVN: $Id: $ */ ?>
	<div id="breadcrumb">
			<?php $this->Html->addCrumb(__l('News'), array('controller' => 'articles', 'action' => 'index')); ?>
			<?php $this->Html->addCrumb($this->Html->cText($article['Article']['title'],false)); ?>
			<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
		</div>
<div class="js-tabs clearfix review-tabs-block">
			<ul class="clearfix">
				<li><?php echo $this->Html->link(__l('News'), '#tabs-1');?></li>
				<li><?php echo $this->Html->link(__l('Comments'), '#comments');?></li>
			</ul>
	<div id="tabs-1">
<div class="articles view phots-view-block">
<h2><?php echo  $this->Html->cText($article['Article']['title'],false);?></h2>
    	<div class="form-content-block">
    		<div class="photos-center-block">
        		<?php
        			echo $this->Html->showImage('Article', $article['Attachment'], array('dimension' => 'view_page_big_thumb','title'=>$this->Html->cText($article['Article']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($article['Article']['title'], false))));
        		?>
    		</div>
    		<div class="article-description">
        	   <?php echo $this->Html->cHtml($article['Article']['description']);?>
        	</div>
            <div class="clearfix">
        		<h3 class="tags grid_left"><?php echo __l('Tags');?></h3>
        		<ul class="tags grid_left clearfix">
        			<?php
        					if (!empty($article['ArticleTag'])) :
        					foreach($article['ArticleTag'] As $article_tag) :
        			?>
        				<li><?php echo $this->Html->link($this->Html->cText($article_tag['name']), array('controller' => 'articles', 'action' => 'index', 'tag' => $article_tag['slug']), array('escape' => false));?></li>
        			<?php
        					endforeach;
        				else :
        			?>
        				<li><p class="notice"><?php echo __l('No tags added');?></p></li>
        			<?php
        				endif;
        			?>
        		</ul>
    		</div>
    	</div>
	
  </div>
    </div>
  	<div id="tabs-2">
 				<?php
			if(!$this->Auth->sessionValid()): ?>
		  <div class="event-link"> <?php	echo __l("Please ") . $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('class'=>'login','title'=>__l('Login'),'escape' => false)) . __l(" to your account to add comments") ; ?> </div>
		<?php else: ?>
		
				<?php echo $this->element('../article_comments/add'); ?>
			<?php endif;
		?>
	
		  <?php  echo $this->element('article-comments-index', array('article_id' => $article['Article']['id'], 'cache' => array('key' => $article['Article']['id'], 'config' => '2sec')));?>

			
</div>
	<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
			<div class="admin-tabs-block form-content-block">
				<div class="js-tabs">
				<ul class="clearfix menu-tabs">
				  <li><?php echo $this->Html->link(__l('News Reviews'), array('controller' => 'article_comments', 'action' => 'index','article_comment' => $article['Article']['slug'], 'admin' => true), array('title' => __l('News Reviews'), 'escape' => false)); ?></li>
				 </ul>
				</div>
			</div>
		<?php endif; ?>

</div>