<?php
class Product
{

    // conexion a bd y nombre de tabla
    private $conn;
    private $table_name = "products";

    // atributos de objeto
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    // constructor de objeto con parametro de conexion a db
    public function __construct($db)
    {
        $this->conn = $db;
    }
    // lectura de productos
    public function read()
    {
        // obtener todos los productos de la talba
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created DESC";
        // preparar query
        $stmt = $this->conn->prepare($query);
        // ejecutar query
        $stmt->execute();
        return $stmt;
    }

    // creacion de producto
    public function create()
    {

        // query de insert
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

        // preparar query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind de valores en query
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);

        // ejecutar query
        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    // usado al momendo de hacer update a un producto - llenar datos
    public function readOne()
    {
        // query para obtener un producto especifico
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? LIMIT 0,1";

        // prepar query
        $stmt = $this->conn->prepare($query);

        // bind de id del producto
        $stmt->bindParam(1, $this->id);

        // ejecuta query
        $stmt->execute();

        // obtener datos
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // setea valor de resultado a objeto producto
        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    // metodo para actualizar datos de un producto
    public function update()
    {

        // query de update
        $query = "UPDATE " . $this->table_name . " SET name = :name, price = :price, description = :description, category_id = :category_id WHERE id = :id";

        // preparo query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind de nuevos valores para query
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // ejecuto query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // metodo para eliminar un producto
    public function delete()
    {

        // query de eliminacion
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        // preparar query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind valor id del producto a eliminar en query
        $stmt->bindParam(1, $this->id);

        // ejecutar query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // busqueda de productos mediante keyword
    public function search($keywords)
    {

        // query de busqueda
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? ORDER BY p.created DESC";

        // preparo query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // bind de keywords al query
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // ejecuto query
        $stmt->execute();

        return $stmt;
    }

    // obtiene productos con paginacion
    public function readPaging($from_record_num, $records_per_page)
    {

        // query de productos
        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created DESC LIMIT ?, ?";

        // perparo query
        $stmt = $this->conn->prepare($query);

        // bind de valores para el query
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // ejecuto query
        $stmt->execute();

        // retorno valores
        return $stmt;
    }

    //usado en la paginacion de productos
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}
