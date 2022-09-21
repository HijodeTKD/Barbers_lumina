<?php
namespace Model;

// Mother model, it has the most used functions, CRUD, alerts generator...

class ActiveRecord {

    // Database
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alerts and messages
    protected static $alertas = [];
    
    // Defines DB conection
    public static function setDB($database) {
        self::$db = $database;
    }

    // Create an alert
    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Get all alerts that have been generated
    public static function getAlertas() {
        return static::$alertas;
    }

    //Validates
    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Query a SQL-query
    public static function consultarSQL($query) {
        // Database
        $resultado = self::$db->query($query);

        // Iterate results
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // Free memory
        $resultado->free();

        // Return all results 
        return $array;
    }

    // Creates an object that has same structure than db
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Identify and match the attributes of the database
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitize values before save it in DB
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Assign db values to memory objects
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // Records - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // All Records
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Find Record by id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = ${id}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Find Record by value
    public static function findValue($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE $columna = '${valor}'";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Find all Records by value
    public static function findValues($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE $columna = '${valor}'";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Static query SQL - Returns diferent results from especific tables 
    public static function SQL($query) {
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Get a numbre of ($limite) records 
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ${limite}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ; //Array shift se trae solo el primer resultado 
    }

    // Create a new record
    public function crear() {
        // Sanitize values
        $atributos = $this->sanitizarAtributos();

        // Insert into DB
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        // return json_encode(['query' => $query]); //Check if query.json is correct
        
        // Query result
        $resultado = self::$db->query($query);

        //Returns ['true/false', '$id']
        return [
            'resultado' =>  $resultado ,
            'id' => self::$db->insert_id
        ];
    }

    // Update a record
    public function actualizar() {
        // Sanitize
        $atributos = $this->sanitizarAtributos();

        // Iterate each value 
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // query
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Update a record
        $resultado = self::$db->query($query);

        // Retuns true/false
        return $resultado;
    }

    // Delete Record by ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }
}