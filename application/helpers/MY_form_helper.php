<?php 
function country_dropdown($countries, $name="countries", $top_countries=array(), $selection=NULL, $show_all=TRUE, $withflag=TRUE)
{
  $html = "<select name='{$name}' id='{$name}'>";
  $html2 ="<li>";
  
  $selected = NULL;
 

  if (in_array($selection, $top_countries)) {
    $top_selection = $selection;
    $all_selection = NULL;
  } else {
    $top_selection = NULL;
    $all_selection = $selection;
  }
 

  /*For all of the Top Countries */
  if (!empty($top_countries)) {
    foreach ($top_countries as $value)
    {
      if (array_key_exists($value, $countries)) {
        if ($value === $top_selection) {
          $selected = "SELECTED";
        }

        $image=img('img/flags/'.$value.'.png');
        $calling_code=$countries[$value]['calling_code'];
        $name=$countries[$value]['name'];
        
        $html .= "<option value='{$value}' {$selected}>&nbsp;{$name}</option>";
        $html2 .= "<a id='+{$calling_code}'>{$image}&nbsp;{$name} &nbsp; +{$calling_code}</a>";

        $selected = NULL;
      }
    }
    $html .= "<option>----------</option>";
    $html2 .= "&nbsp;-------------------------------------------&nbsp;";
  }
 
 /*For all of the Top Countries */
  if ($show_all) {
    foreach ($countries as $key => $country)
    {
      if ($key === $all_selection) {
        $selected = "SELECTED";
      }
      
      $calling_code=$country['calling_code'];
      $name=$country['name'];
      $imageproperties=array('src'=>'img/flags/'.$key.'.png',
                              'class'=>'flags'
                            );
      $image=img($imageproperties);

      $html .= "<option value='{$key}' {$selected}>&nbsp;{$name}</option>";
      $html2 .= "<a id='+{$calling_code}'>{$image}&nbsp;{$name}&nbsp; +{$calling_code}</a>";
      $selected = NULL;
    }
  }
 
  $html .= "</select>";
  $html2 .= "</li>";

  if($withflag){
    return $html2;
  }
  return $html;
}

?>