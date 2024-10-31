<?php 
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalestate
 * @author     Opal  Team <info@wpopal.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @class OpalEstate_Agent
 *
 * @version 1.0
 */
class OpalEstate_Agent{

	/**
	 * @var String $author_name
	 *
	 * @access protected
	 */
	protected $author_name; 
	
	/**
	 * @var Boolean $is_featured
	 *
	 * @access protected
	 */
	protected $is_featured; 
	
	/**
	 *  Constructor
	 */	
	public function __construct( $post_id=null ){

		global $post;

 		$this->post 		= $post;
	 	$this->post_id 		= $post_id ? $post_id:get_the_ID();
		$this->author 		= get_userdata( $post->post_author );
		$this->author_name  = !empty($this->author)? sprintf('%s %s', $this->author->first_name, $this->author->last_name):null;
		$this->is_featured 	= $this->getMeta( 'featured' ); 

	}

	/**
	 * Get Collection Of soicals with theirs values
	 */
	public function get_socials(){
		$socials = array(
						 'facebook' 	=> '',
						  'twitter' 	=> '',
						  'pinterest' 	=> '',
						  'google' 		=> '',
						  'instagram' 	=> '',
						  'linkedIn' 	=> ''
		);
		
		$output = array();

		foreach( $socials as $social => $k ){  

			$data = $this->getMeta( $social );
			if( $data && $data != "#" && !empty($data) ){ 
				$output[$social] = $data;
			}
		} 

		return $output;
	}

	/**
	 * Get url of user avatar by agent id
	 */
	public static function get_avatar_url( $userID ){
	
		return get_post_meta( $userID , OPALESTATE_AGENT_PREFIX . "avatar", true );

	}

	/**
	 * Render list of levels of agent
	 */
 	public function render_level(){
		$levels = wp_get_post_terms( $this->post_id, 'opalestate_agent_level' );

		if( empty($levels) ){
			return ;
		}

		$output = '<span class="agent-levels">';
		foreach( $levels as $key => $value ){
			$output .= '<span class="agent-label"><span>'.$value->name.'</span></span>';
		}
		$output .= '</span>';

		echo $output;
	}

	/**
	 * get meta data value of key without prefix 
	 */
	public function getMeta( $key ){
		return get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX .  $key, true );
	}

	 
	/**
	 *  return true if this agent is featured
	 */
	public function is_featured(){
		return $this->is_featured;
	}

	/**
	 *  render block information by id
	 */
	public static function render_box_info( $post_id ){
		ob_start();
		$args = array(
			'post_type' => 'opalestate_agent',
			'p'	=> $post_id
		);
		$loop = new WP_Query($args);

		if( $loop->have_posts() ){
			while( $loop->have_posts() ){  $loop->the_post();
			 	echo Opalestate_Template_Loader::get_template_part( 'single-agent/author-box' );
			}
		}
	 	wp_reset_postdata();
 		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	public static function get_link( $agent_id ){
		$agent =  get_post( $agent_id );  
		$url = self::get_avatar_url( $agent_id );
		$avatar = $url ? '<img class="avatar" src="'.$url.'" alt="" />':"";
		return !$agent ? __('Unknow Agent', 'opalestate'): '<a href="'.get_permalink($agent->ID).'" class="author-link">'.$avatar.'<span>'. $agent->post_title.'</span>'.'</a>';
	}
}
?>