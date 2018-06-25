<?php if(!empty($articles)):?>
	<ol class="list hot-list clearfix">
		<?php foreach($articles as $article){?>
			<li class="grid_4 alpha omega clearfix">
				<h3><?php echo $this->Html->link($this->Html->cText($article['Article']['title'],false), array('controller' => 'articles', 'action' => 'view',$article['Article']['slug']), array('class' => '', 'title'=>$this->Html->cText($article['Article']['title'],false),'escape' => false)); ?></h3>
				<?php echo $this->Html->cText($this->Html->truncate(($article['Article']['description']), 150),false);?>
			</li>
		<?php } ?>
	</ol>
<?php endif;?>