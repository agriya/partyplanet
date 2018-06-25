<?php /* SVN: $Id: admin_index.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="userViews index js-response">
    <h2><?php echo $this->pageTitle;?></h2>
    <?php echo $this->Form->create('UserView' , array('type' => 'get', 'class' => 'normal filter-form clearfix','action' => 'index')); ?>
	<div class="filter-section clearfix">
		<div>
			<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
		</div>
		<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Search'));?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
    <?php echo $this->Form->create('UserView' , array('class' => 'normal clearfix','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <?php echo $this->element('paging_counter');?>
    <table class="list">
        <tr>
            <th><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>            
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed By'), 'ViewingUser.username');?></div></th>
			<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed Time'),'created');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort('ip');?></div></th>
        </tr>
        <?php
        if (!empty($userViews)):
            $i = 0;
            foreach ($userViews as $userView):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td><?php echo $this->Form->input('UserView.'.$userView['UserView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userView['UserView']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
                    <td class="actions"><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userView['UserView']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>                    
                    <td><?php echo $this->Html->link($this->Html->cText($userView['User']['username']), array('controller'=> 'users', 'action'=>'view', $userView['User']['username'], 'admin' => false), array('escape' => false,'title' => $this->Html->cText($userView['User']['username']) ));?></td>
                    <td><?php echo !empty($userView['ViewingUser']['username']) ? $this->Html->link($this->Html->cText($userView['ViewingUser']['username']), array('controller'=> 'users', 'action'=>'view', $userView['ViewingUser']['username'], 'admin' => false), array('escape' => false,'title' => $this->Html->cText($userView['ViewingUser']['username'],false))) : __l('Guest');?></td>
					<td><?php echo $this->Html->cDateTimeHighlight($userView['UserView']['created']);?></td>
                    <td><?php echo $this->Html->link($this->Html->cText($userView['UserView']['ip'], false),'http://whois.sc/'.$userView['UserView']['ip'],array('target' => 'blank'));?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="7" class="notice"><?php echo __l('No User Views available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>

    <?php
    if (!empty($userViews)) :
        ?>
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
        </div>
        <div class="js-pagination">
            <?php echo $this->element('paging_links'); ?>
        </div>
        <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>