<?php

require_once("database.php");

class TMPL extends DB
{
    public $table_name;

    public function __construct()
    {
        parent::__construct();

        require_once(MODEL_PATH . 'log.php');
    }

    //Metoda pobiera wyłącznie właściwości publiczne z obiektu potomnego i zwraca w postaci tablicy asocjacyjnej
    public function getChildProperties() {
        $properties = [];
        try {
            $rc = new ReflectionClass($this);

            foreach ($rc->getProperties(ReflectionProperty::IS_PUBLIC) as $p) {
                if ($rc->name == $p->class) {
                    $p->setAccessible(true);

                    $properties[$p->getName()] = $p->getValue($this);
                }
            }
        } catch (ReflectionException $e) {
            //Tools::showObj($e);
        }
        return $properties;
    }

    //get(id) - pobiera dane z bazy i uzupełnia właściwości obiektu potomnego
    public function get($id = NULL) {
        $data = $this->Query(  "SELECT * FROM {$this->table_name} WHERE Id = ".($id ? $id : $this->Id).";"   );

        if (count($data)) {
            foreach ($this->getChildProperties() as $col_name => $value) {
                $this->$col_name = $data[0]->$col_name;
            }
        }
    }

    //Metoda zapisuje aktualny obiekt potomny do bazy danych
    public function save() {
        $sqlA = "";
        $sqlB = "";

        foreach ($this->getChildProperties() as $col_name => $value) {
            $sqlA .= "`{$col_name}`, ";
            $sqlB .= "'{$value}', ";
        }

        $sqlA = rtrim($sqlA, ", ");
        $sqlB = rtrim($sqlB, ", ");

        $res = $this->Query( "INSERT INTO {$this->table_name} ( {$sqlA} ) VALUES( {$sqlB} );" );
        
        //Zapis do logów
        Log::Write(get_class($this), Log::INSERT, $res, $res, '');

        return $res;
    }

    //usuwa dane konkretnego obiektu na podstawie numeru Id
    function delete() {

        $res = $this->Query("DELETE FROM {$this->table_name} WHERE Id = {$this->Id};");

        //Zapis do logów
        Log::Write(get_class($this), Log::DELETE, $_SESSION['user_bpawlak']->Id, $this->Id, '');

        return $res;
    }

    function deletePhoto($path) {
        $file = $_SERVER['DOCUMENT_ROOT'].'/bpawlak/assets/uploaded_images/'.$path;
        if(is_dir($file)) return;
        if(file_exists($file)) {
            unlink($file);
            return;
        }
    }

    //aktualizuje dany obiekt na podstawie numeru Id
    function update() {
        
        $sqlA = "";
        
        foreach ($this->getChildProperties() as $col_name => $value) {
            if($col_name=='User_Id') continue;
            $sqlA .= "`{$col_name}` = '{$value}', ";
        }

        $sqlA = rtrim($sqlA, ", ");
        $query = "UPDATE {$this->table_name} SET {$sqlA} WHERE Id = {$this->Id};";
        $res = $this->Query($query);
        
        //Zapis do logów
        Log::Write(get_class($this), Log::UPDATE,$_SESSION['user_bpawlak']->Id, $this->Id, '');

        return $res;
    }

    public function checkFile($file) {
        $re = '';
        $imgset = array( 
            'maxsize' => 2000, 
            'maxwidth' => 2048, 
            'maxheight' => 2000, 
            'minwidth' => 10, 
            'minheight' => 10, 
            'type' => array('jpg', 'jpeg', 'png'), 
        );
        // Get filename without extension 
        $sepext = explode('.', strtolower($file['name'])); 
        $type = end($sepext);    /** gets extension **/ 
    
        
        if(in_array($type, $imgset['type'])){
            // Image width and height 
            list($width, $height) = getimagesize($file['tmp_name']); 
     
            if(isset($width) && isset($height)) { 
                if($width > $imgset['maxwidth'] || $height > $imgset['maxheight']){ 
                    $re .= '\\n Width x Height = '. $width .' x '. $height .' \\n The maximum Width x Height must be: '. $imgset['maxwidth']. ' x '. $imgset['maxheight']; 
                } 
     
                if($width < $imgset['minwidth'] || $height < $imgset['minheight']){ 
                    $re .= '\\n Width x Height = '. $width .' x '. $height .'\\n The minimum Width x Height must be: '. $imgset['minwidth']. ' x '. $imgset['minheight']; 
                } 
     
                if($file['size'] > $imgset['maxsize']*1000){ 
                    $re .= '\\n Maximum file size must be: '. $imgset['maxsize']. ' KB.'; 
                } 
            } else {
                $re .= 'The photo is wrong';
                return $re;
            }
        }else{ 
            $re .= 'The file: '. $file['name']. ' has not the allowed extension type.';
        }
        return $re;
    }

    public function reforgeDate($date, $isAddon) { //isAddon == true => add an addon to the date
        $addon = '';
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $datas = explode(' ',$date);
        $days = explode('-',$datas[0]);
        $time = (count($datas)>1)?' - '.$datas[1]:'';
        if($isAddon) {
            switch($days[2][0]){
                case '1':
                    $addon = 'th';
                    break;
                default: {
                    switch($days[2][1]){
                        case '1':
                            $addon = 'st';
                            break;
                        case '2':
                            $addon = 'nd';
                            break;
                        case '3':
                            $addon = 'rd';
                            break;
                        default:
                            $addon = 'th';
                            break;
                    }
                    break;
                }
            };
        }
        
        $result = $months[$days[1]-1].' '.$days[2].$addon.', '.$days[0].' '.$time;
        return $result;
    }

    function upload($image, $destiny, $id) {
        $re = '';
        // Define file upload path  TRUE : POST      FASLE : USER
        $destiny ? $upload_dir = array('img'=> 'assets/uploaded_images/post_headers/') : $upload_dir = array('img'=> 'assets/uploaded_images/users/');
        // Allowed image properties 
    
        if(isset($image) && strlen($image['name']) > 1) {
            
            // Tools::showObj($this);
            // echo '<br />'.$id;
            // exit();

            $f_name = $id;
    
            // Get filename without extension 
            $sepext = explode('.', strtolower($image['name'])); 
            $type = end($sepext);    /** gets extension **/ 
    
            $upload_dir = $upload_dir['img'];
            $upload_dir = trim($upload_dir, '/') .'/';
            // File upload path 
            $f_name = $f_name .'.'.$type;
            $uploadpath = $upload_dir . $f_name; 
        }

        $this->Open();

        $res = move_uploaded_file($image['tmp_name'], $uploadpath);

        $this->Close();
        return $f_name;
    }

    /* EXTENDS DATABASE */
    //Tych metod używamy do komunikacji z bazą danych
    //Query($sql)
    //Open()
    //Close()
    //Select()
}
