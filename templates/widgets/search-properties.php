<?php
/**
 * $Desc
 *
 * @version    $Id$
 * @package    wpbase
 * @author     WpOpal Team <help@wpopal.com, info@wpopal.com>
 * @copyright  Copyright (C) 2015 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/questions/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo trim($before_widget);
if( $title )
	echo ($before_title)  . trim( $title ) . $after_title;

?>

<div class="widget-content ">
	<div class="search-properies-form">
		
		<?php OpalEstate_Search::render_vertical_form(); ?> 

	</div>
</div>

