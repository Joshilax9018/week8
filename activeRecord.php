<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('DATABASE', 'svj28');
define('USERNAME', 'svj28');
define('PASSWORD', 'vlAtaFzRh');
define('CONNECTION', 'sql2.njit.edu');
class dbConn{
    //variable to hold connection object.
    protected static $db;
    //private construct - class cannot be instatiated externally.
    private function __construct() {
        try {
            // assign PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            //echo "Connection Sucessful";
        }
        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection() {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
            //new connection object.
            new dbConn();
        }
        //return connection.
        return self::$db;
    }
}
class collection {

    static public function create() {
        $model = new static::$modelName;
        return $model;
    }
    static public function findAll() {
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet;
    }
    static public function findOne($id) {
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet[0];
    }
}


class model {

    protected  $tableName;

    protected static $columns_todos = array('owneremail', 'ownerid', 'createddate', 'duedate', 'message', 'isdone');
    protected static $columns_accounts = array('email', 'fname', 'lname', 'phone', 'birthday', 'gender','password');
    protected static $column;
    public function save()
    {
        if ($this->id == '' || $this->id == null) {
            $sql = $this->insert();
        } else {
            $sql = $this->update();
        }
        //echo "This is the query".$sql;
        $db = dbConn::getConnection();
        $statement = $db->prepare($sql);
        $statement->execute();


    }
    private function insert() {

        $tableName = get_called_class();
        //echo $tableName;
        if ($tableName == 'todo')
        {
            model::$column=model::$columns_todos;
            $this->tableName='todos';
        }

        else
        {
            model::$column=model::$columns_accounts;
            $this->tableName='accounts';
        }
        $array = get_object_vars($this);
       // print_r($array);
        $columnString = implode(',',model::$column);
        $valueString = "".implode(',', $array);

        if ($tableName == 'todo')
        return "INSERT INTO todos (" . $columnString . ") VALUES ('" .$this->owneremail."',".$this->ownerid.",'".$this->createddate."','".$this->duedate."','".$this->message."','".$this->isdone. "')";
        else

        return  "INSERT INTO accounts (" . $columnString . ") VALUES (''" .$this->email."','".$this->fname."','".$this->lname."','".$this->phone."','".$this->birthday."','".$this->gender."','".$this->password. ")";;
    }
    private function update() {
        $sql = 'sometthing';
        return $sql;
        echo 'I just updated record' . $this->id;
    }
    public function delete() {
        echo 'I just deleted record' . $this->id;
    }





}
class accounts extends collection {

    protected static $modelName = 'account';



}


class printer{


    public static function printTODOTable($result)
    {
        stringFunctions::printThis("<table border=\"1\"><tr><th>ID</th><th>Email</th><th>Owner ID</th><th>Date Create</th><th>Due Date
       </th><th>Message</th><th>IsDone</th></tr>");
        //print_r($result);
        if(sizeof($result) >1) {
            foreach ($result as $row) {
                stringFunctions::printThis('<tr>');
                stringFunctions::printThis("<td>" . $row->id . "</td>");
                stringFunctions::printThis("<td>" . $row->owneremail . "</td>");
                stringFunctions::printThis("<td>" . $row->ownerid . "</td>");
                stringFunctions::printThis("<td>" . $row->createddate . "</td>");
                stringFunctions::printThis("<td>" . $row->duedate . "</td>");
                stringFunctions::printThis("<td>" . $row->message . "</td>");
                stringFunctions::printThis("<td>" . $row->isdone . "</td>");
                stringFunctions::printThis('</tr>');

            }
        }
        else{

            stringFunctions::printThis('<tr>');
            stringFunctions::printThis("<td>" . $result->id . "</td>");
            stringFunctions::printThis("<td>" . $result->owneremail . "</td>");
            stringFunctions::printThis("<td>" . $result->ownerid . "</td>");
            stringFunctions::printThis("<td>" . $result->createddate . "</td>");
            stringFunctions::printThis("<td>" . $result->duedate . "</td>");
            stringFunctions::printThis("<td>" . $result->message . "</td>");
            stringFunctions::printThis("<td>" . $result->isdone . "</td>");
            stringFunctions::printThis('</tr>');

        }
    }



    public static function printaccountTable($result)
    {
        stringFunctions::printThis("<table border=\"1\"><tr><th>ID</th><th>Email</th><th>First Name</th><th>Last Name</th><th>Phone
       </th><th>birthday</th><th>Gender</th><th>Password</th></tr>");
        //print_r($result);
        if(sizeof($result) >1) {
            foreach ($result as $row) {
                stringFunctions::printThis('<tr>');
                stringFunctions::printThis("<td>" . $row->id . "</td>");
                stringFunctions::printThis("<td>" . $row->email . "</td>");
                stringFunctions::printThis("<td>" . $row->fname . "</td>");
                stringFunctions::printThis("<td>" . $row->lname . "</td>");
                stringFunctions::printThis("<td>" . $row->phone . "</td>");
                stringFunctions::printThis("<td>" . $row->birthday . "</td>");
                stringFunctions::printThis("<td>" . $row->gender . "</td>");
                stringFunctions::printThis("<td>" . $row->password . "</td>");
                stringFunctions::printThis('</tr>');

            }
        }
        else{

            stringFunctions::printThis('<tr>');
            stringFunctions::printThis("<td>" . $result->id . "</td>");
            stringFunctions::printThis("<td>" . $result->email . "</td>");
            stringFunctions::printThis("<td>" . $result->fname . "</td>");
            stringFunctions::printThis("<td>" . $result->lname . "</td>");
            stringFunctions::printThis("<td>" . $result->phone . "</td>");
            stringFunctions::printThis("<td>" . $result->birthday . "</td>");
            stringFunctions::printThis("<td>" . $result->gender . "</td>");
            stringFunctions::printThis("<td>" . $result->password . "</td>");
            stringFunctions::printThis('</tr>');

        }
    }


    public static function printit($modelinfo,$result){

        if($modelinfo =='todo')
            printer::printTODOTable($result);
        else if($modelinfo == 'account')
            printer::printaccountTable($result);

    }




}
class todos extends collection {
    protected static $modelName = 'todo';


}






/*Class String Function
*/
class stringFunctions{

    //This fution will print HTML page
    public static function printThis($text){
        print($text);
    }

}

class account extends model{
    public $id;
    public $email;
    public $fname;
    public $lname;
    public $phone;
    public $birthday;
    public $gender;
    public $password;
}
class todo extends model{

    public $id;
    public $owneremail;
    public $ownerid;
    public $createddate;
    public $duedate;
    public $message;
    public $isdone;



}



$records=accounts::findAll();
printer::printit('account',$records);
echo " </br> ----------------------------------------------------------------------------------------------------------------------------------------------------------</br>";


$records = todos::findAll();
printer::printit('todo',$records);
echo " </br> ----------------------------------------------------------------------------------------------------------------------------------------------------------</br>";


$Todo = new todo();
$Todo->owneremail='tom@gmail.com';
$Todo->ownerid = 111;
$Todo->createddate='2017-06-15 09:34:21';
$Todo->duedate = '2017-06-15 09:34:21';
$Todo->message = 'call dad';
$Todo->isdone = 0;
$Todo->save();
echo " </br> ----------------------------------------------------------------------------------------------------------------------------------------------------------</br>";

$records = todos::findAll();
printer::printit('todo',$records);
echo " </br> ----------------------------------------------------------------------------------------------------------------------------------------------------------</br>";

$records_1 = todos::findOne(2);
printer::printit('todo',$records_1);

