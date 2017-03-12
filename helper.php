<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_galleryslider
 *
 * @copyright   Christian Friedemann - pixelstun.de - c.friedemann@pixelstun.de
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class ModGallerySliderHelper{
		
	/**
	 * Get a set of articles
	 *
	 * @param   int $count number of articles
	 * @param	bool $featuredOnly get only featured articles
	 * @param	category $category articles of given categories only
	 * @return  array
	 */
	public static function getArticles( $count = 5 , $featuredOnly = true, $category = false, $order = 0 ) {

		foreach ( $category  as &$cat ) { //just to be sure nobody manipulated the inputs
			$cat = (int) $cat;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery( true );
		
		$orderOptions = array(
			'0' => $db->quoteName( 'created' ) . ' DESC',
			'1' => $db->quoteName( 'created' ) . ' ASC',
			'2' => $db->quoteName( 'published' ) . ' DESC',
			'3' => $db->quoteName( 'published' ) . ' ASC',
			'4' => $db->quoteName( 'modified' ) . ' DESC',
			'5' => $db->quoteName( 'modified' ) . ' ASC',
			'6' => 'RAND()'
		);
		
		$order = $orderOptions[$order];		
		$options = array('state = 1'); //published only
		
		if ( true == $featuredOnly ) {
			array_push( $options , $db->quoteName( 'featured' ) . ' = 1' );
		}
		
		if ( ! empty( $category ) ) {
			array_push($options, $db->quoteName( 'catid' ) . ' IN (' . implode( ', ' , $category ) . ')' );
		}
		
		$options = implode( ' AND ', $options );
		
		$articles = $query->select(
								   array(
										  $db->quoteName( 'title' ),
										  $db->quoteName( 'introtext' ),
										  $db->quoteName( 'images' ),
										  $db->quoteName( 'catid' ),
										  $db->quoteName( 'id' ),
										 )
								  )
					->from( $db->quoteName( '#__content' ) )
					->where( $options )
					->setLimit( (string) $count )
					->order ( $order );
		$db->setQuery( $query );
		return ( $db->loadObjectList() );
	}
	
	/**
	 * Cut text after a certain number of words
	 *
	 * @param   string $text text to shorten
	 * @param	int $numWords number of words to keep
	 * @return  string shortened text
	 */
	public static function shortenText( $text, $numWords = 0 ){
		if ( 0 == $numWords ) {
			return $text;
		}
		if (0 == strlen($text) ) {
			return false;
		}
		$text = strip_tags( $text, '<br><a>');
		return implode( '', array_slice( preg_split( '/([\s,\.;\?\!]+)/' , $text , $numWords*2+1, PREG_SPLIT_DELIM_CAPTURE ), 0, $numWords*2-1 ) ) . '...';
	}
	
	/**
	 * Build linked title
	 *
	 * @param  	object $article article
	 * @param 	string $menuid id for building a route
	 * @return  string link to article
	 */
	public static function buildTitleLink($article, $menuid = '', $hide = false){
		if ( ! empty($menuid) ) {
			$menuid = '&Itemid=' . (int) $menuid;
		}
		if ( $hide ) {
			$menuid .= '&hideslider=1';
		}
		return '<a href="' . JRoute::_('index.php?view=article&id=' . $article->id . '&catid=' . $article->catid) . $menuid .'">' . $article->title . '</a>';
	}
	
	/**
	 * Build readmore link
	 *
	 * @param  	object $article article
	 * @param  	string $readmore none|button|link
	 * @return  string link to article
	 */
	public static function buildReadmoreLink($article, $menuid = '' , $readmore = 'none', $hide = false){
		if ( ! empty($menuid) ) {
			$menuid = '&Itemid=' . (int) $menuid;
		}
		if ( $hide ) {
			$menuid .= '&hideslider=1';
		}
		if ( 'none' !== $readmore ) {
			$text = str_replace( '...', '', JText::_('COM_CONTENT_READ_MORE_TITLE'));
			return ' <a class="readmore '. $readmore . '" href="' . JRoute::_('index.php?view=article&id=' . $article->id . '&catid=' . $article->catid . $menuid ) . '">' . $text . '</a> ';
		}
	}
}
