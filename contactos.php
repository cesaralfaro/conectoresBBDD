<?PHP
class conectorBBDD
{
    protected $link;
    private $servidor, $usuario, $pass, $db;
    
    public function __construct($servidor, $usuario, $pass, $db)
    {
        $this->servidor = $servidor;
        $this->usuario = $usuario;
        $this->pass = $pass;
        $this->db = $db;
        $this->conectar();
    }
    
    private function conectar()
    {
        $this->link = mysql_connect($this->servidor, $this->usuario, $this->pass);
        mysql_select_db($this->db, $this->link);
    }
    
    public function __sleep()
    {
        return array('servidor', 'usuario', 'pass', 'db');
    }
    
    public function __wakeup()
    {
        $this->conectar();
    }
	
	public function __get($nombrevar)
	{
		echo "La variable ".$nombrevar." no existe. Esto es en __GET<br>";	
	}
	
	public function __set($nombrevar, $valor)
	{
		echo "La variable ".$nombrevar." no existe. Estas intentando darle el valor ".$valor.". Esto es en __SET<br>";	
	}
		
	/** Funcion consulta -- Se le pasa por defecto la consulta a resolver y, dependiendo del valor del segundo parametro devuelve:
	*   $parametros ::				- Consulta a ejecutar
	*
	*	$formato_retorno :: 		1 - Una fila en formato fila
	*								2 - Una fila en formato de array
	*								3 - La primera Celda con el primer parametro
	*								4 - La segunda Celda con el primer parametro, útil en caso de descipciones en fichas
	*								defecto - Todo el resultado
	*								5 - Devuelve todos los registros en formato Array
	*								6 - Devuelve todos los registros en formato JSON
	*
	*	devuelve  ::				- Resultado de la consulta
	*/						

	public function consulta($sql, $formato_retorno = 0)
	{
		$query = mysql_query($sql, $this->link) OR die(mysql_error());
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
			break;
			case 4:
				$query = mysql_fetch_row($query);
				$query = $query[1];
				return $query;
			break;
			case 5:
				while($fila[] = mysql_fetch_array($query)){}
				return $fila;
			break;
			case 6:
				while($fila[] = mysql_fetch_array($query)){}
				return json_encode(array("results"=>$fila));
			break;
			default:
				return $query;
			break;
		}
	}
}

	// Creo el objeto conector que realizará la conexión a Base de datos
	$objConectorBBDD = new conectorBBDD("localhost","root","","contactos");
	
	// La consulta que voy a realizar al motor de bases de datos
	$consulta = "SELECT Nombre,Alias FROM usuarios WHERE Ingresos_mensuales>1200 AND Sexo='Mujer';";
	
	// Llamada al método de consultas (el constructor ya ha creado en enlace)
	// Se le pasa la consulta y un segundo parámetro que me gestionará la vuelta
	$resultado = $objConectorBBDD->consulta($consulta,5);
	
	//Si quiero ver el array que me genera, descomento esa línea
	//print_r($resultado);
	
	//En caso de devolverlo en modo Array, uso esto.
	foreach($resultado as $contacto)
		echo $contacto['Nombre']." ". $contacto['Apellidos'] . "<br> ";


	// En caso de ser un JSON, uso esto
	//$json = json_decode($resultado,true);
	//print_r($json['results'][0][3]);


	//En caso de devolverlo en modo Filas con indice numerico, uso esto.
	//echo $resultado[1];


// Si asigno un valor a una variable que no existe, dispara el método mágico __set
//$objConectorBBDD->variablequenoexiste = 2;

// Si imprimo un valor de una variable que no existe, dispara el método __get
//echo $objConectorBBDD->variablequenoexiste;

?>