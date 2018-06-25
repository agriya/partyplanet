<?php
/* SVN FILE: $Id: error404.ctp 7073 2008-05-31 04:50:38Z gwoo $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.errors
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 7073 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2008-05-31 10:20:38 +0530 (Sat, 31 May 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<div class="notfound-error-message">
<div class="inside-error-block">
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?php echo __l('404 Error'); ?>: </strong>
	<?php echo sprintf(__l("The requested address %s was not found on this server."), "<strong>'{$message}'</strong>")?>
</p>
</div>
</div>