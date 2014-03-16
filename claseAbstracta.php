<?php
/*
	Conector a base de datos + sistema de consultas realizado con clase abstracta + final.
	Como contrapunto más popular a este ejemplo tenemos el Singleton
*/

// Ejemplo de creación y consulta
//$DB = DB::Open();
//$resultado = $DB->qry(" {SQL Statement} ;");

    abstract class Objeto_bbdd
    {
        protected static $DB_Name;
        protected static $DB_Open;
        protected static $DB_Conn;
		protected static $DB_Charset;

        protected function __construct($database, $hostname, $hostport, $username, $password)
        {
            self::$DB_Name = $database;
            self::$DB_Conn = mysql_connect($hostname . ":" . $hostport, $username, $password);
			self::$DB_Charset = mysql_set_charset("UTF8", self::$DB_Conn);
            if (!self::$DB_Conn) { die('Error Crítico: bbdd Error <br />' . mysql_error()); }
            mysql_select_db(self::$DB_Name, self::$DB_Conn);
        }

        private function __clone() {}

        public function __destruct()
        {
        }
    }

    final class DB extends Objeto_bbdd
    {
        public static function Open($database, $hostname = "localhost", $hostport = "3306", $username = "usuario", $password = "pass")
        {
            if (!self::$DB_Open)
            {
                self::$DB_Open = new self($database, $hostname, $hostport, $username, $password);
            }
            else
            {
                self::$DB_Open = null;
                self::$DB_Open = new self($database, $hostname, $hostport, $username, $password);
            }
            return self::$DB_Open;
        }

	/** Funcion query -- Se le pasa por defecto la consulta a resolver y, dependiendo del valor del segundo parametro devuelve:
	*   $parametros ::				- Consulta a ejecutar
	*
	*	$formato_retorno :: 		1 - La primera fila
	*								2 - La primera fila en forma de array
	*								3 - La primera Celda con el primer parametro
	*								4 - La segunda Celda con el primer parametro, útil en caso de descipciones en fichas
	*								defecto - Todo el resultado
	*
	*	devuelve  ::				- Resultado de la consulta
	*/						

        public function qry($sql, $formato_retorno = 0)
        {
            $query = mysql_query($sql, self::$DB_Conn) OR die(mysql_error());
            switch ($formato_retorno)
            {
                case 1:
                    $query = mysql_fetch_row($query);
                    return $query;
                    break;
                case 2:
                    $query = mysql_fetch_array($query);
                    return $query;
                    break;
                case 3:
                    $query = mysql_fetch_row($query);
                    $query = $query[0];
                    return $query;
				case 4:
                    $query = mysql_fetch_row($query);
                    $query = $query[1];
                    return $query;
                default:
                    return $query;
            }
        }
    }
?>