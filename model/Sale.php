<?php
require_once 'config/database.php';

class Sale {
    private $conn;
    private $table_name = "sales";

    public $id;
    public $total;
    public $sale_date;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET total=:total";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":total", $this->total);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function addDetail($sale_id, $product_id, $quantity, $unit_price, $subtotal) {
        $query = "INSERT INTO sale_details 
                  SET sale_id=:sale_id, product_id=:product_id, quantity=:quantity, 
                      unit_price=:unit_price, subtotal=:subtotal";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sale_id", $sale_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":unit_price", $unit_price);
        $stmt->bindParam(":subtotal", $subtotal);
        
        return $stmt->execute();
    }

    public function getDailySales($date = null) {
        if(!$date) $date = date('Y-m-d');
        
        $query = "SELECT SUM(total) as daily_total, COUNT(*) as sales_count 
                  FROM " . $this->table_name . " 
                  WHERE DATE(sale_date) = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $date);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMonthlySales($month = null, $year = null) {
        if(!$month) $month = date('m');
        if(!$year) $year = date('Y');
        
        $query = "SELECT SUM(total) as monthly_total, COUNT(*) as sales_count 
                  FROM " . $this->table_name . " 
                  WHERE MONTH(sale_date) = ? AND YEAR(sale_date) = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $month);
        $stmt->bindParam(2, $year);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentSales($limit = 10) {
        $query = "SELECT s.*, COUNT(sd.id) as items_count 
                  FROM " . $this->table_name . " s 
                  LEFT JOIN sale_details sd ON s.id = sd.sale_id 
                  GROUP BY s.id 
                  ORDER BY s.sale_date DESC 
                  LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
}
?>
