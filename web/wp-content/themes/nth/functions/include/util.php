<?php

/**
 * Outputs a table for custom post meta fields
 * @param  array $fields 	Array of custom fields
 * @param  Object $post   	Post object
 * @return string         	Returns markup to be printed/echoed
 */
function util_output_fields_table($fields, $post, $nonce, $name='custom_meta_box')
{

	$builder = '';

	$builder .= '<input type="hidden" name="'.$name.'_nonce" value="'.$nonce.'" />';  
      
    // Begin the field table and loop  
    $builder .= '<table class="form-table">';  
    foreach ($fields as $field) {  
        // get value of this field if it exists for this post  
        $meta = get_post_meta($post->ID, $field['id'], true);  
        // begin a table row with  
        $builder .= '<tr> 
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
                <td>';  
                switch($field['type']) {  
                    
					case 'text':
						$builder .= '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
						break;

					case 'checkbox':
						$builder .= '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'"' . ($meta ? ' checked="checked"' : '') . ' />
							<label for="'.$field['id'].'">'.$field['desc'].'</label>';
						break;

					case 'checkbox_group':
						foreach ($field['options'] as $option) {
							$builder .= '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"' . ($meta && in_array($option['value'], $meta) ? ' checked="checked"' : '') . ' /> 
									<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						}
						$builder .= '<span class="description">'.$field['desc'].'</span>';
						break;  

					case 'date':
						$builder .= '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="15" />
							<br /><span class="description">'.$field['desc'].'</span>';
						break;

					case 'textarea':
						$builder .= '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
						break;

					case 'select':
						$builder .= '<select name="'.$field['id'].'" id="'.$field['id'].'">';
						foreach ($field['options'] as $option) {
							$builder .= '<option' . ($meta == $option['value'] ? ' selected="selected"' : '') . ' value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						$builder .= '</select><br /><span class="description">'.$field['desc'].'</span>';
						break;

					case 'radio':
						foreach ( $field['options'] as $option ) {  
					        $builder .= '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ' . ($meta == $option['value'] ? ' checked="checked"' : '') . ' /> 
					                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';  
					    } 
					    break;

					case 'image':
						$image = get_template_directory_uri().'/images/image.png';	
						$builder .= '<span class="portfolio_default_image" style="display:none">'.$image.'</span>';
						if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }				
						$builder .= '<input name="'.$field['id'].'" type="hidden" class="portfolio_upload_image" value="'.$meta.'" />
									 <img src="'.$image.'" class="portfolio_preview_image" alt="" /><br />
									 <input class="portfolio_upload_image_button button" type="button" value="Choose Image" />
									 <small> <a href="#" class="portfolio_clear_image_button">Remove Image</a></small>
									 <br clear="all" /><span class="description">'.$field['desc'].'</span>';
						break;

					case 'post':

						$args = array(
							'numberposts' => -1,
							'post_type' => $field['post-type']
						);
						$the_query = get_posts( $args );

						if ( 0 != count($the_query) ) {
							$builder .= '<select name="'.$field['id'].'" id="'.$field['id'].'">';
							foreach ( $the_query as $p ) {

					    		$builder .= '<option' . ($meta == $p->ID ? ' selected="selected"' : '') . ' value="'.$p->ID.'">'.$p->post_title.'</option>';

							}
							$builder .= '</select><br /><span class="description">'.$field['desc'].'</span>';
						}

						break;

					default:
						break;

                } //end switch  
        $builder .= '</td></tr>';  
    } // end foreach  
    $builder .= '</table>'; // end table  

    return $builder;

} 

// Datepicker
// Implemented in $THEME/admin/post-type/job-position.js
// 
/**
 * Register datepicker scripts for administration interface 
 */
function register_datepicker_scripts() {
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'jquery-ui-datepicker', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css' );
} // end register_datepicker_scripts

add_action( 'admin_enqueue_scripts', 'register_datepicker_scripts');