<?php

class counter {
     var $step;
     var $count; 

     function getcount() {
          return $this->count;
     }
 
     function getstep() {
          return $this->step;
     }

     function changestep($newval) {
          if(is_integer($newval))
          $this->step = $newval;
     }

     function step() {
          $this->count += $this->step;
     }

     function reset() {
          $this->count = 0;
          $this->step = 1;
     }
}
?>