<data>
	<width><?php echo ConstFlashIntro::Width;?> </width>
	<height><?php echo ConstFlashIntro::Height;?></height>
	<displayIndex><?php echo ConstFlashIntro::displayIndex;?></displayIndex>
	<autoSwitch><?php echo ConstFlashIntro::autoSwitch;?></autoSwitch>
	<pauseAutoSwitchOnMouseOver><?php echo ConstFlashIntro::pauseAutoSwitchOnMouseOver;?></pauseAutoSwitchOnMouseOver>
	<delayTime><?php echo ConstFlashIntro::delayTime;?></delayTime>
	<menuItemBorderSize><?php echo ConstFlashIntro::menuItemBorderSize;?></menuItemBorderSize>
	<menuItemBorderColor><?php echo ConstFlashIntro::menuItemBorderColor;?></menuItemBorderColor>
	<menuItemBorderAlpha><?php echo ConstFlashIntro::menuItemBorderAlpha;?></menuItemBorderAlpha>
	<menuItemSpace><?php echo ConstFlashIntro::menuItemSpace;?></menuItemSpace>
	<menuItemWidth><?php echo ConstFlashIntro::menuItemWidth;?></menuItemWidth>
	<menuItemHeight><?php echo ConstFlashIntro::menuItemHeight;?></menuItemHeight>
	<menuPosition><?php echo ConstFlashIntro::menuPosition;?></menuPosition>
	<menuScrollSpeed><?php echo ConstFlashIntro::menuScrollSpeed;?></menuScrollSpeed>
	<bgColor><?php echo ConstFlashIntro::bgColor;?></bgColor>
	<bgAlpha><?php echo ConstFlashIntro::bgAlpha;?></bgAlpha>
	<textPad><?php echo ConstFlashIntro::textPad;?></textPad>
	<textSpace><?php echo ConstFlashIntro::textSpace;?></textSpace>
	<?php

    foreach($articles as $article)
    {
     if(count($article['Attachment'])){
    ?>
    <item>
		<img> <?php echo $this->Html->url($this->Html->getImageUrl('Article',$article['Attachment'],array('dimension' => 'player_big_thumb','type'=>'jpg')));?></img>
		<thumb>
       <?php echo $this->Html->url($this->Html->getImageUrl('Article',$article['Attachment'],array('dimension' => 'normalhigh_thumb','type'=>'jpg')));?>
        </thumb>
		<imgLink><?php echo Router::url(array('controller' => 'articles','action' => 'view',$article['Article']['slug']) , true);?></imgLink>
		<imgLinkTarget><?php echo ConstFlashIntro::imgLinkTarget;?></imgLinkTarget>
		<textPosition><?php echo ConstFlashIntro::textPosition;?></textPosition>
		<text><![CDATA[<?php echo $this->Html->link($this->Html->cText($article['Article']['title'],false) , array('controller' => 'articles','action' => 'view',$article['Article']['slug']) , array('title' => $this->Html->cText($article['Article']['title'],false) ,'escape' => false)); ?>]]></text>
		
	</item>
    <?php
    }}
 	?>
</data>