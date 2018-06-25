<?php
if (!empty($articles)) :
    foreach($articles as $article) :
        echo $this->Rss->item(array() , array(
            'title' => $this->Html->cText($article['Article']['title'],false),
            'link' => array(
                'controller' => 'articles',
                'action' => 'view',
                $article['Article']['slug']
            ) ,
            'description' => '<![CDATA[ <p>' . $this->Html->cHtml($this->Html->truncate($article['Article']['description'])) . '</p> ]]>',
            'pubDate' => '<pubDate>' . $this->Html->cDateTime($article['Article']['created'], false) . '</pubDate>',
        ));
    endforeach;
endif;
?>