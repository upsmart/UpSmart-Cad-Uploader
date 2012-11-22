<?PHP

DEFINE( 'UPSMART_CAD_SCRIPTS_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) . '/conversion.scripts' );

Class CadUpload{
	
	private $STL_Uploads_Dir = "/home/aaron/Project-UpSmart/wordpress/wp-content/cad/";
	private $Conversion_Script = "/home/aaron/Project-UpSmart/wordpress/wp-content/plugins/Upsmart\ CAD\ Uploader/conversion.script/";
	
	public function _construct(){}
	
	public function saveUpload($field_name = null, $user_id=null){	
		
		// The default error handler.
		if ( ! function_exists( 'wp_handle_upload_error' ) ) {
			function wp_handle_upload_error( &$file, $message ) {
				return array( 'error'=>$message );
			}
		}
	
		$upload_error_handler = 'wp_handle_upload_error';
			
		//From PHP.net
		$upload_error_strings = array( false,
		__( "The uploaded file exceeds the <code>upload_max_filesize</code> directive in <code>php.ini</code>." ),
		__( "The uploaded file exceeds the <em>MAX_FILE_SIZE</em> directive that was specified in the HTML form." ),
		__( "The uploaded file was only partially uploaded." ),
		__( "No file was uploaded." ),
		'',
		__( "Missing a temporary folder." ),
		__( "Failed to write file to disk." ),
		__( "File upload stopped by extension." ));
			
		if(is_null($field_name))
			die("Need CAD field name");
			
		$file = $_FILES[$field_name];
			
		// A successful upload will pass this test. It makes no sense to override this one.
		if ( ! empty( $file['error'] ) )
			return $upload_error_handler( $file, $upload_error_strings[$file['error']] );

		// A non-empty file will pass this test.
		if (!(filesize($file['tmp_name']) > 0 ) )
			return $upload_error_handler( $file, __( 'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini.' ));

		// A properly uploaded file will pass this test. There should be no reason to override this one.
		if (! @ is_file( $file['tmp_name'] ) )
			return $upload_error_handler( $file, __( 'Specified file does not exist.' ));

		$fullFileName =$file['name'];
		
		// A proper stl file is submitted by validating mime type extension
		if(!preg_match("/^.*\.(stl)$/i", $fullFileName)){	
			return $upload_error_handler( $file, __( 'Specified file does not exist.' ));
		}
			
		echo "Script is a valid stl file...<br/>";

		//blender -b -P cad.import.py -- 'KAPPA'
		// /home/aaron/www/wp-content/plugins/KAPPA.stl
		//convert file to X3Dom
		//aopt -i KAPPA.x3d -N KAPPA.html
		//Current pwd ==> /home/aaron/Project-UpSmart/wordpress/wp-admin
		
		// A writable uploads dir will pass this test. Again, there's no point overriding this one.
		if ( ! ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) )
			return $upload_error_handler( $file, $uploads['error'] );
		
	    $filename = wp_unique_filename( $this->STL_Uploads_Dir, $fullFileName, null );
        $new_file = $this->STL_Uploads_Dir. "$filename";

		if ( false === @move_uploaded_file( $file['tmp_name'], $new_file ) )
			return $upload_error_handler( $file, sprintf( __('The uploaded file could not be moved to %s.' ), $this->STL_Uploads_Dir) );

        echo "Stl file moved to...<br/>$this->STL_Uploads_Dir";
        echo "<br/><br/>";
 
        // Create x3d file from stl file
        $nameOfFile = strstr($filename, '.', true);
        
        echo "Executing stl to xdom conversion script...<br/>";
        echo "using >> " . 'blender -b -P ' . $this->Conversion_Script . 'cad.import.py -- ' . $nameOfFile;
		echo "<br/><br/>";
		$output = shell_exec('blender -b -P ' . $this->Conversion_Script . 'cad.import.py -- ' . $nameOfFile . ' ' . $this->STL_Uploads_Dir);
        print_r($output);
        echo "<br/><br/>";	
               
        $x3dFile = $nameOfFile . ".x3d";
        $htmlFile = $nameOfFile . ".html";
		
		echo "Executing xdom to html conversion script...<br/>";
		// Create x3d file from stl file
		$output = shell_exec('aopt -i ' . $this->STL_Uploads_Dir . $x3dFile  . ' -N ' . $this->STL_Uploads_Dir . $htmlFile);
        print_r($output);
        echo "No errors...<br/><br/>";	
		
		$htmlFilePath = $this->STL_Uploads_Dir . $htmlFile;
	    // Set correct file permissions
		$stat = stat( dirname($htmlFilePath));
		$perms = $stat['mode'] & 0000666;
		@chmod( $htmlFilePath, $perms );
	
		// Replace stl file with html file
		$file['name'] =  $htmlFile;
		$file['tmp_name'] = $htmlFilePath;
		$file['type'] = 'text/html';
		$file['error'] = 0;
		$file['size'] = filesize($htmlFilePath);
		
		print_r($htmlFilePath);
		 echo "<br/>";	
		//Move the file to the uploads directory, returns an array
		$uploaded_file = $this->handleUpload($file);
			
	    print_r($uploaded_file);
	    		
		if(!isset($uploaded_file['error']) && isset($uploaded_file['file'])) {
            $filetype   = wp_check_filetype(basename($uploaded_file['file']), null);
            $title      = $file['name'];
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
			'post_mime_type' => $file['type'],
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
		return wp_handle_upload($file, array( 'test_form' => false , 'test_upload' => false), date('Y/m') );
	}
	
	public function buildGuid( $file=null ){
		$wp_upload_dir = wp_upload_dir();
		return $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $file );
	}
	
}

?>
