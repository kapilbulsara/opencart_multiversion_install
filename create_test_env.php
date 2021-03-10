<?php 

//   php cli_install.php install --db_hostname localhost \
//                               --db_username root \
//                               --db_password pass \
//                               --db_database opencart \
//                               --db_driver mysqli \
//								 --db_port 3306 \
//                               --username admin \
//                               --password admin \
//                               --email youremail@example.com \

$options = array(
		'--db_hostname' => 'localhost',
		'--db_username' => 'root',
		'--db_password' => 'pass',
		'--db_database' => 'opencart',
		'--db_driver' => 'mysqli',
		'--db_port' => '3306',
		'--username' => 'admin',
		'--password' => 'admin',
		'--email' => 'youremail@example.com',
		'--http_server' => 'http://localhost/opencart/'

);

$options = getOptions($argv);

$alternateOptions = array(
		'--db_hostname'	=>'--db_host',
		'--db_username' 	=>'--db_user',
		'--db_password' 	=>'--db_password',
		'--db_database' 	=>'--db_name',
		'--db_driver' 		=>'',
		'--db_port' 		=>'',
		'--username' 		=>'--username',
		'--password' 		=>'--password',
		'--email' 			=>'--email',
		'--http_server' 	=>'--http_server'
);

print_r($options);
// BEGIN clone repos
$repoName = 'opencart_master';
//exec("git clone https://github.com/opencart/opencart.git $repoName");
exec("git ls-remote --tags $repoName", $tags);

foreach( $tags as  $line){

		$tag = substr($line, strpos($line, 'refs/tags/') + 10 ); 
		$tag = str_replace('.', '', $tag);
		//exec("git clone $repoName --branch=$tag $tag");
		//exec("cd $tag && git checkout -b master");
		// END clone repos
		$options  = $argv; 

		$hostname =  $options[array_search('--db_hostname', $options) +1 ];
		$dbuser  = $options[array_search('--db_username', $options) +1 ];
		$dbpass = $options[array_search('--db_password', $options) +1 ];
		$database = $options[array_search('--db_prefix', $options) +1 ] . $tag;
		createDB($hostname, $dbuser ,  $dbpass, $database );

		$options[array_search('--http_server', $options) + 1] = $options[array_search('--http_server', $options) + 1] . $tag;

		if(substr($tag, 0,1) == '1'){
				//alternate option names
				$options[array_search('--db_hostname', $options)] = '--db_host';
				$options[array_search('--db_username', $options)] = '--db_user';
				$options[array_search('--db_prefix', $options)] = '--db_name';
				$options[array_search('--db_name', $options) + 1] = $database;

		}else{

				$options[array_search('--db_prefix', $options)] = '--db_database';
				$options[array_search('--db_database', $options) + 1] = $database;
		}
		exec("cd $tag/upload/install/ && php cli_install.php " . implode(" ", $argv) . " --db_database demo_opencart_$tag  --http_server " .  $options['--db_password'] . "$tag" );

}


function getOptions($args){

		$options = array();
		for($i = 1; $i < count($args); ){

				$options[$args[$i]] = $args[$i + 1];
				$i += 2; 
		}
		return $options; 

}

function getAlternativeOptions($options){

		$alternate = array(); 
		foreach($options as $key =>  $value){
				$alternate[$alternateOptions[$key]] = $value; 
		}
		return $alternate;
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
