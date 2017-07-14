<?php

namespace library;

use PDO;
use Exception;
use PDOException;

/**
 * PDO Database class.
 *
 * @author Marlon Zayro Arias Vargas <zayro8905@gmail.com>
 * @copyright: 	zavweb 2016
 * @version: 	2.5
 */
class DBMS
{
    private $database_types = array('sqlite2', 'sqlite3', 'sqlsrv', 'mssql', 'mysql', 'pg', 'ibm', 'dblib', 'odbc', 'oracle', 'ifmx', 'fbd');

    private $host;
    private $database;
    private $user;
    private $password;
    private $port;
    private $database_type;
    private $root_mdb;
    private $con;
    private $count;

    public $sql;

    protected $err_msg = array();


     /**
      * Constructor of class - Initializes class and connects to the database.
      *
      * Initialize class and connects to the database
      *
      * @param string $database_type the name of the database type
      * @param string $host          the host of the database
      * @param string $database      the name of the database
      * @param string $user          the name of the user for the database
      * @param string $password      the passord of the user for the database
      */
     public function __construct($database_type, $host, $database, $user, $password, $port)
     {
         $this->database_type = strtolower($database_type);
         $this->host = $host;
         $this->database = $database;
         $this->user = $user;
         $this->password = $password;
         $this->port = $port;
     }

