<?PHP
/*
	Ejemplo de conexión a base de datos bajo el estandar Singleton.
	Como contrapunto popular podemos recurrir a una clase abstracta y/o final
*/

final class PDOSingleton{

     private static $dsn       ;
     private static $username  = 'usuario';
     private static $password  = 'pass';
	 private static $options   = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);

     private static $instance;

     private function __construct(){}

     
    /**
	*[Función pública modelada con un patrón singleton encargada de devolver 
	* una instancia de conexión a la BBDD siempre y cuando és no exista]
	*
	*@return estamento PDO con la conexión a la BBDD
	*/
     public static function getInstance($bbdd){

     	    
     	    self::$dsn = 'mysql:dbname=basedatos;host=localhost';
     	
            if(!isset(self::$instance)){
                self::$instance = new PDO(self::$dsn, self::$username, self::$password, self::$options);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$instance;
     }

    public function __clone(){

           trigger_error('No se puede clonar',E_USER_ERROR);
    }
}

?>