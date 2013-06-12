<?php
class Countries extends CI_Model {
    function get_dropdownlist()
    {
      $countries = array();
      $countries_get = $this->db->get('countries');
      foreach ($countries_get->result() as $country)
      {
        $countries[$country->iso2]= array('name'=>$country->short_name,
                          'calling_code'=>$country->calling_code);
      }
      return $countries;
    }

}
?>