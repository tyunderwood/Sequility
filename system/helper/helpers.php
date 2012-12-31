<?php
final class HelperHelpers {

    //this function will output the dropdown content    
    function dropdown($carray,$sel='',$val_as_key=false) {

        if(is_array($carray)) {
            foreach($carray as $key=>$val) {            
                //if the value array is multidimentional array, we will loop extract the content                
                if(is_array($val)) list($key,$val)=array_values($val);
                if($val_as_key == true)$key=$val;
                $select = $key == $sel ? 'selected':'';
                $content.="<option value='".$key."' $select>".$val."</option>";
            }
            
            return $content;
        }
    }
    
    function serialdata($data) {
        return serialize(data);
    }
    
    function unserialdata($data) {
        return unserialize(data);
    }
    
}
