<?php
if (!empty($videos)) {
    foreach($videos as $video) {
        echo $this->Rss->item(array() , array(
            'title' => array(
                'value' => $video['Video']['title']
            ) ,
            'link' => array(
                'controller' => 'videos',
                'action' => 'v',
                'slug' => $video['Video']['slug'],
                'view_type' => ConstViewType::FullView
            ) ,
            'description' => array(
                'value' => '<p><a href="' . $this->Html->url(array(
                    'controller' => 'videos',
                    'action' => 'v',
                    'slug' => $video['Video']['slug'],
                    'view_type' => ConstViewType::FullView
                ) , true) . '">'.$this->Html->Image($video['YoutubeThumbnailUrl']['thumbnail_url'], array('width' => '75', 'height' => '75', 'title' => $video['Video']['title'], 'alt' => sprintf(__l('[Image: %s]'), $video['Video']['title']))).'</a></p><p>' . $this->Html->cHtml($video['Video']['description']) . '</p>',
                'cdata' => true,
                'convertEntities' => false
            ) ,
            'createdDate' => $this->Html->cDateTime($video['Video']['created'], false) ,
        ));
    }
}
?>