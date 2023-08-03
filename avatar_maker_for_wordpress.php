<!-- 
This code is suitable for custom adding user avatars on WordPress websites.
It's a simple and fast way to allow users to upload and change avatars.
I used this code to create and change avatars from the WooCommerce user account, but it can work with any user account.
For this code to function, you'll need a custom field to store the image (I used the ACF plugin custom field and used ACF's update_field() function).
Replace the field slug ('user_avatar') with the slug of your custom field.
Additionally, the function resizes the image proportionally to 150*150px using the avatar_compressor() function.
You can modify this size according to your needs.
After uploading and compressing the image, only the 150150px avatar will remain in your media library, saving space on your server.
Good luck with your implementation! -->
 
 
 <!-- HTML PART (add this code to your personal area template) -->

<div class="woocomerce_personal_area--account_avatar">
	<label for="account_avatar" class="left_label avatar_label">Avatar</label>
	<section class="account_avatar_wrap">
	<?php 
		$user_id = get_current_user_id();
		if(get_field('user_avatar', 'user_' . $user_id)){
			$avatar_url = get_field('user_avatar', 'user_' . $user_id);
		}
		else {$avatar_url = get_avatar_url( $user_id );}

		echo '<img src="' . esc_url( $avatar_url ) . '" alt="" class="avatar-preview">';
	?>
	<label for="account_avatar" class="custom-file-upload">
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 40 40" fill="none">
		<path d="M4.99996 36.6654C4.0833 36.6654 3.2983 36.3387 2.64496 35.6854C1.99163 35.032 1.66552 34.2476 1.66663 33.332V13.332C1.66663 12.4154 1.9933 11.6304 2.64663 10.977C3.29996 10.3237 4.08441 9.99759 4.99996 9.9987H10.25L12.3333 7.7487C12.6389 7.41537 13.0072 7.15148 13.4383 6.95704C13.8694 6.76259 14.32 6.66537 14.79 6.66537H21.6666C22.1389 6.66537 22.535 6.82537 22.855 7.14537C23.175 7.46537 23.3344 7.86093 23.3333 8.33204V11.6654C23.3333 12.1376 23.4933 12.5337 23.8133 12.8537C24.1333 13.1737 24.5289 13.3331 25 13.332H28.3333V16.6654C28.3333 17.1376 28.4933 17.5337 28.8133 17.8537C29.1333 18.1737 29.5289 18.3331 30 18.332H33.3333C33.8055 18.332 34.2016 18.492 34.5216 18.812C34.8416 19.132 35.0011 19.5276 35 19.9987V33.332C35 34.2487 34.6733 35.0337 34.02 35.687C33.3666 36.3404 32.5822 36.6665 31.6666 36.6654H4.99996ZM18.3333 30.832C20.4166 30.832 22.1877 30.1026 23.6466 28.6437C25.1055 27.1848 25.8344 25.4143 25.8333 23.332C25.8333 21.2487 25.1039 19.4776 23.645 18.0187C22.1861 16.5598 20.4155 15.8309 18.3333 15.832C16.25 15.832 14.4789 16.5615 13.02 18.0204C11.5611 19.4793 10.8322 21.2498 10.8333 23.332C10.8333 25.4154 11.5627 27.1865 13.0216 28.6454C14.4805 30.1043 16.2511 30.8332 18.3333 30.832ZM31.6666 9.9987H30C29.5277 9.9987 29.1316 9.8387 28.8116 9.5187C28.4916 9.1987 28.3322 8.80315 28.3333 8.33204C28.3333 7.85982 28.4933 7.4637 28.8133 7.1437C29.1333 6.8237 29.5289 6.66426 30 6.66537H31.6666V4.9987C31.6666 4.52648 31.8266 4.13037 32.1466 3.81037C32.4666 3.49037 32.8622 3.33093 33.3333 3.33204C33.8055 3.33204 34.2016 3.49204 34.5216 3.81204C34.8416 4.13204 35.0011 4.52759 35 4.9987V6.66537H36.6666C37.1389 6.66537 37.535 6.82537 37.855 7.14537C38.175 7.46537 38.3344 7.86093 38.3333 8.33204C38.3333 8.80426 38.1733 9.20037 37.8533 9.52037C37.5333 9.84037 37.1377 9.99981 36.6666 9.9987H35V11.6654C35 12.1376 34.84 12.5337 34.52 12.8537C34.2 13.1737 33.8044 13.3331 33.3333 13.332C32.8611 13.332 32.465 13.172 32.145 12.852C31.825 12.532 31.6655 12.1365 31.6666 11.6654V9.9987Z" fill="#FBFBFB"/>
		</svg>
	</label>
	<span class="avatar_info">Update your avatar<br> file size maximum XXX mb<br>Format: .jpg, .jpeg, .png</span>
	<input type="file" name="account_avatar" id="account_avatar" class="<?php echo $user_id ?>">
	</section>