    //Initialize class and connects to the database
    public function Cnxn()
    {
        if (in_array($this->database_type, $this->database_types)) {
            try {
                switch ($this->database_type) {
                    case 'mssql':
                        $this->con = new PDO('mssql:host='.$this->host.';dbname='.$this->database, $this->user, $this->password);
                        break;
                    case 'sqlsrv':
                        $this->con = new PDO('sqlsrv:server='.$this->host.';database='.$this->database, $this->user, $this->password);
                        break;
                    case 'ibm': //default port = ?
                        $this->con = new PDO('ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE='.$this->database.'; HOSTNAME='.$this->host.';PORT='.$this->port.';PROTOCOL=TCPIP;', $this->user, $this->password);
                        break;
                    case 'dblib': //default port = 10060
                        $this->con = new PDO('dblib:host='.$this->host.':'.$this->port.';dbname='.$this->database, $this->user, $this->password);
                        break;
                    case 'odbc':
                        $this->con = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=".$this->user);
                        break;
                    case 'oracle':
                        $this->con = new PDO('OCI:dbname='.$this->database.';charset=UTF-8', $this->user, $this->password);
                        break;
                    case 'ifmx':
                        $this->con = new PDO('informix:DSN=InformixDB', $this->user, $this->password);
                        break;
                    case 'fbd':
                        $this->con = new PDO('firebird:dbname='.$this->host.':'.$this->database, $this->user, $this->password);
                        break;
                    case 'mysql':
                        $this->con = (is_numeric($this->port)) ? new PDO('mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->database, $this->user, $this->password) : new PDO('mysql:host='.$this->host.';dbname='.$this->database, $this->user, $this->password);
                        break;
                    case 'sqlite2': //ej: "sqlite:/path/to/database.sdb"
                        $this->con = new PDO('sqlite:'.$this->host);
                        break;
                    case 'sqlite3':
                        $this->con = new PDO('sqlite::memory');
                        break;
                    case 'pg':
                        $this->con = (is_numeric($this->port)) ? new PDO('pgsql:dbname='.$this->database.';port='.$this->port.';host='.$this->host, $this->user, $this->password) : new PDO('pgsql:dbname='.$this->database.';host='.$this->host, $this->user, $this->password);
                        break;
                    default:
                        $this->con = null;
                        break;
                }

                $this->con->exec('SET NAMES UTF8');
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                //$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                //$this->con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY => true);
                //$this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                return $this->con;
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                #$this->err_msg['errorCode'] = $this->con->errorCode();
                #eval(\Psy\sh());

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                /*
                $this->err_msg["errorInfo"] = $this->con->errorInfo();
                $this->err_msg["errorCode"] = $this->con->errorCode();
                */

                return false;
            }
        } else {
            $this->err_msg = 'Error: Error establishing a database connection (error in params or database not supported).';

            return false;
        }
    }

    //Retrieve connection properties
    public function properties()
    {
        echo '<span style="display:block;color:#267F00;background:#F4FFEF;border:2px solid #267F00;padding:2px 4px 2px 4px;margin-bottom:5px;">';
        print_r('<b>DATABASE:</b>&nbsp;'.$this->con->getAttribute(PDO::ATTR_DRIVER_NAME).'&nbsp;'.$this->con->getAttribute(PDO::ATTR_SERVER_VERSION).'<br/>');
        print_r('<b>STATUS:</b>&nbsp;'.$this->con->getAttribute(PDO::ATTR_CONNECTION_STATUS).'<br/>');
        print_r('<b>CLIENT:</b><br/>'.$this->con->getAttribute(PDO::ATTR_CLIENT_VERSION).'<br/>');
        print_r('<b>INFORMATION:</b><br/>'.$this->con->getAttribute(PDO::ATTR_SERVER_INFO));
        echo '</span>';
    }

    //Retrieve all drivers capables
    public function drivers()
    {
        print_r(PDO::getAvailableDrivers());
    }

    //Execute the transactional operations
    public function transaction($arg)
    {
        if ($this->con != null) {
            try {
                if ($arg == 'B') {
                    $this->con->beginTransaction();
                } elseif ($arg == 'C') {
                    $this->con->commit();
                } elseif ($arg == 'R') {
                    $this->con->rollBack();
                } else {
                    $this->err_msg = 'Error: The passed param is wrong! just allow [B=begin, C=commit or R=rollback]';

                    return false;
                }
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Evaluate query statement
    private function evalStatement($query)
    {
        $query = strtolower(trim($query));
        $instruction = substr($query, 0, 9);

        if ($instruction == 'delimiter') {
            return 'delimiter';
        }
        $instruction = substr($query, 0, 6);
        if ($instruction == 'delete') {
            return 'delete';
        }
        if ($instruction == 'insert') {
            return 'insert';
        }
        if ($instruction == 'update') {
            return 'update';
        }
        if ($instruction == 'create') {
            return 'create';
        }
        $instruction = substr($query, 0, 5);
        if ($instruction == 'alter') {
            return 'alter';
        }
        $instruction = substr($query, 0, 4);
        if ($instruction == 'exec') {
            return 'exec';
        }
        if ($instruction == 'call') {
            return 'call';
        }
        if ($instruction == 'drop') {
            return 'drop';
        }

        return '';
    }

    //Return total records from query as integer
    public function rowcount()
    {
        return $this->count;
    }

    //Iterate over rows
    public function query($sql_statement, $type = 'array', $arg = '')
    {
        //$transactionable = false;
        $arguments = null;
        $total_rows = 0;


        if ($this->con != null) {
            try {
                $this->sql = $sql_statement;

                if (gettype($type) == 'array') {
                    $tmpArrayObject = new ArrayObject($type);
                    $arguments = $tmpArrayObject->getArrayCopy();
                }

                $tmpType = trim($this->evalStatement($this->sql));

                if ($tmpType != '') {
                    $type = $tmpType;
                }

                switch ($type) {
                    case 'delete':
                        echo 'Deprecated... You need use delete to call method.';
                        break;
                    case 'update':
                   //$transactionable = true;
                        $sth = $this->con->prepare($this->sql);
                        //$this->con->beginTransaction();

                            $sth->execute();
                             $this->count = $sth->rowCount();

                        //$this->con->commit();
                        break;
                    case 'insert':
                        //$transactionable = true;
                        $sth = $this->con->prepare($this->sql);
                        //$this->con->beginTransaction();

                            $sth->execute();
                             $this->count = $sth->rowCount();

                        //$this->con->commit();
                        break;
                    case 'call':
                        echo 'Deprecated... You need use query_secure to call procedures.';
                        break;
                    default:

                        $sth = $this->con->prepare($this->sql);

                        if (!$sth) {
                            throw new Exception($this->con->errorInfo());
                        }

                        if ($arguments != null) {
                            $sth->execute($arguments);
                        } else {
                            $sth->execute();
                        }

                        $this->count = $sth->rowCount();
                        break;
                }

                if ($arg == '') {
                    $arg = $type;
                }

                switch ($arg) {
                    case 'named':
                        return $sth->fetch(PDO::FETCH_NAMED);
                    case 'both':
                        return $sth->fetchAll(PDO::FETCH_BOTH);
                    case 'obj':
                        return $sth->fetchAll(PDO::FETCH_OBJ);
                    case 'class':
                        return $sth->fetchAll(PDO::FETCH_CLASS, $arg);
                    case 'func':
                        return $sth->fetchAll(PDO::FETCH_FUNC, $arg);
                    case 'count':
                        $objNum = $sth->fetch(PDO::FETCH_NUM);

                        return isset($objNum[0]) ? intval($objNum[0]) : 0;
                    case 'delete':
                    case 'update':
                    case 'exec':
                    case 'drop':
                    case 'create':
                    case 'alter':
                        return $this->count;
                    case 'insert':
                        return $this->count;
                    case 'call':
                        return false;
                    default:
                        return $sth->fetchAll(PDO::FETCH_ASSOC);
                }
            } catch (PDOException $e) {
                //if($transactionable) $this->con->rollback();
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();
                $this->err_msg['sql'] = $this->sql;
                $this->err_msg['success'] = false;                
            
           

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Querys Anti SQL Injections
    public function query_secure($sql_statement, $params, $fetch_rows = false, $unnamed = false, $delimiter = '|')
    {
        $this->sql = $sql_statement;
        if (!isset($unnamed)) {
            $unnamed = false;
        }
        if (trim((string) $delimiter) == '') {
            $this->err_msg = 'Error: Delimiter are required.';

            return false;
        }
        if ($this->con != null) {
            $obj = $this->con->prepare($sql_statement);
            if (!$unnamed) {
                for ($i = 0; $i < count($params); ++$i) {
                    $params_split = explode($delimiter, $params[$i]);
                    (trim($params_split[2]) == 'INT') ? $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_INT) : $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_STR);
                }
                try {
                    $obj->execute();
                    $this->count = $obj->rowCount();
                } catch (PDOException $e) {
                    $this->err_msg['error'] = $e->getMessage();
                    $this->err_msg['errorInfo'] = $this->con->errorInfo();
                    $this->err_msg['errorCode'] = $this->con->errorCode();

                    return false;
                } catch (Throwable $e) {
                    $this->err_msg['error'] = $e->getMessage();
                    $this->err_msg['errorInfo'] = $this->con->errorInfo();
                    $this->err_msg['errorCode'] = $this->con->errorCode();

                    return false;
                }
            } else {
                try {
                    $obj->execute($params);
                    $this->count = $obj->rowCount();

                    if ($fetch_rows) {
                        return $obj->fetchAll(PDO::FETCH_ASSOC);
                    } //PDO::FETCH_OBJ || PDO::FETCH_ARRAY || PDO::FETCH_ASSOC
            if (is_numeric($this->con->lastInsertId())) {
                return $this->con->lastInsertId();
            }
                } catch (PDOException $e) {
                    $this->err_msg['error'] = $e->getMessage();
                    $this->err_msg['errorInfo'] = $this->con->errorInfo();
                    $this->err_msg['errorCode'] = $this->con->errorCode();

                    return false;
                } catch (Throwable $e) {
                    print_r($e->getMessage());
                    $this->err_msg['error'] = $e->getMessage();
                    $this->err_msg['errorInfo'] = $this->con->errorInfo();
                    $this->err_msg['errorCode'] = $this->con->errorCode();


                    return false;
                }
            }

            return true;
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Fetch the first row
    public function query_first($sql_statement)
    {
        if ($this->con != null) {
            try {
                $sttmnt = $this->con->prepare($sql_statement);
                $sttmnt->execute();

                return $sttmnt->fetch();
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Select single table cell from first record
    public function query_single($sql_statement)
    {
        if ($this->con != null) {
            try {
                $sttmnt = $this->con->prepare($sql_statement);
                $sttmnt->execute();

                return $sttmnt->fetchColumn();
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Return name columns as vector
    public function columns($table)
    {
        $this->sql = "SELECT * FROM $table";
        if ($this->con != null) {
            try {
                $q = $this->con->query($this->sql);
                $column = array();
                foreach ($q->fetch(PDO::FETCH_ASSOC) as $key => $val) {
                    $column[] = $key;
                }

                return $column;
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    public function increment($table, $data, $explode = ',')
    {
        if ($this->con != null) {
            try {
                $txt_fields = '';
                $txt_values = '';
                $data_column = explode($explode, $data);
                for ($x = 0; $x < count($data_column); ++$x) {
                    list($field, $value) = explode('=', $data_column[$x]);
                    $txt_fields .= ($x == 0) ? $field : ','.$field;
                    $txt_values .= ($x == 0) ? $value : ','.$value;
                }

                $sql = 'INSERT INTO '.$table.' (id, '.$txt_fields.') VALUES((select IFNULL(max(id) + 1 , 1) from '.$table.' as alias), '.$txt_values.');';
                $this->sql = $sql;
                $result = $this->con->exec($sql);

                return ($result === false) ? $result : $this->con->lastInsertId();
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Insert and get newly created id
    public function insert($table, $data, $explode = ',')
    {
        if ($this->con != null) {
            try {
                $txt_fields = '';
                $txt_values = '';
                $data_column = explode($explode, $data);
                for ($x = 0; $x < count($data_column); ++$x) {
                    list($field, $value) = explode('=', $data_column[$x]);
                    $txt_fields .= ($x == 0) ? $field : ','.$field;
                    $txt_values .= ($x == 0) ? $value : ','.$value;
                }

                $sql = 'INSERT INTO '.$table.' ('.$txt_fields.') VALUES('.$txt_values.');';
                $this->sql = $sql;
                $result = $this->con->exec($sql);

                return ($result === false) ? $result : $this->con->lastInsertId();
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Update tables
    public function update($table, $data, $condition = '')
    {
        if ($this->con != null) {
            try {
                if (trim($condition) != '') {
                    $sql = 'UPDATE '.$table.' SET '.$data.' WHERE '.$condition.';';
                    $this->sql = $sql;
                    $result = $this->con->exec($sql);
                } else {
                    $sql = 'UPDATE '.$table.' SET '.$data.';';
                    $this->sql = $sql;
                    $result = $this->con->exec($sql);
                }

                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                $return = array($sql, $result);

                return $result;
            } catch (PDOException  $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Delete records from tables
    public function delete($table, $condition = '')
    {
        if ($this->con != null) {
            try {
                if (trim($condition) != '') {
                    $sql = 'DELETE FROM '.$table.' WHERE '.$condition.';';
                    $this->sql = $sql;
                    $result = $this->con->exec($sql);
                } else {
                    $sql = 'DELETE FROM '.$table.';';
                    $this->sql = $sql;
                    $result = $this->con->exec($sql);
                }

                return $result;
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Execute Store Procedures
    public function execute($sp_query)
    {
        if ($this->con != null) {
            try {
                $this->con->exec($sp_query);

                return true;
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Get latest specified id from specified table
    public function getLatestId($db_table, $table_field)
    {
        $sql_statement = '';
        $dbtype = $this->database_type;

        if ($dbtype == 'sqlsrv' || $dbtype == 'mssql' || $dbtype == 'ibm' || $dbtype == 'dblib' || $dbtype == 'odbc') {
            $sql_statement = 'SELECT TOP 1 '.$table_field.' FROM '.$db_table.' ORDER BY '.$table_field.' DESC;';
        } elseif ($dbtype == 'oracle') {
            $sql_statement = 'SELECT '.$table_field.' FROM '.$db_table.' WHERE ROWNUM<=1 ORDER BY '.$table_field.' DESC;';
        } elseif ($dbtype == 'ifmx' || $dbtype == 'fbd') {
            $sql_statement = 'SELECT FIRST 1 '.$table_field.' FROM '.$db_table.' ORDER BY '.$table_field.' DESC;';
        } elseif ($dbtype == 'mysql' || $dbtype == 'sqlite2' || $dbtype == 'sqlite3') {
            $sql_statement = 'SELECT '.$table_field.' FROM '.$db_table.' ORDER BY '.$table_field.' DESC LIMIT 1;';
        } elseif ($dbtype == 'pg') {
            $sql_statement = 'SELECT '.$table_field.' FROM '.$db_table.' ORDER BY '.$table_field.' DESC LIMIT 1 OFFSET 0;';
        }

        if ($this->con != null) {
            try {
                return $this->query_single($sql_statement);
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Get all tables from specified database
    public function ShowTables($database)
    {
        $complete = '';
        $sql_statement = '';
        $dbtype = $this->database_type;

        if ($dbtype == 'sqlsrv' || $dbtype == 'mssql' || $dbtype == 'ibm' || $dbtype == 'dblib' || $dbtype == 'odbc' || $dbtype == 'sqlite2' || $dbtype == 'sqlite3') {
            $sql_statement = "SELECT name FROM sysobjects WHERE xtype='U';";
        } elseif ($dbtype == 'oracle') {
            //If the query statement fail, try with uncomment the next line:
            //$sql_statement = "SELECT table_name FROM tabs;";
            $sql_statement = 'SELECT table_name FROM cat;';
        } elseif ($dbtype == 'ifmx' || $dbtype == 'fbd') {
            $sql_statement = "SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME;";
        } elseif ($dbtype == 'mysql') {
            if ($database != '') {
                $complete = " FROM $database";
            }
            $sql_statement = 'SHOW tables '.$complete.';';
        } elseif ($dbtype == 'pg') {
            $sql_statement = 'SELECT relname AS name FROM pg_stat_user_tables ORDER BY relname;';
        }

        if ($this->con != null) {
            try {
                $this->sql = $sql_statement;
                $sth = $this->con->prepare($this->sql);
                $sth->execute();
                $this->count = $sth->rowCount();

                return $sth->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Get all databases from your server
    public function ShowDBS()
    {
        $sql_statement = '';
        $dbtype = $this->database_type;

        if ($dbtype == 'sqlsrv' || $dbtype == 'mssql' || $dbtype == 'ibm' || $dbtype == 'dblib' || $dbtype == 'odbc' || $dbtype == 'sqlite2' || $dbtype == 'sqlite3') {
            $sql_statement = 'SELECT name FROM sys.Databases;';
        } elseif ($dbtype == 'oracle') {
            //If the query statement fail, try with uncomment the next line:
            //$sql_statement = "SELECT * FROM user_tablespaces";
            $sql_statement = "SELECT * FROM $database;";
        } elseif ($dbtype == 'ifmx' || $dbtype == 'fbd') {
            $sql_statement = '';
        } elseif ($dbtype == 'mysql') {
            $sql_statement = 'SHOW DATABASES;';
        } elseif ($dbtype == 'pg') {
            $sql_statement = 'SELECT datname AS name FROM pg_database;';
        }

        if ($this->con != null) {
            try {
                $this->sql = $sql_statement;

                return $this->con->query($this->sql);
            } catch (PDOException $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            } catch (Throwable $e) {
                $this->err_msg['error'] = $e->getMessage();
                $this->err_msg['errorInfo'] = $this->con->errorInfo();
                $this->err_msg['errorCode'] = $this->con->errorCode();

                return false;
            }
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }

    //Get the latest error ocurred in the connection
    public function getError()
    {
        return $this->err_msg;
    }

    //Get the latest sql execute
    public function getSql()
    {
        return trim($this->sql) != '' ? $this->sql : '';
    }

    //Disconnect database
    public function disconnect()
    {
        if ($this->con) {
            $this->con = null;

            return true;
        } else {
            $this->err_msg = 'Error: Connection to database lost.';

            return false;
        }
    }


    public function insertSingle(string $sBaseDatos, string $sTable, array $aData): bool {
        try {
            $this->sSql = "INSERT INTO " . $sBaseDatos . "." . $sTable . " (" . implode(', ', array_keys($aData)) . ") VALUES " . $this->argsInsert(count($aData), 1) . ";";
            return $this->pdo->prepare($this->sSql)->execute(array_values($this->parsingValuesQuery($aData)));
        } catch (PDOException $e) {
            $this->err_msg['error'] = $e->getMessage();
            $this->err_msg['errorInfo'] = $this->pdo->errorInfo();
            $this->err_msg['errorCode'] = $this->pdo->errorCode();

            return false;
        } catch (Throwable $e) {
            $this->err_msg['error'] = $e->getMessage();
            $this->err_msg['errorInfo'] = $this->pdo->errorInfo();
            $this->err_msg['errorCode'] = $this->pdo->errorCode();

            return false;
        }
    }

    public function insertMultiple(string $sBaseDatos, string $sTable, array $aRows): bool {
        try {
            $aInsert = array();
            $this->sSql = "INSERT INTO " . $sBaseDatos . "." . $sTable . " (" . implode(', ', array_keys($aRows[0])) . ") VALUES " . $this->argsInsert(count($aRows[0]), count($aRows)) . ";";

            foreach ($aRows as $i => $aData) {
                $aInsert = array_merge_recursive($aInsert, array_values($this->parsingValuesQuery($aData)));
            }

            return $this->pdo->prepare($this->sSql)->execute($aInsert);
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateSingle(string $sBaseDatos, string $sTable, array $aData, array $aWhere): bool {
        try {
            $this->sSql = "UPDATE " . $sBaseDatos . "." . $sTable . " SET " . implode(" = ?, ", array_keys($aData)) . " = ? WHERE " . implode(" = ? AND ", array_keys($aWhere)) . " = ?;";
            return $this->pdo->prepare($this->sSql)->execute(array_values($this->parsingValuesQuery(array_merge_recursive(array_values($aData), array_values($aWhere)))));
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function execQueryList() {
        try {
            $this->List = array();
            $stm = $this->pdo->prepare($this->sSql);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $this->List[] = (object) $this->parsingValuesQuery($r);
            }

            return $list;
        } catch (Exception $e) {
            
        }
    }

    protected function magicQuotes(string $Text): string {
        if (!get_magic_quotes_gpc()) {
            return addslashes($Text);        }

        return $Text;
    }

    protected function parsingValuesQuery($DataArray): array {
        try {
            $Parsing = array();

            foreach ($DataArray as $key => $value) {
                $nepdo.classw = is_null($value) ? null : (
                    is_numeric($value) ? (strpos((string) $value, '.') === false ? (int) $value : (float) $value) : (
                        is_string($value) ? (strlen($value) > 0 ? $this->magicQuotes(utf8_encode($value)) : '') : null
                    )
                );

                $Parsing[$key] = $new;
            }

            return $Parsing;
        } catch (Exception $e) {
        }
    }

    protected function argsInsert(int $iColumnLength, int $iRowLength): string {

        $iLength = $iRowLength * $iColumnLength;

        return implode(',', array_map(
            function ($el) {
                return '('.implode(',', $el).')';
            },
            array_chunk(array_fill(0, $iLength, '?'), $iColumnLength)
        ));
    }

}
