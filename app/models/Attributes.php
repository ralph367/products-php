<?php
    class Attributes
    {
        public $name;
        public $description;
        public $type;

        public function __construct($name, $description, $type){
            $this->name = $name;
            $this->description = $description;
            $this->type = $type;
        }
       
    }