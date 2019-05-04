<?php 
//require_once './Version.php';
//require_once './Post.php';

class SchemaBdd {

    const DEFAULT_DIRECTORY = "config";
	private $tableName ;
	private $version ;



    public function __construct(){
		$this->version = new Version() ;
	}




    function get_json(){
        $configfiles = array_diff(scandir(self::DEFAULT_DIRECTORY), array('..', '.'));
        return $configfiles ;
	}
	
	function migrate(){
		$this->create();
		$this->update();
	}


    function create(){
        foreach($this->get_json() as $file){
            $json = file_get_contents(self::DEFAULT_DIRECTORY .'/' . $file);
            $obj = json_decode($json, true);
            $this->tablename = (array_key_exists('tableName',$obj)) ? $obj['tableName'] : pathinfo(self::DEFAULT_DIRECTORY . '/' . $file)['filename'];
            $fields = array();
            foreach($obj['fields'] as $k => $v){
                $fields[] = $k . ' ' . $v['type'] . ' ' .$v['property'];
            }
		    $req = "CREATE TABLE IF NOT EXISTS ". $this->tablename . '(' . implode(' , ', $fields) .')'  ;
		    MySql::getInstance()->getConnection()->exec($req);
		    if($this->table_exist($this->tablename) == 0){
		        $req_version = "INSERT INTO version SET tab = '" . $this->tablename . "', nb_fields = ". count($obj['fields']) .", version='0.0'";
		        MySql::getInstance()->getConnection()->exec($req_version);
		    }
        }
    }

    function update(){
        foreach($this->get_json() as $file){
            $json = file_get_contents(self::DEFAULT_DIRECTORY .'/' . $file);
            $obj = json_decode($json, true);
            $this->tablename = (array_key_exists('tableName',$obj)) ? $obj['tableName'] : pathinfo(self::DEFAULT_DIRECTORY . '/' . $file)['filename'];
            if($this->recup_version($this->tablename) !== $obj['version']){
                if(count($obj['fields']) > $this->recup_nb_fields($this->tablename)){
					echo "On a ajouté un ou plusieurs champ(s) dans la table -> " . $this->tablename . "\n";
					$this->add_column($obj, $this->tablename);
                }
                else if (count($obj['fields']) < $this->recup_nb_fields($this->tablename)){
					echo "on a retiré un ou plusieurs champ(s) dans la table -> " . $this->tablename . "\n";
					$this->delete_column($obj, $this->tablename);
				}
				$this->update_property($obj, $this->tablename);
            } else echo "\nLa version est à jour - Aucune modification apportée";
        }
	}

	function delete_column($obj, $tableName){
		$class = new $tableName();
		$nomsChamps = $this->recup_noms_fields_bdd($tableName);
		$nomsCol = [] ;
		    foreach ($nomsChamps as $v){ $nomsCol[] =  $v['COLUMN_NAME'] ; }
		        $champsJson = array_keys($obj['fields']);
		        $difference = array_diff($nomsCol, $champsJson);
		        $fields = [] ;
		        foreach($difference as $v){
			        $fields[] =  'DROP ' . $v;
		        }
		        $req = "ALTER TABLE " . $tableName . ' ' . implode(' , ', $fields) ;
		        $this->version->load(1);
		        $this->version->setVersion($obj['version']);
		        $this->version->setNbFields(count($obj['fields']));
		        $this->version->save();
		        echo ($req);
		        MySql::getInstance()->getConnection()->exec($req);
}
	
	function add_column($obj, $tableName){
		$class = new $tableName();
		$nomsChamps = $this->recup_noms_fields_bdd($tableName);
		$nomsCol = [] ;
			foreach ($nomsChamps as $v){ $nomsCol[] =  $v['COLUMN_NAME'] ; }
			$champsJson = array_keys($obj['fields']);
			$difference = array_diff($champsJson, $nomsCol);
			$fields = [] ;
			foreach($difference as $v){
				$fields[] =  'ADD ' . $v . ' ' . $obj['fields'][$v]['type'] . ' ' . $obj['fields'][$v]['property'];
			}
			$req = "ALTER TABLE " . $tableName . ' ' . implode(' , ', $fields) ;
			$this->version->load(1);
			$this->version->setVersion($obj['version']);
			$this->version->setNbFields(count($obj['fields']));
			$this->version->save();
			echo $req ;
			MySql::getInstance()->getConnection()->exec($req);
	}

    function update_property($obj, $tableName){

                $i = 0 ;
				$props = [] ;
				$nomsChamps = $this->recup_noms_fields_bdd($tableName);
                foreach($obj['fields'] as $k => $v){
                    $props[] = 'CHANGE ' . $nomsChamps[$i]['COLUMN_NAME'] . ' ' . $k . ' ' . $v['type'] . ' ' .$v['property'];
                    $i++;
                }
				$req = "ALTER TABLE " . $tableName . ' ' . implode(' , ', $props)  ;
				$this->version->load(1);
				$this->version->setVersion($obj['version']);
				$this->version->setNbFields(count($obj['fields']));
				$this->version->save();
            MySql::getInstance()->getConnection()->exec($req);
    }

    function recup_version($nomtable){
        //$version = new version();
        $posts = Version::find('tab = "'. $nomtable .'"');
        return $posts[0]['version'];
    }

    function recup_nb_fields($nomtable){
        $version = new Version();
		$posts = Version::find('tab = "'. $nomtable .'"');
        return $posts[0]['nb_fields'];
	}
	
	function table_exist($nomtable){
		$version = new Version();
		$posts = Version::find('tab = "'. $nomtable .'"');
		return empty($posts[0]) ? 0 : 1 ;
	}

    function recup_noms_fields_bdd($nomtable){
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$nomtable'" ;
        $pdo = MySql::getInstance()->getConnection();
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        
    }

    function check_json_validity($json){
    json_decode($json, true);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo 'Aucune erreur dans les fichiers JSON';
            return 1;
        break;
        case JSON_ERROR_DEPTH:
            echo  ' - Profondeur maximale atteinte';
            return 0;
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Inadéquation des modes ou underflow';
            return 0;
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo  ' - Erreur lors du contrôle des caractères';
            return 0;
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Erreur de syntaxe ; JSON malformé';
            return 0;
        break;
        case JSON_ERROR_UTF8:
            echo  ' - Caractères UTF-8 malformés, probablement une erreur d\'encodage';
            return 0;
        break;
        default:
            echo  ' - Erreur inconnue';
            return 0;
        break;
    }
    }

}