<?php
    function cari($input,$term = 'PT. Avia Avian, Sidoarjo, Indonesia'){
        $lowerTerm = strtolower($term);
        $splitTerm = str_split($lowerTerm);
        $countSplitTerm = count($splitTerm)-1;

        $lowerInput = strtolower($input);
        $inputSplit = str_split($lowerInput);
        $countInput = count($inputSplit);
        
        $firstInputKey = array_keys($splitTerm,$inputSplit[0]);
        $output = [];
        if(count($inputSplit) == 1){
            $output = $firstInputKey;
            return $output;
        }
        foreach ($firstInputKey as $key=>$val){
            if($val+$countInput-1< ($countSplitTerm)){
                $arrayTemp = array_slice($splitTerm,$val,$countInput);
                $tempchar = implode('',$arrayTemp);
                if($tempchar == $input){
                    array_push($output, $val);
                }
            }
        }
        
        return $output;
    }

    $value = cari('av');
    print_r($value);
?>
