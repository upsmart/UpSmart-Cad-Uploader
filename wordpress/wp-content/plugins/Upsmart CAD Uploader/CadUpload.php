<?PHP

DEFINE( 'UPSMART_CAD_SCRIPTS_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) . '/conversion.scripts' );

Class CadUpload{
	
	public function _construct(){}
	
	public function saveUpload($field_name = null, $user_id=null){
		
		if(is_null($field_name))
			die("Need CAD field name");
		
		//blender -b -P cad.import.py -- 'KAPPA'
		
		//convert file to X3Dom
		//aopt -i KAPPA.x3d -N KAPPA.html
		
		//$output = shell_exec('ls -l ./conversion.script');
		if(!preg_match("/^.*\.(stl)$/i", $_FILES[$field_name]['name'])){
			echo "Script is a invalid stl file...";
			echo "Terminating...";
			return false;
		}
		
		echo "Script is a valid stl file...<br/>";
		//Current pwd ==> /home/aaron/Project-UpSmart/wordpress/wp-admin
		$fileName = strstr($_FILES[$field_name]['name'], '.', true);
		//$output = shell_exec('cd ../wp-content/plugins/Upsmart\ CAD\ Uploader/conversion.script/');
		
		print_r($output);
		$output = shell_exec('pwd');
		//$output = shell_exec('blender -b -P ../wp-content/plugins/Upsmart\ CAD\ Uploader/conversion.scripts/cad.import.py -- ' .$fileName);
        print_r($output);
        echo "<br/>";
		print_r($_FILES[$field_name]);		
		//$FILES[ $field_name]['type']
		
		//Move the file to the uploads directory, returns an array
		//$uploaded_file = $this->handleUpload($_FILES[$field_name] );
			
	    //print_r($uploaded_file);		
		if(!isset($uploaded_file['error']) && isset($uploaded_file['file'])) {
            $filetype   = wp_check_filetype(basename($uploaded_file['file']), null);
            $title      = $_FILES[ $field_name]['name'];
            $ext        = strrchr($title, '.');
            $title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
		}
		else
			return false;
		
		//If we were to have a unique user account for uploading
		if(is_null($user_id) ){
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
		}
		
		//build global unique identifier 
		$guid = $this->buildGuid( $uploaded_file['file'] );
		
		//Build our array of data to be inserted as a post
		$attachment = array(
			'post_mime_type' => $_FILES[ $field_name]['type'],
			'guid' => $guid,
			'post_title' => addslashes($title),
			'post_content' => '',
			'post_author' => $user_id,
			'post_Status' => 'inherit',
			'post_date' => date( 'Y-m-d H:i:s' ),
			'post_date_gmt' => date( 'Y-m-d H:i:s' )
		);
		
		
		//Add the file to the media library
		$attach_id = wp_insert_attachment( $attachment, $uploaded_file['file'] );
		$meta = wp_generate_attachment_metadata($attach_id, $uploaded_file['file']  );

        $upload_feedback = false;
        
        return $attach_id;
		
	}
	
	public function handleUpload( $file=array() ){
		require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
		return wp_handle_upload($file, array( 'test_form' => false), date('Y/m') );
	}
	
	public function buildGuid( $file=null ){
		$wp_upload_dir = wp_upload_dir();
		return $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $file );
	}
	
}

?>