</div>	

<!-- END of HTML -->

<!-- JS part (add this code to your JS file or to personal area template) -->

<script>
function send_avatar() {
	$('#account_avatar').on('change', function() {
	var updating_span = $('<span>').addClass('updating').text('Updating.'); // Upload status
	if ($(".updating").length === 0) {
		$(this).after(updating_span);
	}else {
		$('.updating').text('Updating.');
	}

	const avatarClass = $('#account_avatar').attr('class');
	const avatarFile = $('#account_avatar')[0].files[0];

	const formData = new FormData();
	formData.append('action', 'avatar_upload');
	formData.append('value', avatarClass);
	formData.append('avatar_file', avatarFile);

	avatar_upload(formData);
	});

	function avatar_upload(formData) {
		$.ajax({
			type: 'POST',
			url: $ajax_url,
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				var avatar_img = $('.avatar-preview');// old avatar
				avatar_img.attr('src', response); // new one
				$('.updating').text('Your avatar is upload');
				console.log(response); // url in library
			},
			error: function(xhr, textStatus, errorThrown) {
				console.error('error in js');
				$('.updating').text('ERROR, please try again.');
			}
		});
	}        
} 				
</script>

<!-- END of JS -->

<!-- PHP part (add this code to your function.php) -->

<?php
// Avatar maker
function avatar_upload() {
    // POST data check
    if (isset($_POST['value']) && isset($_FILES['avatar_file'])) {
      $value = $_POST['value'];
      $avatar_file = $_FILES['avatar_file'];
  
      // Library img upload
      $upload_overrides = array('test_form' => false);
      $uploaded_file = wp_handle_upload($avatar_file, $upload_overrides);
  
      if ($uploaded_file && !isset($uploaded_file['error'])) {
        // IMG ID (attachment)
        $attachment_id = wp_insert_attachment(array(
          'post_mime_type' => $uploaded_file['type'],
          'post_title'     => preg_replace('/\.[^.]+$/', '', basename($avatar_file['name'])),
          'post_content'   => '',
          'post_status'    => 'inherit'
        ), $uploaded_file['file']);
  
        // LIbrary meta update
        $attach_data = wp_generate_attachment_metadata($attachment_id, $uploaded_file['file']);
        wp_update_attachment_metadata($attachment_id, $attach_data);
  
        // IMG compressing
        $compressed_image_data = avatar_compressor($attachment_id);
        $compressed_image_url = $compressed_image_data[0];
        update_field('user_avatar', $compressed_image_data[1], 'user_' . $value); // IMPORTANT! add your castom field name (I have "user_avatar" field in ACF plugin)
  
        // URL-answer
        echo $compressed_image_url;
      } else {
        // ERROR
        http_response_code(400);
        echo 'Library updating ERROR';
      }
    } else {
      // ERROR
      http_response_code(400);
      echo 'JS ERROR';
    }
  
    // END
    wp_die();
  }
  
  add_action('wp_ajax_avatar_upload', 'avatar_upload');
  add_action('wp_ajax_nopriv_avatar_upload', 'avatar_upload');

  function avatar_compressor($attachment_id) {
    // Get meta data
    $attachment_data = wp_get_attachment_metadata($attachment_id);

    // Check img size
    $original_image_path = get_attached_file($attachment_id);
    $original_image_width = $attachment_data['width'];
    $original_image_height = $attachment_data['height'];

    // NEW SIZE!!!
	// (add your size if you need it (now its 150*150px))
    $new_image_width = ($original_image_width / $original_image_height) * 150;
    $new_image_height = 150;

    // copy img
    $original_image = imagecreatefromjpeg($original_image_path);

    // making new img
    $new_image = imagecreatetruecolor($new_image_width, $new_image_height);

    // change old img and new one
    imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_image_width, $new_image_height, $original_image_width, $original_image_height);

    // making new part
    $upload_dir = wp_upload_dir();
    $new_image_filename = $upload_dir['path'] . '/' . basename($original_image_path) . '-avatar.jpg';

    // SAVING
    imagejpeg($new_image, $new_image_filename);

    // чистим память и удаляем старый img
    imagedestroy($original_image);
    imagedestroy($new_image);
    wp_delete_attachment($attachment_id, true);

    // URL & attachment ID 
    $new_attachment_id = wp_insert_attachment(array(
        'guid' => $upload_dir['url'] . '/' . basename($new_image_filename),
        'post_mime_type' => 'image/jpeg',
        'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $new_image_filename ) ),
        'post_content' => '',
        'post_status' => 'inherit'
    ), $new_image_filename);

    return array($upload_dir['url'] . '/' . basename($new_image_filename), $new_attachment_id);

    die();
}

add_action('wp_ajax_add_files_rooms', 'avatar_compressor');
add_action('wp_ajax_nopriv_add_files_rooms', 'avatar_compressor');

?>