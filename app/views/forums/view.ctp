<?php /* SVN: $Id: $ */ ?>
<div id="breadcrumb">
	<?php echo $this->Html->addCrumb(__l('Forums'), array('controller' => 'forum_categories', 'action' => 'index')); ?>
	<?php echo $this->Html->addCrumb($this->Html->cText($forum['ForumCategory']['title'], false), array('controller' => 'forums', 'action' => 'index', $forum['ForumCategory']['slug'])); ?>
	<?php echo $this->Html->addCrumb($this->Html->cText($this->pageTitle, false)); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<div class="forums view phots-view-block">
    <h2><?php echo $this->Html->cText($this->pageTitle,false); ?></h2>
   	<div class="form-content-block clearfix">
	 <h3><?php echo $this->Html->cText($forum['Forum']['title'],false);?></h3>
	<div class="clearfix" >
		   	<div class="grid_2 omega alpha">
    			<?php
    				echo $this->Html->getUserAvatar($forum['User'], 'micro_medium_thumb');
    				//echo $this->Html->link($this->Html->showImage('UserAvatar', $forum['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($forum['User']['username'], false)), 'title' => $this->Html->cText($forum['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $forum['User']['username'], 'admin' => false), array('escape' => false));?>
            </div>
            <div class="grid_14 omega alpha clearfix">
            <div class="clearfix">
            <div class="grid_left">
                <h3><?php echo $this->Html->link($this->Html->cText($forum['User']['username']), array('controller' => 'users', 'action' => 'view', $forum['User']['username'], 'admin' => false), array('escape' => false)); ?></h3>
    			<?php if(!empty($forum['User']['UserProfile']['Country']['name'])): ?>
    			<p>
    				<?php
    					echo $this->Html->image('flags/'.strtolower($forum['User']['UserProfile']['Country']['country_code']).'.gif', array('alt'=> sprintf(__l('[Image: %s]'),$this->Html->cText($forum['User']['UserProfile']['Country']['name'])), 'title' => $forum['User']['UserProfile']['Country']['name']));
    					echo $this->Html->cText($forum['User']['UserProfile']['Country']['name']);
    				?>
    				</p>
    			<?php endif;?>
    		</div>
    		<div class="grid_right">
        	   <p class="posted-date"> <?php echo sprintf(__l('posted %s'), $this->Html->cDateTime($forum['Forum']['created'])); ?></p>
            </div>
            </div>
             <div><?php echo $this->Html->cText($forum['Forum']['description'],false);?></div>
		</div>
		</div>


	</div>
    <div>
        <?php
             echo $this->element('forum_comments-index', array('cache' => array('config' => 'sec', 'key' => $forum['Forum']['id'])));
        ?>
    </div>
    <div>
    <?php
    	if($this->Auth->user('id')) :
          echo $this->element('../forum_comments/add', array('cache' => array('config' => 'sec', 'key' => $forum['Forum']['id'])));
        endif;
    ?>
    </div>
    <div>
        <?php
            if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
                ?>
                <div class="admin-tabs-block form-content-block">
                <div class="js-tabs">
                <ul class="clearfix menu-tabs">
                    <li><?php echo $this->Html->link(__l('Comments'), array('controller' => 'forum_comments', 'action' => 'index', 'forum' => $forum['Forum']['id'], 'admin' => true), array('title' => __l('Forum Comments'), 'escape' => false)); ?></li>
                    <li><?php echo $this->Html->link(__l('Views'), array('controller' => 'forum_views', 'action' => 'index', 'forum' => $forum['Forum']['id'], 'admin' => true), array('title' => __l('Forum Views'), 'escape' => false)); ?></li>
                </ul>
                </div>
                </div>
                <?php
            endif;
        ?>
    </div>
</div>
