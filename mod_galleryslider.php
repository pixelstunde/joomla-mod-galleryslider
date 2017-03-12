<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_galleryslider
 *
 * @copyright   Christian Friedemann - pixelstun.de - c.friedemann@pixelstun.de
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//dont show slider after links clicked if not wanted
$hide = JFactory::getApplication()->input->get('hideslider');
if ( isset( $hide ) ){
    return;
}

// Include the menu functions only once
require_once __DIR__ . '/helper.php';

//get params
$autoplay = $params->get( 'autoplay', '1' ) ? 'autoplay' : '';
$bgstyle =  $params->get( 'backgroundStyle', 'contain' );
$category = $params->get( 'category', false );
$count = (int) $params->get( 'count', 5 );
$featuredOnly = (bool) $params->get( 'featuredOnly' , true );
$height = $params->get('height' , 'auto');
$hide = (bool) $params->get('hide' , false);
$linkTitle =  (bool) $params->get('linkTitle' , false);
$menuid = $params->get( 'menuid', '' );
$order = (int) $params->get( 'order', 0 );
$readmore = $params->get('readmore' , false);
$showTitle =  (bool) $params->get('showTitle' , false);
$words = (int) $params->get( 'words', 10 );

//get articles
$articles = ModGallerySliderHelper::getArticles( $count , $featuredOnly, $category, $order);

//initialize variables for displaying
$controls = '';
$figures = '';
$operators = '';
$style = '';
$title = '';
$count = 0;

//add stylesheet
$document = JFactory::getDocument();
$document->addStylesheet( JURI::base(true).'/modules/mod_galleryslider/css/gallery.css' );

if ( 'auto' != $height ){
	$style = 'height:' . $height . ';';
}

if ( ! empty($style) ){
	$style = ' style="' . $style . '"';
}

if ( 'cover' == $bgstyle ) {
    $document->addStyleDeclaration('.gallery .item{ background-size: cover;}');
}

//render
require JModuleHelper::getLayoutPath( 'mod_galleryslider' , $params->get( 'layout', 'default' ) );
