<?php

require_once(CORE_PATH . "TMPL.php");

class Log extends TMPL
{
    const
        INSERT = 'I',
        DELETE = 'D',
        UPDATE = 'U';

    public
        $Id = NULL,
        $Type = NULL,
        $UserId = NULL,
        $ObjectId = NULL,
        $ObjectName = NULL,
        $Time = NULL,
        $Comment = NULL;


    public function __construct()
    {
        parent::__construct();

        $this->table_name = 'Logs';
    }

    public function getAllLogs(){
        $query = "SELECT * FROM Logs";
        $result = $this->Query($query);
        return $result;
    }

    public function deleteLogAdmin($id){
        $this->get($id);
        $this->delete();
        return;
    }

    static function Write($ObjectName, $Type, $UserId, $ObjectId, $Info)
    {
        if($ObjectName == 'Log')
            return;

        $tmpl = new TMPL();

        switch ($Type) {
            case self::DELETE:
                $Comment = "Usunięto ";
                break;

            case self::INSERT:
                $Comment = "Utworzono ";
                break;

            case self::UPDATE:
                $Comment = "Zaktualizowano ";
                break;

            default:
                break;
        }

        $Comment .= " obiekt " . $ObjectName.'. '.$Info;

        $tmpl->Query("INSERT INTO Logs() VALUES(NULL, '$Type', '$UserId', '$ObjectName', '$ObjectId', '" . date("Y-m-d H:i:s") . "', '$Comment');");
    }
}
