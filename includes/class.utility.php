<?php

class counter {
     private $step;
     private $count; 

     public function getcount() {
          return $this->count;
     }
 
     public function getstep() {
          return $this->step;
     }

     public function changestep($newval) {
          if(is_integer($newval))
          $this->step = $newval;
     }

     public function step() {
          $this->count += $this->step;
     }

     public function reset() {
          $this->count = 0;
          $this->step = 1;
     }
}
?>