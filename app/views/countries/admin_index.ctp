<?php /* SVN: $Id: admin_index.ctp 13910 2010-07-16 14:34:46Z siva_063at09 $ */ ?>
<div class="countries index js-response">

    <div class="clearfix ">
    <div class="grid_left">
    <?php echo $this->element('paging_counter');?></div>
    <div class="add-event grid_right">
        	<?php
        	 echo $this->Html->link(__l('Add'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add')));?>
    	</div>
    <div class="grid_left">
    <?php  echo $this->Form->create('Country', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form','action'=>'index'));?>
         <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
           <?php echo $this->Form->submit(__l('Filter')); ?>
         <?php echo $this->Form->end(); ?>
      </div>
    </div>
            <?php  echo $this->Form->create('Country' , array('class' => 'normal','action' => 'update'));?>
            <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    
				<table class="list">
                <tr>
                    <th rowspan="2"><?php echo __l('Select'); ?></th>
                    <th rowspan="2"><?php echo __l('Actions'); ?></th>
                    <th class="dl" rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'), 'Country.name');?></div></th>
                    <th rowspan="2" class="dc"><div class="js-pagination" ><?php echo $this->Paginator->sort(__l('fips_code'), 'Country.fips_code');?></div></th>
                    <th rowspan="2" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Iso_alpha2'), 'Country.iso_alpha2');?></div></th>
                    <th rowspan="2" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Iso_alpha3'), 'Country.iso_alpha3');?></div></th>
                    <th rowspan="2" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Iso_numeric'), 'Country.iso_numeric');?></div></th>
                    <th class="dl" rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Capital'), 'Country.capital');?></div></th>
                    <th colspan="2" class="dc"><?php echo __l('Currency');?></th>
                </tr>
                <tr>
                    <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'), 'Country.currencyName');?></div></th>
                    <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Code'), 'Country.currency');?></div></th>

                </tr>
                <?php
                if (!empty($countries)):
                    $i = 0;
                    foreach ($countries as $country):
                        $class = null;
                        if ($i++ % 2 == 0) :
                            $class = ' class="altrow"';
                        endif;
                        ?>
                        <tr<?php echo $class;?>>
                            <td>
								<?php
                                echo $this->Form->input('Country.'.$country['Country']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$country['Country']['id'],'label' => false , 'class' => 'js-checkbox-list'));
                                ?>
                            </td>
                            <td class="actions">
                                    <div class="action-block">
                                            <span class="action-information-block">
                                                <span class="action-left-block">&nbsp;&nbsp;</span>
                                                    <span class="action-center-block">
                                                        <span class="action-info">
                                                            <?php echo __l('Action');?>
                                                         </span>
                                                    </span>
                                                </span>
                                                <div class="action-inner-block">
                                            <div class="action-inner-left-block">
                                                <ul class="action-link clearfix">
                                                    <li><?php  echo $this->Html->link(__l('Edit'), array('action'=>'edit', $country['Country']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        	                       	<li>
                                                            <?php
                    										$delete_icon_show = 1;
                    										foreach($country['City'] as $city)
                    										{
                                                                if($city['slug'] == Configure::read('site.city'))
                                                                    {
                                                                        $delete_icon_show = 0;
                                                                    }
                                                            }
                                                            if(!empty($delete_icon_show))
                                                            {
                                                              echo $this->Html->link(__l('Delete'), array('action'=>'delete', $country['Country']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));
                                                            } ?>
                                                     </li>
                               					 </ul>
                                			 </div>
                                					<div class="action-bottom-block"></div>
                                				  </div>
                                       </div>

                        </td>
                            <td class="dl"><?php echo $this->Html->cText($country['Country']['name']);?></td>
                            <td class="dc"><?php echo $this->Html->cText($country['Country']['fips_code']);?></td>
                            <td class="dc"><?php echo $this->Html->cText($country['Country']['iso_alpha2']);?></td>
                            <td class="dc"><?php echo $this->Html->cText($country['Country']['iso_alpha3']);?></td>
                            <td class="dc"><?php echo $this->Html->cText($country['Country']['iso_numeric']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($country['Country']['capital']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($country['Country']['currencyName']);?></td>
                            <td class="dc"><?php echo $this->Html->cText($country['Country']['currency']);?></td>
                         </tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td colspan="20"><p class="notice"><?php echo __l('No countries available');?></p></td>
                    </tr>
                    <?php
                endif;
                ?>
            </table>

            <?php if (!empty($countries)): ?>
            
             <div class="clearfix select-block-bot">
                <div class="admin-select-block grid_left">
                    <div>
                        <?php echo __l('Select:'); ?>
                        <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                        <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                    </div>
                     <div>
                        <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
                    </div>
                </div>
                 <div class="js-pagination grid_right">
                    <?php echo $this->element('paging_links');  ?>
                </div>
            </div>

                <div class="hide">
                <div class="submit-block clearfix">
                    <?php echo $this->Form->submit('Submit');  ?>
                </div>
                </div>
                <?
            endif; ?>
                <?php echo $this->Form->end(); ?>
  
</div>