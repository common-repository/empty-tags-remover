<?php
/*
Plugin Name: Empty Tags Remover
Plugin URI: http://deceblog.net/
Description: Removes the empty tags, tags with no posts attached.
Version: 1.0
Author: Dan - Lucian Stefancu
Author URI: http://deceblog.net/
*/ 

add_action('admin_menu', 'add_empty_tags_page'); // adds the options page

function add_empty_tags_page() {
	if (function_exists('add_posts_page')) {
		add_posts_page('Remove empty tags', 'Remove empty tags', 8, __FILE__, 'empty_tags_page');
	}
}

function empty_tags_page() { ?>

<div class="wrap">
	<h2>Remove Empty Tags</h2>

<?php
	if(empty($_POST['remove_my_empty_tags'])) {
		
		$args = array('offset' => 0, 'number' => 0, 'hide_empty' => 0);
		
		$tags = get_terms( 'post_tag', $args );
		
		$count_tags = 0;
		echo '<strong>The following tags are empty:</strong><br />';
		echo '<ul>';
		foreach ($tags as $tag) {
			if ($tag->count == 0) {
				echo "<li><a href='edit-tags.php?action=edit&amp;taxonomy=post_tag&amp;tag_ID=$tag->term_id'>" . $tag->name . "</a></li>\n";
				$count_tags++;
			}
		}
		if ($count_tags == 0) { echo '<li>No empty tags found!</li>'; }
		echo '</ul>';
		if ($count_tags > 0) {
			?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo plugin_basename(__FILE__); ?>">
				<label for="remove_my_empty_tags">Do you want to remove now all the empty tags?</label> <input type="submit" name="remove_my_empty_tags" value="Yes" />
			</form>
			<?php
		} // if some empty tags
	} else {
			$args = array('offset' => 0, 'number' => 0, 'hide_empty' => 0);
			
			$tags = get_terms( 'post_tag', $args );
			$count_tags = 0;
			echo '<ul>';
			foreach ($tags as $tag) {
				if ($tag->count == 0) {
					wp_delete_term($tag->term_id,'post_tag');
					echo "<li>" . $tag->name . " deleted</li>";
					$count_tags++;
				}
			}
			echo '</ul>';
			echo '<strong>' . $count_tags . " empty tag(s) deleted</strong>";
		}
?>
</div><!-- //wrap -->
<?php } // function ?>