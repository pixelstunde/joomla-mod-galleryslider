<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_galleryslider
 *
 * @copyright   Christian Friedemann - pixelstun.de - c.friedemann@pixelstun.de
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

foreach ( $articles as $article ) {
	$images = json_decode( $article->images );
	
	if ( $showTitle ) {
		if ( $linkTitle ){
			$title = ModGallerySliderHelper::buildTitleLink($article, $menuid, $hide);
		}
		else{
			$title = $article->title;
		}
	}
	
	$title = '<div class="headline">' . $title . '</div>';
	
	if ( ! empty( $images->image_intro ) ) {
		$bg = ' style="background-image: url(\'' . $images->image_intro . '\')" ';
	}
	else {
		$bg = '';
	}

	if ( true == $introtext ) {
		$boxContent = ModGallerySliderHelper::shortenText( $article->introtext, $words );
		if ( false != $boxContent){
			$boxContent = '<div class="spoilers">
							<div class="content">' .
								$title .
								$boxContent .
								ModGallerySliderHelper::buildReadmoreLink( $article, $menuid, $readmore, $hide).
							'</div>
						</div>';
		}
	}
	else {
		$boxContent = '<div class="title-only">' . $title . '</div>';
	}
	$operators .= '<div id="item-' . ++$count . '" class="control-operator"></div>';
	$figures .= '<figure class="item custom-controls" '. $bg .' >
					'. $boxContent . '
				</figure>';
	$controls .= '<a href="#item-' . $count . '" class="control-button">•</a>';
}
?>
<div class="gallery <?php echo $autoplay ?> items-<?php echo $count ?>" <?php echo $style ?>>
	<?php
		echo $operators;
		echo $figures;
	?>
	<div class="controls">
		<?php
			echo $controls;
		?>
	</div>
</div>