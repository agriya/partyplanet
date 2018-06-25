<?php /* SVN: $Id: $ */ ?>
<div id="breadcrumb">
	<?php echo $this->Html->addCrumb(__l('User')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<h2><?php echo __l('User Profile - ').$user['User']['username']?$user['User']['username']:$this->request->data['User']['username']; ?></h2>
<div class="add-block1 clearfix">
<?php
	 if($this->Auth->user('username')!=$user['User']['username'] && Configure::read('friend.is_enabled') && $user['User']['user_type_id'] != ConstUserTypes::Admin):
	    echo $this->Html->link(__l('Send a Message') , array('controller' => 'messages', 'action' => 'compose', 'user' => $user['User']['username']) , array('class' => 'send-message','title'=>__l('Send a Message')));
		if (!empty($friend)):
			if ($friend['UserFriend']['friend_status_id'] == ConstUserFriendStatus::Pending):
				$is_requested = ($friend['UserFriend']['is_requested']) ? 'sent' : 'received';
				echo $this->Html->link(__l('Friend Request is Pending'), array('controller' => 'user_friends', 'action' => 'remove', $user['User']['username'], $is_requested), array('class' => 'user-pending js-friend', 'title' => __l('Click to remove from friend\'s list')));
			else:
				$is_requested = ($friend['UserFriend']['is_requested']) ? 'sent' : 'received';
				echo $this->Html->link(__l('Remove Friend'), array('controller' => 'user_friends', 'action' => 'remove', $user['User']['username'], $is_requested), array('class' => 'js-delete remove-user delete js-add-friend', 'title' => __l('Click to remove from friend\'s list')));
			endif;
		elseif($this->Auth->user('id')):
         ?> 
		   <?php echo $this->Html->link(__l('Add as Friend'), array('controller' => 'user_friends', 'action' => 'add', $user['User']['username']), array('class' => 'add add-friend', 'title' => __l('Add as Friend')));
		endif;
	endif;
	?>
 </div>
<div class="users view">
	<?php
     if (!empty($user)): ?>
     <div class="clearfix">
		<div class="clearfix user-image-block grid_left omega alpha">
				<?php
					 echo $this->Html->getUserAvatar($user['User'], 'user_info_thumb');
				?>
			</div>
            <div class="grid-left grid_10 omega alpha">
    		<dl class="list clearfix">
				<?php if (!empty($user['User']['username'])): ?>
   				<dt><?php echo __l('Name:').' '; ?></dt>
				<dd>
				<?php echo $this->Html->link($this->Html->cText($user['User']['username'], false), array('controller' => 'users', 'action' => 'view', $user['User']['username']), array('escape' => false)); ?>
                </dd>
				<?php endif; ?>
			<?php if (!empty($user['UserProfile']['dob'])): ?>
               <?php if ($user['UserProfile']['is_show_month_date']=='0'): ?>
					<dt><?php echo __l('Age:').' '; ?></dt>
				<dd>
					<?php echo $this->Html->userAge($user['UserProfile']['dob']); ?>
				</dd>
                  <?php endif; ?>
      		  		<dt><?php echo __l('Date of Birth:').' '; ?></dt>
    		  		<dd>
    		  	   		<?php echo $this->Html->userDob($user['UserProfile']['dob'], $user['UserProfile']['is_show_month_date']); ?>
                    </dd>
                 <?php endif; ?>
				<?php if (!empty($user['UserProfile']['City']['name'])): ?>
                    <dt><?php echo __l('City:').' '; ?></dt>
                    <dd>
					<?php echo $this->Html->cText($user['UserProfile']['City']['name']); ?>
					</dd>
                <?php endif; ?>
			      <dt><?php echo __l('Joined:').' '; ?></dt>
			      <dd>
                <?php echo $this->Html->userDob($user['User']['created'],'0'); ?>
                </dd>
             
				
			</dl>
		
			</div>
		</div>
		<div class="clearfix">
		
           
			 	<?php if (!empty($user['UserProfile']['about_me'])): ?>
            	 <div class="user-description-block">
            		<h3><?php echo __l('About me:').' '; ?></h3>
                	<?php echo $this->Html->cText($user['UserProfile']['about_me']); ?>
                 </div>
               	<?php endif; ?>
             
			<?php
	        if(!empty($user['User']['username']) && $user['User']['username']== $this->Auth->user('username')):?>
	        <ul class="userprofile-link">
	             <li>
        			<?php echo $this->Html->link(__l('Change Photo'), array('controller' => 'user_profiles', 'action' => 'edit',$user['User']['id'],'basic'), array('title'=>__l('Change Photo'),'escape' => false));?>
                </li>
                <li>
            	   <?php echo $this->Html->link(__l('Edit Profile'), array('controller' => 'user_profiles', 'action' => 'edit',$user['User']['id'],'basic'), array('title'=>__l('Edit Profile'),'escape' => false));?>
                </li>
            </ul>
			<?php endif; ?>
			</div>
 	<?php endif; ?>

<div class="js-tabs clearfix review-tabs-block user-menu-list form-content-block">
    <ul class="clearfix">
    	<li><?php echo $this->Html->link(__l('Events'), '#my-events');?></li>
		<li><?php echo $this->Html->link(__l('Photos'), '#favorite-events');?></li>
		<li><?php echo $this->Html->link(__l('Where i party'), '#my-venues');?></li>
		<li><?php echo $this->Html->link(__l('Favorite photos'), '#favorite-photos');?></li>
		<li><?php echo $this->Html->link(__l('Favorite videos'), '#favorite-videos');?></li>
		<li><?php echo $this->Html->link(__l('Reviews'), '#reviews');?></li>
    </ul>
    	<div id="my-events"><?php echo $this->element('my_events', array('user_id' => $user['User']['id'],'cache' => array('key' => $user['User']['id'], 'config' => 'sec'))); ?></div>
		<div id="favorite-events"><?php echo $this->element('photo_albums-index', array('username' => $user['User']['username'],'cache' => array('key' => $user['User']['id'],'config' => 'sec'))); ?></div>
		<div id="my-venues"><?php echo $this->element('joined_venues', array('user_id' => $user['User']['id'],'cache' => array('key' => $user['User']['id'], 'config' => 'sec'))); ?></div>
		<div id="favorite-photos"><?php echo $this->element('favorite-photos', array('username' => $user['User']['username'],'cache' => array('key' => $user['User']['username'], 'config' => 'sec'))); ?></div>
		<div id="favorite-videos"><?php echo $this->element('favorite-videos', array('username' => $user['User']['username'],'cache' => array('key' => $user['User']['username'], 'config' => 'sec'))); ?></div>
		<div id="reviews"></div>
		
	</div>
<?php
   if($this->Auth->user('id') and $this->Auth->user('id')!= $user['User']['id']): ?>
	<div class="form-content-block">
	 <h2><?php echo __l('Leave a note on my pad');?> </h2>
    	 <div class="comment-1">
    		<?php if(!$this->Auth->sessionValid()): ?>
    			<p><?php echo Configure::read('site.name'); ?><?php echo __l(' members: please'). ' '; ?><?php echo $this->Html->link(__l('log in'), array('controller' => 'users', 'action' => 'login'), array('class' => 'login', 'title' => __l('Login'), 'escape' => false)); ?></p>
    			<p><?php echo __l('Guests: Please ') . $this->Html->link(__l('create an account'), array('controller' => 'users', 'action' => 'joinus'), array('class' => 'login', 'title' => __l('create an account'), 'escape' => false)); ?><?php echo __l(' or enter your review below.'); ?></p>
            	<?php
            	else:
            		echo $this->element('../user_comments/add');
            	endif;
            	?>
    	</div>
	</div>
	<?php endif;?>
	<div class="form-content-block">
		<?php echo $this->element('user-comment-index', array('user_id' => $user['User']['id'],'username'=>$this->request->params['pass'][0],'cache' => array('key' => $user['User']['id'], 'config' => 'sec'))); ?>
    </div>
    <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
    <div class="admin-tabs-block form-content-block">
          <div class="js-tabs">
             <ul class="clearfix menu-tabs">
        		<li><?php echo $this->Html->link(__l('User Comments'), array('controller' => 'user_comments', 'action' => 'index','user_comment' => $user['User']['username'], 'admin' => true), array('title' => __l('User Comments'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Venue Comments'), array('controller' => 'venue_comments', 'action' => 'index','user_venue_comment' => $user['User']['username'], 'admin' => true), array('title' => __l('User Venue Comments'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Event Comments'), array('controller' => 'event_comments', 'action' => 'index','user_event_comment' => $user['User']['username'], 'admin' => true), array('title' => __l('User Event Comments'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Video Comments'), array('controller' => 'video_comments', 'action' => 'index','user_video_comment' => $user['User']['username'], 'admin' => true), array('title' => __l('User Video Comments'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Photo Comments'), array('controller' => 'photo_comments', 'action' => 'index','user_photo_comment' => $user['User']['username'], 'admin' => true), array('title' => __l('User Photo Comments'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index','username' => $user['User']['username'], 'admin' => true), array('title' => __l('User Photo Galleries'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Venue Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index','username' => $user['User']['username'],'venue' => 'venue', 'admin' => true), array('title' => __l('User Venue Photo Galleries'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('User Event Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index','username' => $user['User']['username'],'event' => 'event', 'admin' => true), array('title' => __l('User Event Photo Galleries'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('Venues'), array('controller' => 'venues', 'action' => 'index','username' => $user['User']['username'], 'admin' => true), array('title' => __l('Venues'), 'escape' => false)); ?></li>
        		<li><?php echo $this->Html->link(__l('Events'), array('controller' => 'events', 'action' => 'index','username' => $user['User']['username'], 'admin' => true), array('title' => __l('Events'), 'escape' => false)); ?></li>
             </ul>
          </div>
      </div>
	<?php endif; ?>
</div>
