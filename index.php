<?php
/*
Plugin Name: Lorem ipsum article shortcode
Version: 1.0.0
Plugin URI: #
Description: #
Author: Ron Valstar
Author URI: http://www.ronvalstar.nl
*/

if (!class_exists('LoremIpsumArticleShortcode')) {
	class LoremIpsumArticleShortcode {

		private $aLorem = array('a','et','at','in','mi','ac','id','eu','ut','non','dis','cum','sem','dui','nam','sed','est','nec','sit','mus','vel','leo','urna','duis','quam','cras','nibh','enim','quis','arcu','orci','diam','nisi','nisl','nunc','elit','odio','amet','eget','ante','erat','eros','ipsum','morbi','nulla','neque','vitae','purus','felis','justo','massa','donec','metus','risus','curae','dolor','etiam','fusce','lorem','augue','magna','proin','mauris','nullam','rutrum','mattis','libero','tellus','cursus','lectus','varius','auctor','sociis','ornare','magnis','turpis','tortor','semper','dictum','primis','ligula','mollis','luctus','congue','montes','vivamus','aliquam','integer','quisque','feugiat','viverra','sodales','gravida','laoreet','pretium','natoque','iaculis','euismod','posuere','blandit','egestas','dapibus','cubilia','pulvinar','bibendum','faucibus','lobortis','ultrices','interdum','maecenas','accumsan','vehicula','nascetur','molestie','sagittis','eleifend','facilisi','suscipit','volutpat','venenatis','fringilla','elementum','tristique','penatibus','porttitor','imperdiet','curabitur','malesuada','vulputate','ultricies','convallis','ridiculus','tincidunt','fermentum','dignissim','facilisis','phasellus','consequat','adipiscing','parturient','vestibulum','condimentum','ullamcorper','scelerisque','suspendisse','consectetur','pellentesque');
		private $iLorem;
		private $fDeviation;

		function __construct() {
			$this->iLorem = count($this->aLorem);
			add_shortcode('lll', array(&$this,'handleShortcode'));
			add_filter( 'the_title', 'do_shortcode', 11 );
		}

		function handleShortcode($atts,$content=null,$shortcode){
			global $post;
			//post_content
			//post_title
			//post_type
			$aAttributeAlias = array(
				'p'=>'paragraph'
				,'s'=>'sentence'
				,'w'=>'word'
				,'h'=>'header'
			);
			// map alias attributes
			foreach ($aAttributeAlias as $alias=>$key) {
				if (isset($atts[$alias])) {
					$atts[$key] = $atts[$alias];
					unset($atts[$alias]);
				}
			}
			// predefining shortcode attribute variables to prevent IDE from whining about undefined vars
			// the number of paragraphs
			$paragraph = 7;
			// the number of sentences in a paragraph
			$sentence = 5;
			// the number of words in a sentence
			$word = 12;
			// the length of the header
			$header = null;
			$headerSize = 6;
			// the random seed
			$seed = $post->ID;
			// how much the sentence-, paragraph and article length can deviate
			$deviation = .5;
			//
			extract(shortcode_atts(array(
				'paragraph' =>	$paragraph
				,'sentence' =>	$sentence
				,'word' =>		$word
				,'header' =>	$header
				,'seed' =>		$seed
				,'deviation' =>	$deviation
			), $atts));
			//
			$this->fDeviation = floatval($deviation);
			srand($seed);
			//
			// is header
			if (is_array($atts)&&(in_array('h',$atts)||in_array('header',$atts))) {
				if (!isset($header)) $header = $headerSize;
				$sResult = $this->getSentence($header,false);
			} else {
				$sResult = $this->getArticle($paragraph,$sentence,$word);
			}
			//
//			dump($atts);
//			dump($content);
//			dump($paragraph);
//			dump($post);
//			dump($this->getArticle($paragraph,$sentence,$word));
//			dump(implode(' ',$this->getArticle($paragraph,$sentence,$word)));
			//
			return $sResult;
		}

		/**
		 * Returns and article
		 * @param $numParagraphs
		 * @param $numSentences
		 * @param $numWords
		 * @return string
		 */
		private function getArticle($numParagraphs,$numSentences,$numWords){
			$aArticle = array();
			for ($i=0;$i<$this->deviateInt($numParagraphs,$this->fDeviation);$i++) {
				$aArticle[] = $this->getParagraph($numSentences,$numWords);
			}
			return implode('',$aArticle);
		}

		/**
		 * Returns a paragraph.
		 * @param $numSentences
		 * @param $numWords
		 * @return string
		 */
		private function getParagraph($numSentences,$numWords){
			$aParagraph = array();
			for ($i=0;$i<$this->deviateInt($numSentences,$this->fDeviation);$i++) {
				$aParagraph[] = $this->getSentence($numWords);
			}
			return '<p>'.$this->enhanceParagraph(implode(' ',$aParagraph)).'</p>';
		}

		/**
		 * Returns a sentence.
		 * @param $numWords
		 * @return string
		 */
		private function getSentence($numWords,$enhanced=true){
			$aSentence = array();
			for ($i=0;$i<$this->deviateInt($numWords,$this->fDeviation);$i++) {
				$sWord = $this->aLorem[rand(0,$this->iLorem-1)];
				$aSentence[] = $enhanced?$this->enhanceWord($sWord):$sWord;
			}
			return ucfirst(implode(' ',$aSentence)).($enhanced?'.':'');
		}

		/**
		 * Deviates randomly for a given integer.
		 * @param $int
		 * @param $deviation
		 * @return $int
		 */
		private function deviateInt($int,$deviation) {
			$iDeviation = intval($int*$deviation);
			return $int + rand(-$iDeviation,$iDeviation);
		}

		/**
		 * Randomly enhance words with anchor, strong or em
		 * @param $word
		 * @return string
		 */
		private function enhanceWord($word){
			if (rand(0,100)===1) {
				$word = '<a href="#">'.$word.'</a>';
			} else if (rand(0,100)===1) {
				$word = '<strong>'.$word.'</strong>';
			} else if (rand(0,100)===1) {
				$word = '<em>'.$word.'</em>';
			}
			return $word;
		}

		/**
		 * Randomly enhance paragraphs with images
		 * @param $paragraph
		 * @return string
		 */
		private function enhanceParagraph($paragraph){
			if (rand(0,2)===1) {
				$aAlign = array('alignright','alignnone','alignleft','aligncenter');
				$iKey = array_rand($aAlign);
				$sClass = $aAlign[$iKey];
				$paragraph = '<img class="'.$sClass.'" src="'.$this->createImageData().'" />'.$paragraph;
			}
			return $paragraph;
		}

		/**
		 * Create a base64 dummy image for use in src
		 * @return string
		 */
		private function createImageData(){
			$aSizes = $this->getImageSizes();
			$fAspectRatio = 4/3;
			$iW = $aSizes['medium'][0];
			$iH = $iW/$fAspectRatio;
			//
			$oImg = imagecreatetruecolor($iW,$iH);
			$oClrFill = imagecolorallocate($oImg, 64, 64, 64);
			$oClrLine = imagecolorallocate($oImg, 192, 192, 192);
			//
			imagefill($oImg,0,0,$oClrFill);
			imagerectangle($oImg,0,0,$iW-1,$iH-1,$oClrLine);
			imageline($oImg,0,0,$iW-1,$iH-1,$oClrLine);
			imageline($oImg,0,$iH-1,$iW-1,0,$oClrLine);
			//
			ob_start();
			imagepng($oImg);
			$data = ob_get_contents();
			ob_end_clean();
			imagedestroy($oImg);
			//
			return 'data:image/png;base64,' . base64_encode($data);
		}

		/**
		 * Read all Wordpress image sizes
		 * @return array
		 */
		private function getImageSizes() {
			global $_wp_additional_image_sizes;
			$sizes = array();
			foreach (get_intermediate_image_sizes() as $s) {
				$sizes[$s] = array(0,0);
				if (in_array($s,array('thumbnail','medium','large'))) {
					$sizes[$s][0] = get_option($s.'_size_w');
					$sizes[$s][1] = get_option($s.'_size_h');
				} else {
					if (isset($_wp_additional_image_sizes)&&isset($_wp_additional_image_sizes[$s])) {
						$sizes[$s] = array($_wp_additional_image_sizes[$s]['width'],$_wp_additional_image_sizes[$s]['height'],);
					}
				}
			}
			return $sizes;
		}
	}
	new LoremIpsumArticleShortcode();
}
/*
 * commas
 * exclamation mark
 * question mark
 * ordered lists
 * unordered lists
 * parenthesis
 * blockquote
 * h3,h4,h5
 *
 * min word: 300
 * https://medium.com/starts-with-a-bang/how-the-sun-really-shines-2dc9fafd3ea4
 * http://en.wikipedia.org/wiki/Flesch%E2%80%93Kincaid_readability_tests#Flesch_Reading_Ease
 **/
?>