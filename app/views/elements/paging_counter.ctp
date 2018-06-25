<p class="paging-counter">
<?php
echo $this->Paginator->counter(array(
'format' => __l('Results %start% - %end% of about %count%')
));
?></p>
