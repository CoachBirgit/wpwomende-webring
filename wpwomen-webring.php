<?php
/**
 * Plugin Name: WP Women Webring
 * Plugin URI:	http://wpwomende.org/webring/
 * Description:	We do a webring of pages that are attending to the WP Women DE like it's 1997
 * Version:		0.1.0
 * Author: 		Birgit Olzem
 * Author URI:	http://www.coachbirgit.de
 * Licence: 	GPL2
 * 
 * Credits to Robert Windisch @nullbytes und Bernhard Kau @kayboys, for developing the first idea for a webring in celebration the outdated WP Camp Berlin 2013
 * Copyright 2014  Birgit Olzem  (email : coachbirgit (at) gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  */

if ( ! class_exists( 'wp_women_webring' ) ) {
	add_action( 'plugins_loaded', array( 'wp_women_webring', 'get_object' ) );
	
	class wp_women_webring {

		/**
		 * The class object
		 *
		 * @static
		 * @since  0.1
		 * @var    string
		 */		
		static private $classobj = NULL;

		/**
		 * The array of the blogs participating in the webring
		 *
		 * @since  0.2
		 * @var    array
		 */		
		public $blogs = array();

		/**
		 * The home_url of the blog
		 *
		 * @since  0.2
		 * @var    string
		 */				
		public $home_url = NULL;
		
		/**
		 * Constructor, init on defined hooks of WP and include second class
		 * 
		 * @access  public
		 * @since   0.1
		 * @uses    add_filter, home_url, shuffle
		 * @return  void
		 */
		public function __construct() {
			
			$this->home_url = home_url();
			
			// set the blogs array and shuffle it
			$this->blogs = array(
				'http://www.wpwomende.org',
				'http://www.birgitolzem.de',
				'http://www.elbmedien.de',
				'http://www.taxifisch.com',
				'http://www.die-netzialisten.de',
				'http://www.texto.de',
				'http://www.voelckner.de',
				'http://wp-bistro.de',
			);

			shuffle( $this->blogs );
			
			// show the webring in footer
			add_filter( 'wp_footer', array( $this, 'display_webring' ) );

			add_filter( 'wp_enqueue_scripts', array( $this, 'load_style' ) );
		}
		
		/**
		 * Handler for the action 'init'. Instantiates this class.
		 * 
		 * @access  public
		 * @since   0.1
		 * @return  object $classobj
		 */
		public function get_object() {
			
			if ( NULL === self::$classobj ) {
				self::$classobj = new self;
			}
			
			return self::$classobj;
		}

		/**
		 * display the webring and choose two random blogs
		 *
		 * @access  public
		 * @since   0.1
		 * @uses    get_blog_url
		 * @return  void
		 */
		public function display_webring() {

			?><div class="wpwomen-webring"><a href="<?php echo $this->get_blog_url(); ?>" class="wpwomen-webring-prev" rel="nofollow">&#9668;</a> <a href="http://wpwomende.org/webring" class="wpwomen-webring-list" rel="nofollow">WP Women Webring</a> <a href="<?php echo $this->get_blog_url(); ?>" class="wpwomen-webring-next" rel="nofollow">&#9658;</a></div><?php
		}
		
		/**
		 * Load frontend CSS
		 * 
		 * @access  public
		 * @since   0.1
		 * @uses    get_blog_url
		 * @return  void
		 */
		public function load_style() {

			wp_enqueue_style( 'wpwomen-webring', plugins_url( 'wpwomende-webring.css', __FILE__ ) );
		}
		
		/**
		 * Get a blog URL from the blogs array excluding the blog matching the home_url
		 * 
		 * @access  public
		 * @since   0.2
		 * @uses    array_shift
		 * @return  string $blog_url
		 */
		public function get_blog_url() {
			
			$blog_url = array_shift( $this->blogs );
			
			if ( parse_url( $blog_url, PHP_URL_HOST ) == parse_url( $this->home_url, PHP_URL_HOST ) )
				$blog_url = array_shift( $this->blogs );
			
			return $blog_url;
		}
	} // end class
} // end if class exists