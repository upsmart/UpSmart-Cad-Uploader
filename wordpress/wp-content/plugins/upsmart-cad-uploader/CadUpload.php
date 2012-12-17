<?PHP

DEFINE( 'UPSMART_CAD_SCRIPTS_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) . '/conversion.scripts' );

Class CadUpload{
	
	/* Need to change these to use wordpress base links */
	private $STL_Uploads_Dir = "/home/aaron/Project-UpSmart/wordpress/wp-content/cad/";
	private $Conversion_Script = "/home/aaron/Project-UpSmart/wordpress/wp-content/plugins/upsmart-cad-uploader/conversion.script/";
	
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
		/**/
		// A proper stl file is submitted by validating mime type extension
		if(!preg_match("/^.*\.(stl)$/i", $fullFileName)){	
			return $upload_error_handler( $file, __( 'Specified file does not exist.' ));
		}
			
        echo'
		<tr>
			<th scope="row">File Validation</th>
			<td>Is uploaded file a valid stl file</td>
			<td><span class="check"></span></td>
		</tr>
        ';	

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

        echo '
		<tr>
			<th scope="row">File Transfer</th>
			<td>Stl file is being moved to temp directory</td>
			<td><span class="check"></span></td>
		</tr>
        ';
 
        // Create x3d file from stl file
        $nameOfFile = strstr($filename, '.', true);
        
		$output = shell_exec('blender -b -P ' . $this->Conversion_Script . 'cad.import.py -- ' . $nameOfFile . ' ' . $this->STL_Uploads_Dir);

        echo '
		<tr>
			<th scope="row">STL Conversion</th>
			<td>Stl file is being converted to xdom format</td>
			<td><span class="check"></span></td>
		</tr>
        ';
               
        $x3dFile = $nameOfFile . ".x3d";
        $htmlFile = $nameOfFile . ".html";
		
		// Create x3d file from stl file
		$output = shell_exec('aopt -i ' . $this->STL_Uploads_Dir . $x3dFile  . ' -N ' . $this->STL_Uploads_Dir . $htmlFile);

        echo '
		<tr>
			<th scope="row">X3D Conversion</th>
			<td>X3Dom file is being converted to embeddable WebGL</td>
			<td><span class="check"></span></td>
		</tr>
        ';	
		
		$htmlFilePath = $this->STL_Uploads_Dir . $htmlFile;
	
		// Replace stl file with html file
		$file['name'] =  $htmlFile;
		$file['tmp_name'] = $htmlFilePath;
		$file['type'] = 'text/html';
		$file['error'] = 0;
		$file['size'] = filesize($htmlFilePath);
		
		 /**/
		//Move the file to the uploads directory, returns an array
		$uploaded_file = $this->handleUpload($file, $uploads);
			
        echo '
		<tr>
			<th scope="row">File Transfer</th>
			<td>WebGL is being moved to wordpress uploads directory</td>
			<td><span class="check"></span></td>
		</tr>
        ';
	    		
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
        
        echo '
		<tr>
			<th scope="row">Media Library</th>
			<td>WebGL file is registered with wordpress media library</td>
			<td><span class="check"></span></td>
		</tr>
        ';
        
        return $attach_id;
		
	}
	
	public function handleUpload( $file=array(), $uploads = array() ){
		/*
		require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
		return wp_handle_upload($file, array( 'test_form' => false , 'test_upload' => false), date('Y/m') );
		*/

		// Move the file to the uploads dir
		$filename = $file['name'];
		$type = $file['type'];
		$new_file = $uploads['path'] . "/$filename";
		
		echo $new_file . "  vs  " . $file['tmp_name'];
		if ( false === @copy( $file['tmp_name'], $new_file ) )
			return $upload_error_handler( $file, sprintf( __('The uploaded file could not be moved to %s.' ), $uploads['path'] ) );

		unlink($file['tmp_name']);
		
		// Set correct file permissions
		$stat = stat( dirname( $new_file ));
		$perms = $stat['mode'] & 0000666;
		@ chmod( $new_file, $perms );

		// Compute the URL
		$url = $uploads['url'] . "/$filename";

		if ( is_multisite() )
			delete_transient( 'dirsize_cache' );

		return apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ), 'upload' );
			
	
	}
	
	public function buildGuid( $file=null ){
		$wp_upload_dir = wp_upload_dir();
		return $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $file );
	}
	
}

?>
