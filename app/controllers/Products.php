<?php 
class Products extends Controller 
{
    public function __construct()
    {
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        // Get posts
        $products = $this->productModel->getProducts();
        $productsType = $this->productModel->getProductsType();

        $data = [
            'products' => $products,
            'productsType' => $productsType,
        ];

        $this->view('products/index', $data);
    }

    public function add()
    {
        $productsType = $this->productModel->getProductsType();
        $data['productsType'] = $productsType;
        $this->view('products/add', $data);
    }

    public function addProd()
    {
        $errors = [];
        $data = [];
        $attributes = ["sku", "name", "price", "typeName"]; // Default attributes of every product
        $typeAtt = []; // Array containing the special attributes of a specific product
        $typeAttributes = []; // Array containing the attributes of selected type
        if (!empty($_POST['typeName']))
        {
            // Get attributes of the selected type
            $productsType = $this->productModel->getProductsType();
            foreach($productsType as $type)
            {
                if ($_POST['typeName'] == $type->Name)
                {
                    $typeAttributes = json_decode($type->Attributes);
                } 
            }
            // Add the attributes of the selected type in the attributes array
            if(!empty($typeAttributes))
            {
                foreach($typeAttributes as $tatt)
                {
                    array_push($attributes,$tatt->name);
                }
            }
        }
        // Check if all attributes are added
        foreach($attributes as $att)
        {
            if (empty($_POST[$att]))
            {
                $errors[$att] = $att. ' is required.';
            } else {
                if ($att == 'sku' && $this->productModel->getProductById($_POST[$att]))
                {
                    $errors[$att] = $att.' is already used';
                }
                // Check if it is the right type of every field
                if ($att == 'price' && !is_numeric($_POST[$att]))
                {
                    $errors[$att] = $att.' attribute should be numeric';
                }
                if(!empty($typeAttributes))
                {
                    foreach($typeAttributes as $tatt)
                    {
                        if( $att == $tatt->name && !is_numeric($_POST[$att]))
                        {
                            $errors[$att] = $att.' attribute should be '. $tatt->type;
                        }
                    }
                }
            }
        }
    
        if (!empty($errors))
        {
            $data['success'] = false;
            $data['errors'] = $errors;
            
        } else {
            // Merge the special attributes into array to add them in  the database
            foreach($typeAttributes as $tatt)
            {
                array_push( $typeAtt,array($tatt->name => $_POST[$tatt->name]));
            }
            $productData['name'] = $_POST['name'];
            $productData['sku'] = $_POST['sku'];
            $productData['typeName'] = $_POST['typeName'];
            $productData['price'] = $_POST['price'];
            $productData['typeAtt'] = json_encode($typeAtt) ;
            if($this->productModel->addProduct($productData))
            {
                $data['success'] = true;
                $data['message'] = 'Success!';
            }else {
                die('Something went wrong');
            }
        }
        print_r( json_encode($data));
    }

    public function delete()
    {
        $errors = [];
        $data = [];
        if(!empty($_POST['skus']))
        {
            if($this->productModel->deleteProduct('("'.implode('","',$_POST['skus']).'")'))
            {
                $data['success'] = true;
                $data['message'] = 'Success!';
            }else {
                die('Something went wrong');
            }
        }
        print_r( json_encode($data));
    }
}