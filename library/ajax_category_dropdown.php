<?php

require( "../../../../wp-load.php");

/* get post type */
$post_type = $_REQUEST['post_type'];
$taxonomies = get_taxonomies( array( 'public' => true,'object_type' =>array($post_type)  ), 'objects' );
$var ='';
$args =array();
$terms = get_terms( $taxonomies[0], $args );

		  		foreach ( $taxonomies as $taxonomy ) {	
							$query_label = '';
							if ( !empty( $taxonomy->query_var ) )
								$query_label = $taxonomy->query_var;
							else
								$query_label = $taxonomy->name;
							
							if($taxonomy->labels->name!='Tags' && $taxonomy->labels->name!='Format' && !strstr($taxonomy->labels->name,'tag') && !strstr($taxonomy->labels->name,'Tags') && !strstr($taxonomy->labels->name,'format') && !strstr($taxonomy->labels->name,'Shipping Classes')&& !strstr($taxonomy->labels->name,'Order statuses')&& !strstr($taxonomy->labels->name,'genre')&& !strstr($taxonomy->labels->name,'platform') && !strstr($taxonomy->labels->name,'colour') && !strstr($taxonomy->labels->name,'size')):	
								?>
        <optgroup label="<?php echo esc_attr( $taxonomy->object_type[0])."-".esc_attr($taxonomy->labels->name); ?>">
        <?php
												$terms = get_terms( $taxonomy->name, 'orderby=name&hide_empty=0' );
												foreach ( $terms as $term ) {		
												$term_value=esc_attr($taxonomy->object_type[0]). ',' .$term->term_id.','.$query_label;
							?>
        <option style="margin-left: 8px; padding-right:10px;" value="<?php echo $term_value ?>" <?php if(isset($term_value) && !empty($post_type) && in_array(trim($term_value),$post_type)) echo "selected";?>><?php echo '-' . esc_attr( $term->name ); ?></option>
        <?php } ?>
        </optgroup>
        <?php
								endif;
						}
exit;
?>
