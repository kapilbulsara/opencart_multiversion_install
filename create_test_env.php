<?php 
/* @TODO 
- 3.0.3.7 - 3.1.0.0 
 */
/*

php create_test_env.php  --db_hostname localhost \  
                         --db_username demo \
						 --db_password pass \
						 --db_prefix demo_opencart_  \
						 --username admin \
						 --password admin \
						 --email yourmail@example.com \
						 --http_server http://localhost/opencart/

 */
// php create_test_env.php  --db_hostname localhost  --db_username demo --db_password demo --db_prefix demo_opencart_  --username admin --password admin --email yourmail@example.com --http_server http://demo.localhost/opencart/

// BEGIN clone repos
$repoName = 'opencart_master';
exec("git clone https://github.com/opencart/opencart.git $repoName");
exec("git ls-remote --tags $repoName", $tags);

foreach( $tags as  $line){

		$tag = substr($line, strpos($line, 'refs/tags/') + 10 ); 
		exec("git clone $repoName --branch=$tag $tag");
		exec("cd $tag && git checkout -b master");
// END clone repos
		$options  = $argv; 

		$hostname =  $options[array_search('--db_hostname', $options) +1 ];
		$dbuser  = $options[array_search('--db_username', $options) +1 ];
		$dbpass = $options[array_search('--db_password', $options) +1 ];
		$database = $options[array_search('--db_prefix', $options) +1 ] . str_replace('.', '', $tag);	
		createDB($hostname, $dbuser ,  $dbpass, $database );

		$options[array_search('--http_server', $options) + 1] = $options[array_search('--http_server', $options) + 1] .   "$tag/upload/";

		if(substr($tag, 0,1) == '1'){
				//alternate option names
				$options[array_search('--db_hostname', $options)] = '--db_host';
				$options[array_search('--db_username', $options)] = '--db_user';
				$options[array_search('--db_prefix', $options)] = '--db_name';
				$options[array_search('--db_name', $options) + 1] = $database;
				$options['--agree_tnc'] = 'yes';

		}else{

				$options[array_search('--db_prefix', $options)] = '--db_database';
				$options[array_search('--db_database', $options) + 1] = $database;
		}

		array_splice($options, 0,1);

		exec("cd $tag/upload/install/ && php cli_install.php install " . implode(" ", $options) );
		if(substr($tag, 0,1) == '3'){
				exec("rm -rf $tag/upload/system/storage/vendor/*");
				exec("cd $tag && composer -n update");
		}

}

function createDB($servername = 'localhost', $username, $password, $dbName){
		// Create connection
		$conn = new mysqli($servername, $username, $password);
		// Check connection
		if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
		}

		// Create database
		$sql = "CREATE DATABASE " .  $conn->real_escape_string($dbName);
		if ($conn->query($sql) === TRUE) {
				echo "Database $dbName created successfully";
		} else {
				echo "Error creating database: " . $conn->error;
		}

		$conn->close();
}


?>
