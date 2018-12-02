<?php
/**
 * PHP Light MVC.
 * Extremely small but powerful MVC framework for PHP.
 * @package PHP Light MVC
 * @version 1.5.1
 * @author Sławomir Kokłowski {@link http://www.kurshtml.edu.pl}
 * @copyright Do NOT remove this comment!
 * @license LGPL
 */
function __autoload($className)
{
    if (preg_match('/^[a-z][0-9a-z]*(_[0-9a-z]+)*$/i', $className))
    {
        $file = dirname(__FILE__) . '/' . str_replace('_' , '/', $className);
        if (file_exists($path = $file . '.php') || file_exists($path = $file . '.class.php'))
        {
            require_once $path;
            return;
        }
    }
    _Controller::http404();
}
abstract class _Model
{
    static $dsn;
    static $user;
    static $password;
    protected static $db;
    protected $className;
    protected $sql = array();
    private $sth = array();
    function __construct()
    {
        if (empty(self::$db) && !empty(self::$dsn))
        {
            self::$db = new PDO(self::$dsn, self::$user, self::$password);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$db->query ('SET NAMES utf8');
        }
    }
    protected function execute($name, $arguments=array())
    {
        if (!array_key_exists($name, $this->sql)) throw new Exception('Execute of undefined sql ' . $name);
        if (!array_key_exists($name, $this->sth)) $this->sth[$name] = self::$db->prepare($this->sql[$name]);
        foreach ($arguments as $key => $value)
        {
                    switch (gettype($value))
                    {
                        case 'boolean':
                            $type = PDO::PARAM_BOOL;
                            break;
                        case 'integer':
                            $type = PDO::PARAM_INT;
                            break;
                        case 'NULL':
                            $type = PDO::PARAM_NULL;
                            break;
                        default:
                            $type = PDO::PARAM_STR;
                    }
                    $this->sth[$name]->bindValue($key, $value, $type);
                }
        $this->sth[$name]->execute();
        $result = array();
        if (preg_match('/^[^A-Z_]*SELECT[^A-Z_]/i', $this->sql[$name]))
        {
            while (($object = $this->className ? $this->sth[$name]->fetchObject($this->className) : $this->sth[$name]->fetchObject())) $result[] = $object;
        }
        else
        {
            $object = (object)array('count' => $this->sth[$name]->rowCount());
            if (preg_match('/^[^A-Z_]*(INSERT|REPLACE)[^A-Z_]/i', $this->sql[$name])) $object->id = self::$db->lastInsertId();
            $result[] = $object;
        }
        return $result;
    }
    
    function __call($name, $arguments)
    {
        if (!array_key_exists($name, $this->sql)) throw new Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
        return $this->execute($name, array_key_exists(0, $arguments) ? $arguments[0] : array());
    }
}
class _View
{
    static $dir = '';
    static $var = array();
    protected $file;
    protected $data = array();
    function __construct($file)
    {
        $this->file = $file;
    }
    
    function __get($name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }
    
    function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    function __toString()
    {
        if (!file_exists(self::$dir . $this->file)) return '';
        foreach (array_merge(self::$var, $this->data) as $name => $value)
        {
            if ($name != 'this') $$name = $value;
        }
        unset($name, $value);
        $_config = _Controller::$config;
        $_dir = self::$dir;
        ob_start();
        require $_dir . $this->file;
        _Controller::$config = $_config;
        return ob_get_clean();
    }
}
abstract class _Controller
{
    static $config;
    static function http404()
    {
      //header("HTTP/1.0 404 Not Found"); 
        //include('/index.php?action=error404'); 
        //die;
       

         echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        exit;
    }    
    static function httpRedirect($location, $status=302)
    {
        $location = preg_replace(array('/^([^\r\n]+)/', '/(^|\/)\.(\/|$)/', '/[^\/]*\/\.\.(\/|$)/'), array('$1', '$1', ''), $location);
        header('Location: ' . (preg_match('/^[0-9a-z.+-]+:/i', $location) ? '' : 'http://' . $_SERVER['SERVER_NAME'] . (preg_match('/^\//', $location) ? '' : rTrim(dirName($_SERVER['SCRIPT_NAME']), '/') . '/')) . $location, true, $status);
        exit;
    }
    
    function __call($name, $arguments)
    {
        self::http404();
    }
}
?>