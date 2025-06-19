<?php

final class DataRepository 
{
    private $_connect_db;
    public function __construct(){
        $this->_connect_db = new Database;
    }

    public function payment_details(){
        $this->_connect_db->query(/** @lang text */"SELECT * FROM `payment_details`");
        $row = $this->_connect_db->resultSet();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }
    
    public function getAllUsers() {
        $this->_connect_db->query(/** @lang text */"
            SELECT 
                users.id,
                users.name,
                users.email,
                users.role_id,
                users.telephone,
                roles.name AS role_name,
                users.created_at,
                users.updated_at
            FROM users
            INNER JOIN roles ON users.role_id = roles.id
        ");
        $row = $this->_connect_db->resultSet();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }


    public function getInvoiceLastNumber() {
        $this->_connect_db->query(/** @lang text */'SELECT number FROM commercial_invoice_number ORDER BY number DESC LIMIT 1');
        $row = $this->_connect_db->single();
        return ($row) ? $row : false;
    }

    public function getAllProducts(){
        $this->_connect_db->query(/** @lang text */"SELECT `id`, `title`, `binding`, `sale_price` FROM `books` ");
        $row = $this->_connect_db->resultSet();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }

    public function delete_product($id){
		$i = implode(',', $id);
		$this->_connect_db->query(/** @lang text */"DELETE FROM books WHERE id IN (".$i.")");
		$this->_connect_db->bind(':id', $id);
		if($this->_connect_db->execute()){
			return true;
		}else{
			return false;
		}
	}

    public function add_product($product_title, $product_binding, $product_price){
        $this->_connect_db->query(/** @lang text */"INSERT INTO `books`(title, binding, sale_price) VALUES (:product_title, :product_binding, :product_price)");
        $this->_connect_db->bind(':product_title', $product_title);
        $this->_connect_db->bind(':product_binding', $product_binding);
        $this->_connect_db->bind(':product_price', $product_price);
        if($this->_connect_db->execute()){
            return true;
        }else{
            return false;
        }
    }
}