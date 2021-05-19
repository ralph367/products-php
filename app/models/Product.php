<?php
    class Product
    {
        private $db;

        public function __construct()
        {
            $this->db = new Database;
        }

        public function getProducts()
        {
            $this->db->query('SELECT * FROM products');
            $results = $this->db->resultSet();

            return $results;
        }

        public function getProductsType()
        {
            $this->db->query('SELECT * FROM productstype');
            $results = $this->db->resultSet();

            return $results;
        }

        public function addProduct($data)
        {
            $this->db->query('INSERT INTO products (`SKU`, `Name`, `Price`, `TypeName`, `TypeAttributes`) VALUES (:sku, :productname, :price, :typeName, :typeAtt)');
            // Bind values
            $this->db->bind(':sku', $data['sku']);
            $this->db->bind(':productname', $data['name']);
            $this->db->bind(':price', $data['price']);
            $this->db->bind(':typeName', $data['typeName']);
            $this->db->bind(':typeAtt', $data['typeAtt']);

            // Execute
            if($this->db->execute())
            {
                return true;
            }else {
                return false;
            }
        }

		public function getProductById($sku)
        {
			$this->db->query('SELECT * FROM products WHERE sku = :sku');
			$this->db->bind(':sku', $sku);
			$row = $this->db->single();
			return $row;
		}
        
        public function deleteProduct($sku)
        {
            $this->db->query('DELETE FROM products WHERE SKU in '.$sku);

            // Execute
            if($this->db->execute())
            {
                return true;
            }else {
                return false;
            }
        }
    }