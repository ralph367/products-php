<?php
    class ProductType
    {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getProductsType(){
            $this->db->query('SELECT * FROM productstype');
            $results = $this->db->resultSet();
            return $results;
        }

        public function addProductType($data){
            $this->db->query('INSERT INTO productstype (`Name`, `Attributes`) VALUES (:typename, :attributes)');
            // Bind values
            $this->db->bind(':typename', $data['typename']);
            $this->db->bind(':attributes', stripslashes(json_encode($data['attributes'])));
            // Execute
            if($this->db->execute()){
                return true;
            }else {
                return false;
            }
        }
    }