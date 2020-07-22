<?php
namespace voidgrid\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.0.0
 */

class Void_Post_Grid extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'void-post-grid';
	}

	public function get_title() {
		return 'Void Post Grid';   // title to show on elementor
	}

	public function get_icon() {
		return 'eicon-posts-grid';    
	}

	public function get_categories() {
		return [ 'void-elements' ];    // category of the widget
	}

	// public function is_reload_preview_required() {
	// 	return true;   
	// }

	public function get_script_depends() {		//load the dependent scripts defined in the voidgrid-elements.php
		return [ 'void-grid-equal-height-js', 'void-grid-custom-js' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/
    protected function _register_controls() {
            
    //start of a control box
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Post Grid Query', 'void' ),   //section name for controler view
			]
		);

        $this->add_control(
            'refer_wp_org',
            [
                'raw' => __( 'For more detail about following filters please refer <a href="https://codex.wordpress.org/Template_Tags/get_posts" target="_blank">here</a>', 'void' ),
                'type' => Controls_Manager::RAW_HTML,
                'classes' => 'elementor-descriptor',
            ]
        );
        $this->add_control(
            'post_type',
            [
                'label' => esc_html__( 'Select post type', 'void' ),
                'type' => Controls_Manager::SELECT2,
                'options' => void_grid_post_type(),                                
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'taxonomy_type',
            [
                'label' => __( 'Select Taxonomy', 'void' ),
                'type' => Controls_Manager::SELECT2,
                'options' => (object) array(),                              
            ]
        );
        
        $repeater->add_control(
            'terms',
            [
                'label' => __( 'Select Terms (usually categories/tags) * Must Select Taxonomy First', 'void' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => '',              
                'multiple' => true,
                'condition' => [
                            'taxonomy_type!' =>'',
                        ], 
            ]
        );

        $repeater->add_control(
            'compare',
            [
                'label' => __( 'Compare(Operator)', 'void' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'IN',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'reffer_compare',
            [
                'raw' => __( 'To know about operator check <a href="https://elequerybuilder.com/operator/" target="_blank">this</a>', 'void' ),
                'type' => Controls_Manager::RAW_HTML,
                'classes' => 'elementor-descriptor',
            ]
        );

        $this->add_control(
            'tax_fields',
            [
                'label' => __( 'Taxonomy & terms combination', 'void' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'label_block' => true,
                'item_actions' => array('duplicate' => false),
                'default' => [
                    [
                        'taxonomy_type' => '',
                        'terms' => '',
                        
                    ],
                ],
                'title_field' => '{{{ taxonomy_type }}}', 
                          
            ]
        );

        $this->add_control(
            'tax_fields_relation',
            [
                'label' => __( 'Fields Relation', 'void' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'AND', 'void' ),
                'label_off' => __( 'OR', 'void' ),
                'return_value' => 'AND',
                'default' => 'OR',
                
            ]
        );

        $this->add_control(
			'meta_query_section',
			[
				'label' => __( 'Meta query', 'void' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
        
        $this->add_control(
            'meta_key',
            [
                'label' => __( 'Name/Key', 'void' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'options' => '',
                'label_block' => true,

            ]
        );
        $this->add_control(
            'meta_value',
            [
                'label' => __( 'Value', 'void' ),
                'type' => Controls_Manager::TEXT,
                'options' => '',
                'label_block' => true,
                'placeholder' => __( 'i.e. value1, value2', 'void' ),
                'condition' => [
                    'meta_key!' =>'',
                ] 
            ]
        );
        $this->add_control(
            'meta_compare',
            [
                'label' => __( 'Compare(Operator)', 'void' ),
                'type' => Controls_Manager::TEXT,
                'options' => '',
                'label_block' => true,
                 
                'condition' => [
                    'meta_value!' =>'',
                ] 
            ]
        );
        $this->add_control(
            'reffer_custom_compare',
            [
                'raw' => __( 'To know about operator check <a href="https://elequerybuilder.com/operator/" target="_blank">this</a>', 'void' ),
                'type' => Controls_Manager::RAW_HTML,
                'classes' => 'elementor-descriptor',
                'condition' => [
                    'meta_value!' =>'',
                ] 
            ]
        );

        $this->add_control(
          'cat_exclude',
            [
                'label'       => __( 'Include / Exclude With Category ID', 'void' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'description' => __( 'Get post category id and add them here. To Include use the id(s) directly (Example: 1,2,3), To exclude category add a minus sign before the category ID (Example : -1,-44,-3343)', 'void' ),
                'placeholder' => __( '-1,-2,-33,10,11', 'void' ),
                'condition' => [
                    'post_type' => 'post',
                ],
            ]
        );
        $this->add_control(
            'reffer_category_find',
            [
                'raw' => __( 'For finding out your category ID follow <a href="https://voidcoders.com/find-category-id-wordpress/" target="_blank">this</a>', 'void' ),
                'type' => Controls_Manager::RAW_HTML,
                'classes' => 'elementor-descriptor',
                'condition' => [
                    'post_type' => 'post',
                ],
            ]
        );


        $this->end_controls_section();


		$this->start_controls_section(
            'section_content2',
            [
                'label' => esc_html__( 'Pagination & Setting', 'void' ),   //section name for controler view
            ]
        );

        $this->add_control(
            'posts',     
            [
                'label' => esc_html__( 'Post Per Page', 'void' ),
                'description' => esc_html__( 'Give -1 for all post & No Pagination', 'void' ),
                'type' => Controls_Manager::NUMBER,
                'default' => -1,
            ]
        );

        $this->add_control(
                'pagination_yes',
                [
                    'label' => esc_html__( 'Pagination Enabled', 'void' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        1 => 'Yes',
                        2 => 'No'
                    ],
                    'default' => 1,
                    'condition' => [
                            'posts!' => -1,
                        ]
                ]
            );
        $this->add_control(
            'offset',
            [
                'label' => esc_html__( 'Post Offset', 'void' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '0'
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'void' ),
                'type' => Controls_Manager::SELECT,
                'options' => voidgrid_post_orderby_options(),
                'default' => 'date',

            ]
        );
        
        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Post Order', 'void' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ],
                'default' => 'desc',

            ]
        );

        $this->add_control(
            'sticky_ignore',
            [
                'label' => esc_html__( 'Sticky Condition', 'void' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => 'Remove Sticky',
                    '0' => 'Keep Sticky'
                ],

                'default' => '1',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content3',
            [
                'label' => esc_html__( 'Post Style & Image Settings', 'void' ),   //section name for controler view
            ]
        );


        $this->add_control(
            'display_type',
            [
                'label' => esc_html__( 'Choose your desired style', 'void' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grid' => 'Grid Layout', 
                    'list' => 'List Layout', 
                    'first-post-grid' => '1st Full Post then Grid',
                    'first-post-list' => '1st Full Post then List',
                    'minimal' => 'Minimal Grid'
                ],
                'default' => 'grid'
            ]
        );

        $this->add_control(
            'posts_per_row',
            [
                'label' => esc_html__( 'Posts Per Row', 'void' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'display_type' => ['grid','minimal'],
                ],
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '6' => '6',
                ],
                'default' => '2',
            ]
        );
		
        
        $this->add_control(
            'filter_thumbnail',
            [
                'label' => esc_html__( 'Image Condition', 'void' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                     0 => esc_html__( 'Show All', 'void' ),
                    'EXISTS' => esc_html__( 'With Image', 'void' ),
                    'NOT EXISTS' => esc_html__( 'Without Image', 'void' ),
                ],
                'default' => 0,

            ]
        );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`.
				'default' => 'large',
				'exclude' => [ 'custom' ],
                'condition' => [
                    'filter_thumbnail!' => 'NOT EXISTS',
                ],
			]
		);
        $this->add_control(
            'image_style',
            [
                'label' => esc_html__('Featured Image Style', 'void'),
                'type'  => Controls_Manager::SELECT2,
                'options' => [
                    'standard' => 'Standard',
                    'top-left' => 'left top rounded',
                    'top-right' => 'left bottom rounded'
                ],
                'default'   => 'standard',
                'condition' => [
                    'filter_thumbnail!' => 'NOT EXISTS',
                ],
            ]
        );
       
      
		$this->end_controls_section();




        $this->start_controls_section(
            'section_style_grid',
            [
                'label' => esc_html__( 'Style', 'void' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_text_transform',
            [
                'label' => esc_html__( 'Title Text Transform', 'void' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'None', 'void' ),
                    'uppercase' => esc_html__( 'UPPERCASE', 'void' ),
                    'lowercase' => esc_html__( 'lowercase', 'void' ),
                    'capitalize' => esc_html__( 'Capitalize', 'void' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'text-transform: {{VALUE}};',   //the selector used above in add_control
                ],
            ]
        );

        $this->add_responsive_control(
            'title_font_size',
            [
                'label' => esc_html__( 'Title Size', 'void' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'void' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Title Hover Color', 'void' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_color',
            [
                'label' => esc_html__( 'Meta Color', 'void' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_hover_color',
            [
                'label' => esc_html__( 'Meta Hover Color', 'void' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_color_i',
            [
                'label' => esc_html__( 'Meta Icon Color', 'void' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'excerpt_text_transform',
            [
                'label' => esc_html__( 'Excerpt Transform', 'void' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'None', 'void' ),
                    'uppercase' => esc_html__( 'UPPERCASE', 'void' ),
                    'lowercase' => esc_html__( 'lowercase', 'void' ),
                    'capitalize' => esc_html__( 'Capitalize', 'void' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .blog-excerpt p' => 'text-transform: {{VALUE}};',   //the selector used above in add_control
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_font_size',
            [
                'label' => esc_html__( 'Excerpt Size', 'void' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blog-excerpt p' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'exceprt_color',
            [
                'label' => esc_html__( 'Excerpt Color', 'void' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blog-excerpt p' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'te_align',
            [
                'label' => __( 'Text Alignment', 'void' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'void' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'void' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'void' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'void' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .blog-excerpt p' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __( 'Pagination Alignment', 'void' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'void' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'void' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'void' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'void' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .void-grid-nav' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagi_font_size',
            [
                'label' => esc_html__( 'Pagination Size', 'void' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .void-grid-nav' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

	}


	protected function render() {
        //to show on the fontend 
        $settings = $this->get_settings();

        $post_type        = isset($settings['post_type'])? $settings['post_type']: '';
        $filter_thumbnail = isset($settings['filter_thumbnail'])? $settings['filter_thumbnail']: '';
        $cat_exclude      = isset($settings['cat_exclude'])? $settings['cat_exclude']: ''; // actually include or exclude both
        $display_type     = isset($settings['display_type'])? $settings['display_type']: '';   
        $posts            = isset($settings['posts'])? $settings['posts']: '';
        $posts_per_row    = isset($settings['posts_per_row'])? $settings['posts_per_row']: '';      
        $image_style      = isset($settings['image_style'])? $settings['image_style']: '';
        $orderby          = isset($settings['orderby'])? $settings['orderby']: '';
        $order            = isset($settings['order'])? $settings['order']: '';
        $offset           = isset($settings['offset'])? $settings['offset']: ''; 
        $sticky_ignore    = isset($settings['sticky_ignore'])? $settings['sticky_ignore']: '';
        $display_type     = isset($settings['display_type'])? $settings['display_type']: '';
        $pagination_yes   = isset($settings['pagination_yes'])? $settings['pagination_yes']: '';
        $image_size       = isset($settings['image_size'])? $settings['image_size']: '';

        //build variable needed for tax_query
        if( !empty($settings[ 'tax_fields' ][0]['taxonomy_type']) ){
    
            if( !empty($settings[ 'tax_fields_relation' ]) ){
                $tax_query[ 'relation' ] = $settings[ 'tax_fields_relation' ];
            }else{
                $tax_query[ 'relation' ] = 'OR';
            }
            foreach ($settings[ 'tax_fields' ] as $key => $value) {
                // remove _id key set by ELEMENTOR CODE
                unset( $value[ '_id' ] );
                //as WP_QUERY uses taxonomy key not taxonomy_type
                $value['taxonomy'] = $value['taxonomy_type'];
                unset( $value['taxonomy_type'] );
                //if current post is chosen, get current post terms based on taxonomy chosen

                foreach( $value[ 'terms' ] as $index => $val ){
                    if( $val == 'current' ){
                        unset( $value[ 'terms' ][$index] );
                        $current_post_terms = get_the_terms( get_the_ID(), $value['taxonomy']  );
                        foreach( $current_post_terms as $index => $term ){
                            //only push terms array if that term is not actively selected, concetaning with '' to returned ineger term_id into string to be used on in_arry as select returns as array
                            if( !( in_array( $term->term_id . '', $value['terms'] ) ) ){
                                array_push( $value['terms'], $term->term_id );
                            }                       
                        }
                    }
                }

                // set all terms on empty term input under the taxonomy
                if(empty($value[ 'terms' ])){
                    $terms = get_terms( array(
                        'taxonomy' => $value['taxonomy'],
                        'hide_empty' => false
                    ) );
                    foreach($terms as $term_key => $term_val){
                        array_push( $value['terms'], $term_val->term_id );
                    }
                }

                $tax_query[] = $value;
            }   
        }else{
            $tax_query = '';
        }

        $meta_query = [];

        if(!empty($settings['meta_value']) || !empty($settings['meta_key'])){
            $meta['key'] = isset($settings['meta_key'])? $settings['meta_key']: '';
            $meta['value'] = isset($settings['meta_value'])? $settings['meta_value']: '';
            $meta['compare'] = isset($settings['meta_compare'])? $settings['meta_compare']: '';
            $meta_query[] = $meta;
        }

        if($filter_thumbnail){
            $void_image_condition = [
                [
                    'key' => '_thumbnail_id',
                    'compare' => $filter_thumbnail,
                ]
            ];
        } else {
            $void_image_condition='';
        }

        $meta_query[] = $void_image_condition;

        set_transient('void_grid_image_size', $image_size, '60' );
        global $col_no,$count,$col_width;
        
        $count = 0;         

        $col_width = ( ($posts_per_row != '') ? (12 / $posts_per_row): 12 );
        $col_no = ( ($posts_per_row != '') ? $posts_per_row : 1 );
        
        // display type handler will be remove after data updater
        switch ($display_type) {
            case "1":
                $display_type = 'grid';
                break;
            case "2":
                $display_type = 'list';
                break;
            case "3":
                $display_type = 'first-post-grid';
                break;
            case "4":
                $display_type = 'first-post-list';
                break;
            case "5":
                $display_type = 'minimal';
                break;
            default:
                $display_type = $display_type;
        }

        // image style handler will be remove after data updater
        switch ($image_style) {
            case '':
                $image_style = '';
                break;
            case '2':
                $image_style = 'top-left';
                break;
            case '3':
                $image_style = 'top-right';
                break;
            default:
                $image_style = $image_style;
        }
    
        $templates = new \Void_Template_Loader;
    
        $grid_query= null;
    
        if ( get_query_var('paged') ) {
            $paged = get_query_var('paged');
        } elseif ( get_query_var('page') ) { // if is static front page
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }
    
        $args = [
            'post_type'      => $post_type,
            'meta_query'     => $meta_query,
            'cat'            => $cat_exclude,        // actually include or exclude both  
            'post_status'    => 'publish',
            'posts_per_page' => $posts, 
            'paged'          => $paged,   
            'tax_query'      => $tax_query,
            'orderby'        => $orderby,
            'order'          => $order,   //ASC / DESC
            'ignore_sticky_posts' => $sticky_ignore,
            'void_grid_query' => 'yes',
            'void_set_offset' => $offset,
        ];
    
        $grid_query = new \WP_Query( $args );
        
        global $post_count;
        $post_count = $posts;

        echo'<div class="elementor-shortcode">';

            ?>
            
            <div class="content-area void-grid">
                <div class="site-main <?php echo esc_html( $display_type . ' '. $image_style); ?>" >       
                <div class="void-row">       
                    <?php
                    if ( $grid_query->have_posts() ) : 
            
                        /* Start the Loop */
                    while ( $grid_query->have_posts() ) : $grid_query->the_post();  // Start of posts loop found posts
                        
                        $count++;
                        //$templates->get_template_part( 'content', $display_type );
            
                    endwhile; // End of posts loop found posts
                    // dummy for testing purpuse
                    $templates->get_template_part( 'content', 'dummy' );
            
                    if($pagination_yes==1) :  //Start of pagination condition 
                        global $wp_query;
                        $big = 999999999; // need an unlikely integer
                        $totalpages = $grid_query->max_num_pages;
                        $current = max(1,$paged );
                        $paginate_args = [
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))) ,
                            'format' => '?paged=%#%',
                            'current' => $current,
                            'total' => $totalpages,
                            'show_all' => False,
                            'end_size' => 1,
                            'mid_size' => 3,
                            'prev_next' => True,
                            'prev_text' => esc_html__('« Previous') ,
                            'next_text' => esc_html__('Next »') ,
                            'type' => 'plain',
                            'add_args' => False,
                        ];
            
                        $pagination = paginate_links($paginate_args); ?>
                        <div class="col-md-12">
                            <nav class='pagination wp-caption void-grid-nav'> 
                            <?php echo $pagination; ?>
                            </nav>
                        </div>
                    <?php endif; //end of pagination condition ?>
            
            
                    <?php else :   //if no posts found
            
                        $templates->get_template_part( 'content', 'none' );
            
                    endif; //end of post loop ?>  
            
                </div><!-- #main -->
                </div><!-- #primary -->
            </div>
            
            <?php
            wp_reset_postdata();    
		echo'</div>';
	}

}

// $current_url=esc_url("//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

// if( strpos( $current_url, 'action=elementor') == true ){
//     add_action( 'wp_footer', function() {

//         if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
//             return;
//         }
    
//         // load our jquery file that sends the $.post request
//         wp_enqueue_script( "void-grid-ajax", plugins_url('assets/js/void-ajax.js', dirname(__FILE__)) , array( 'jquery', 'json2' ) );
    
//         // make the ajaxurl var available to the above script
//         wp_localize_script(
//             'void-grid-ajax',
//             'void_grid_ajax',
//             [
//                 'ajaxurl'          => admin_url( 'admin-ajax.php' ),
//                 'postTypeNonce' => wp_create_nonce( 'voidgrid-post-type-nonce' ),
//             ] 
//         );
//     } );
// }



