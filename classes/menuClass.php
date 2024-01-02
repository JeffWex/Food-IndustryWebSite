<?php
   class Menu{
      public $id;
      public $prodName; //only accessable by the class and the child class
      public $lname; //only accessable by the class
      public $email;
      function __construct($id,$prodName,$lname,$email){
         $this->id = $id;
         $this->prodName = $prodName;
         $this->lname = $lname;
         $this->email = $email;
      }
    //   function display(){
    //      foreach($this as $value){
    //         echo "$value<br/>";
    //      }
    //   }
  
   }
?>