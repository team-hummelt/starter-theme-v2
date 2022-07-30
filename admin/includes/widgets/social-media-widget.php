<?php
defined( 'ABSPATH' ) or die();

/**
 * Hupa THEME Social Media Widget
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class HupaSocialMediaWidget extends WP_Widget {

	/**
	 * Constructs the new widget.
	 *
	 * @see WP_Widget::__construct()
	 */
	function __construct() {
		// Instantiate the parent object.
		parent::__construct( false, __( 'Hupa Social Media', 'bootscore' ) );
	}

	/**
	 * The widget's HTML output.
	 *
	 * @param array $args Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 */
	function widget( $args, $instance ) {
        global $post;
		$args     = (object) $args;
		$instance = (object) $instance;
		$header   = empty( $instance->header ) ? ' ' : apply_filters( 'widget_title', $instance->header );
		$cssClass = empty( $instance->cssClass ) ? '' : $instance->cssClass;
		$isColor  = empty( $instance->isColor ) ? '' : $instance->isColor;
		$type = empty( $instance->type ) ? '' : $instance->type;
        switch($type){
            case '1':
                $btnId = 'share-symbol';
                break;
            case '2':
                $btnId = 'share-buttons';
                break;
            default:
                $btnId = '';
        }

        $shareData = new stdClass();
        $shareData->share_url = urlencode(get_permalink());
        isset($post->ID) ? $postID = $post->ID : $postID = '';
        $metaTitle = get_post_meta( $postID , '_hupa_custom_title', true);
        if($metaTitle){
            $shareData->share_title = $metaTitle;
        } else {
            $shareData->share_title = str_replace( ' ', '%20', get_the_title());
        }

		!$isColor && $btnId == 'share-symbol'  ? $color = 'gray' : $color = '';
		echo( $args->before_widget ?? '' );
		echo $args->before_title . $header . $args->after_title;

		$media = apply_filters( 'get_social_media', '' );
		$html  = '<div id="'.$btnId.'" class="d-flex flex-wrap">';
		foreach ( $media->record as $tmp ) {
			if ( ! $tmp->top_check ) {
				continue;
			}
            $tmp->share_txt ? $shareData->share_subject = $tmp->share_txt : $shareData->share_subject = __( 'Look what I found:', 'bootscore' );
            $shareData->btn = $tmp->btn;
            $url = apply_filters('get_social_button_url', $shareData);
			$tmp->slug === 'print_' ? $href = 'javascript:;" onclick="window.print()' : $href = $url;
			$html .= '<a class="btn-widget  '.  $tmp->btn . ' '.$color. ' '.$cssClass. ' " title="' . $tmp->bezeichnung . '" href="' . $href . '" target="_blank" rel="nofollow"><i class="' . $tmp->icon . '"></i></a> ';
		}
		$html .= '</div>';
		echo $html;
		echo( $args->after_widget ?? '' );
	}

	/**
	 * The widget update handler.
	 *
	 * @param array $new_instance The new instance of the widget.
	 * @param array $old_instance The old instance of the widget.
	 *
	 * @return array The updated instance of the widget.
	 * @see WP_Widget::update()
	 *
	 */
	function update( $new_instance, $old_instance ): array {

		$instance             = $old_instance;
		$instance['header']   = $new_instance['header'];
		$instance['cssClass'] = $new_instance['cssClass'];
		$instance['isColor']  = $new_instance['isColor'];
		$instance['type']     = $new_instance['type'];
		return $instance;
	}

	/**
	 * Output the admin widget options form HTML.
	 *
	 * @param array $instance The current widget settings.
	 *
	 * @return void The HTML markup for the form.
	 */
	function form( $instance ): void {
			$instance = wp_parse_args( (array) $instance, array(
				'title' => __( 'Social Media', 'bootscore' ),
			) );

        isset($instance['header']) && !empty($instance['header']) ? $header =  filter_var( $instance['header'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH ) : $header = __( 'Social Media', 'bootscore' );
        isset($instance['cssClass']) && !empty($instance['cssClass']) ? $cssClass =  filter_var( $instance['cssClass'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH ) : $cssClass = '';
        isset($instance['isColor']) && !empty($instance['isColor']) ? $isColor =  filter_var( $instance['isColor'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH ) : $isColor = '';
        isset($instance['type']) && !empty($instance['type']) ? $type = (int) filter_var( $instance['type'], FILTER_SANITIZE_NUMBER_INT ) : $type = 1;
		?>

        <p>
            <label for="<?= $this->get_field_id( 'header' ); ?>"><?= __( 'Title', 'bootscore' ) ?>
                <input class="widefat" id="<?= $this->get_field_id( 'header' ); ?>"
                       name="<?= $this->get_field_name( 'header' ); ?>" type="text"
                       value="<?= esc_attr( $header ); ?>"/>
            </label>
        </p>
        <hr>
        <div>
            <p>
                <input type="radio" id="<?=( $this->get_field_id( 'type' ) . '-1' ) ?>"
                       name="<?=( $this->get_field_name( 'type' ) ) ?>"
                       value="1" <?php checked( true, $type == 1 ) ?>>
                <label for="<?=( $this->get_field_id( 'type' ) . '-1' ) ?>"><?= __( 'Show icon', 'bootscore' ) ?></label>
                &nbsp;
                <input type="radio" id="<?=( $this->get_field_id( 'type' ) . '-2' ) ?>"
                       name="<?=( $this->get_field_name( 'type' ) ) ?>"
                       value="2" <?php checked( true, $type == 2 ) ?>>
                <label for="<?=( $this->get_field_id( 'type' ) . '-2' ) ?>"><?= __( 'Show button', 'bootscore' ) ?></label>
            </p>
        </div>
        <hr/>
        <p>
            <input id="<?= esc_attr( $this->get_field_id( 'isColor' ) ); ?>"
                   name="<?= esc_attr( $this->get_field_name( 'isColor' ) ); ?>"
                   type="checkbox" value="1" <?php checked( '1', $isColor ); ?> />
            <label for="<?= esc_attr( $this->get_field_id( 'isColor' ) ); ?>"><?= __( 'Show coloured symbols', 'bootscore' ) ?>
            </label>
        </p>
        <hr/>
        <p>
            <label for="<?= $this->get_field_id( 'cssClass' ); ?>"><?= __( 'Add extra CSS class', 'bootscore' ) ?>
                <input class="widefat" id="<?= $this->get_field_id( 'cssClass' ); ?>"
                       name="<?= $this->get_field_name( 'cssClass' ); ?>" type="text"
                       value="<?= esc_attr( $cssClass ); ?>"/>
            </label>
        </p>
        <hr>
		<?php
	}
}

add_action( 'widgets_init', 'hupa_register_social_media_widget' );
/**
 * Register the new widget.
 *
 * @see 'widgets_init'
 */
function hupa_register_social_media_widget(): void {
	register_widget( 'HupaSocialMediaWidget' );
}


