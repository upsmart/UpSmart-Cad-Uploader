<?PHP
		
Class CadUpload{

	private $plugin_url;
	private $STL_Uploads_Dir;
	private $Conversion_Script;
	private $upload_error_handler;
	
	private $upload_error_strings;
		
	public function __construct(){
		$this->plugin_url = trailingslashit( plugin_dir_path(__FILE__) ); 
		$this->STL_Uploads_Dir = $this->plugin_url . 'scratch/';
		$this->Conversion_Script = $this->plugin_url. 'conversion.script/';
		
		// The default error handler.
		if ( ! function_exists( 'wp_handle_upload_error' ) ) {
			function wp_handle_upload_error( &$file, $message ) {
				return array( 'error'=>$message );
			}
		}

		$this->upload_error_handler = 'wp_handle_upload_error';
		
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
	}
	
	private function startResponseTable(){
		echo '	
		<table class="result-table">
			<thead>
				<tr>
					<th></th>
					<th scope="col" abbr="Details">Message</th>
					<th scope="col" abbr="Status">Status</th>
				</tr>
			</thead>
			<tbody>
        ';
	}
	
	private function addResponseRow($title, $desc, $success=false){
		
		$row = '<tr>';
		$row.= '<th scope="row">' . $title .'</th>';
		$row.= '<td>'. $desc . '</td>';
		$row.= '<td><span class="' . ($success ? 'check' : 'error') . '"></span></td>';
		$row.= '</tr>';
		
		echo $row;
		
		if(!$success)
			$this->endResponseTable(false);
       
	}
	
	private function endResponseTable($done=true){
	
		if( ! $done)
			$this->addResponseRow("Error Occured!", "Please Try Again", true);
		echo '</tbody></table>';	
	}

	private function handle_upload( $file = array(), $uploads = array() ){

		// Move the file to the uploads dir
		$filename = $file['name'];
		$type = $file['type'];
		$new_file = $uploads['path'] . "/$filename";
		
		if ( false === @copy( $file['tmp_name'], $new_file ) )
			return $this->addResponseRow(__('File Transfer'), sprintf( __('The uploaded file could not be moved to %s.' ), $uploads['path']));
		
		// Set correct file permissions
		$stat = stat( dirname( $new_file ));
		$perms = $stat['mode'] & 0000666;
		@chmod( $new_file, $perms );

		// Compute the URL
		$url = $uploads['url'] . "/$filename";

		if ( is_multisite() )
			delete_transient( 'dirsize_cache' );

		return apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ), 'upload' );
	}
	
	public function build_guid( $file = null ){
		$wp_upload_dir = wp_upload_dir();
		return $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path( $file );
	}
	
	function cleanCAD_ScratchSpace_Directory(){		
		$output = shell_exec("rm -rf " . $this->STL_Uploads_Dir . "*");
	}
	
	public function saveUpload($field_name = null, $user_id=null){	
		
		$this->startResponseTable();

		// Clean directory in case it was corrupted or previous upload process exited prematurely
		$this->cleanCAD_ScratchSpace_Directory();
			
		if(is_null($field_name))
			return $this->addResponseRow(__('Form Error'), __('upload form field name was not recognized'));
			
		$file = $_FILES[$field_name];

		// A successful upload will pass this test. It makes no sense to override this one.
		if ( ! empty( $file['error'] ) )
			return $upload_error_handler( $file, $upload_error_strings[$file['error']] );

		// A non-empty file will pass this test.
		if (!(filesize($file['tmp_name']) > 0 ) )
			return $this->addResponseRow(__('File Empty'), 'Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini.');
		// A properly uploaded file will pass this test. There should be no reason to override this one.
		if (! @ is_file( $file['tmp_name'] ) )
			return $this->addResponseRow(__('File Validation'), __('specified file does not exist'));

		$fullFileName =$file['name'];
		
		
		// A proper stl file is submitted by validating mime type extension
		if(!preg_match("/^.*\.(stl)$/i", $fullFileName))	
			return $this->addResponseRow(__('File Validation'), __('file uploaded is not a valid stl file'));
			
		$this->addResponseRow(__('File Validation'), __('Is uploaded file a valid stl file'), true);

		
		// A writable uploads dir will pass this test. Again, there's no point overriding this one.
		if ( ! ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) )
			return $this->upload_error_handler( $file, $uploads['error'] );
			
		
		$nameOfFile = strstr($fullFileName, '.', true);
		$htmlEquivelant = $nameOfFile . '.html';
	    $filename = wp_unique_filename( $uploads['path'], $htmlEquivelant, null );
	    
        $new_file = $this->STL_Uploads_Dir. strstr($filename, '.', true) . '.stl';

		if ( false === @move_uploaded_file( $file['tmp_name'], $new_file ) )
			return $this->addResponseRow(__('File Transfer'), sprintf( __('The uploaded file could not be moved to %s' ), $this->STL_Uploads_Dir));

		$this->addResponseRow(__('File Transfer'), __('Stl file is being moved to temp directory'), true);

 	
        // Create x3d file from stl file
        $nameOfFile = strstr($filename, '.', true);
        
		$output = shell_exec('/opt/blender-2.65a-linux-glibc27-i686/blender -b -P ' . $this->Conversion_Script . 'cad.import.py -- ' . $nameOfFile . ' ' . $this->STL_Uploads_Dir);
		
		
		if(stripos($output, "finished X3D export") === false){
			return $this->addResponseRow(__('STL Conversion'), sprintf( __('%s.' ), $output));
		}
		$this->addResponseRow(__('STL Conversion'), __('Stl file conversion to xdom format'), true);

               
        $x3dFile = $nameOfFile . ".x3d";
        $htmlFile = $nameOfFile . ".html";
		
		// Create x3d file from stl file
		$output = shell_exec('aopt -i ' . $this->STL_Uploads_Dir . $x3dFile  . ' -N ' . $this->STL_Uploads_Dir . $htmlFile);

		if(!file_exists($this->STL_Uploads_Dir . $htmlFile)){
			return $this->addResponseRow(__('X3D Conversion'), __('X3Dom file is being converted to embeddable WebGL'));	
		}
		
		$this->addResponseRow(__('X3D Conversion'), __('X3Dom file is being converted to embeddable WebGL'), true);

		$htmlFilePath = $this->STL_Uploads_Dir . $htmlFile;
	
		// Replace stl file with html file
		$file['name'] =  $htmlFile;
		$file['tmp_name'] = $htmlFilePath;
		$file['type'] = 'text/html';
		$file['error'] = 0;
		$file['size'] = @filesize($htmlFilePath);
		
		// Set correct file permissions
		$stat = stat( dirname( $htmlFilePath ));
		$perms = $stat['mode'] & 0000666;
		@chmod( $htmlFilePath, $perms );
		
		if($file['size']  == 0)
			return $this->addResponseRow(__('File Stats'), __('File exists to attain file information'));
		
		//Move the file to the uploads directory, returns an array
		$uploaded_file = $this->handle_upload( $file, $uploads );
			
		$this->addResponseRow(__('File Transfer'), __('WebGL moved to wordpress uploads directory'), true);
	    		
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
		$guid = $this->build_guid( $uploaded_file['file'] );
		
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
        
        $this->addResponseRow(__('Media Library'), __('WebGL file is registered with wordpress media library'), true);
        $this->addResponseRow(__('Transaction Complete'), __('CAD file is now embeddable through media library'), true);
        $this->endResponseTable();
        
        $this->cleanCAD_ScratchSpace_Directory();
        
        return $attach_id;
		
	}
	
}

?>
