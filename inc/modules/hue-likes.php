<?php
// The 'likes' meta key value will store the total like count for a specific post, it'll show 0 if it's an empty string
	$likes = get_post_meta($post->ID, "likes", true);
	$likes = ($likes == "") ? 0 : $likes;
?>

This post has <span id='like_counter'><?php echo $likes ?></span> likes<br>

<?php
// Linking to the admin-ajax.php file. Nonce check included for extra security. Note the "user_like" class for JS enabled clients.
	$nonce = wp_create_nonce("my_user_like_nonce");
	$link = admin_url('admin-ajax.php?action=my_user_like&post_id='.$post->ID.'&nonce='.$nonce);
	echo '<a class="user_like" data-nonce="' . $nonce . '" data-post_id="' . $post->ID . '" href="' . $link . '">Like this Post</a>';
// End 'likes'
?>