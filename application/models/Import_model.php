<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_model extends CI_Model {

  private $_config_table = "config";

  private $_research_fields_table = "research_fields";
  private $_research_levels_table = "research_levels";

  private $_buildings_table = "buildings";
  private $_buildings_levels_table = "buildings_levels";

  private $_units_table = "units";
  private $_units_levels_table = "units_levels";

  private $_users_builders_table = "users_builders";
  private $_users_buildings_table = "users_buildings";
  private $_users_units_table = "users_units";
  private $_users_research_table = "users_research";
  private $_users_researchers_table = "users_researchers";

  private $_main_research_fields_ids = array();
  private $_building_ids = array();
  private $_unit_ids = array();

  private $_import_data_path = "/";

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->_import_data_path = $this->config->item("import_data_path");
  }

  public function reset_database() {
    $this->db->truncate($this->_config_table);

    $this->db->truncate($this->_research_fields_table);
    $this->db->truncate($this->_research_levels_table);

    $this->db->truncate($this->_buildings_table);
    $this->db->truncate($this->_buildings_levels_table);

    $this->db->truncate($this->_units_table);
    $this->db->truncate($this->_units_levels_table);

    $this->db->truncate($this->_users_builders_table);
    $this->db->truncate($this->_users_buildings_table);
    $this->db->truncate($this->_users_units_table);
    $this->db->truncate($this->_users_research_table);
    $this->db->truncate($this->_users_researchers_table);
  }

  public function config() {
    $this->load->model("update_model");
    $config = array(
      "update_time" => time(),
      "update_interval" => $this->config->item("update_interval"),
      "round_number" => 0,
      "users_amount" => $this->update_model->get_users_amount()
    );
    foreach ($config as $name => $value) {
      $this->db->insert(
        $this->_config_table, array("name" => $name, "value" => $value)
      );
    }
  }

  // RESEARCH FIELDS

  public function research_main_fields()
  {
    $fields = array();
    $xmls = $this->get_xml_data_by_folder("");
    foreach ($xmls as $xml)
    {
      $data = array(
        "parent_id" => 0,
        "name" => (string)$xml->attributes()[0],
        "title" => (string)$xml->research->title,
        "text" => (string)$xml->research->text
      );
      $this->db->insert(
        $this->_research_fields_table, $data
      );
      $field_id = $this->db->insert_id();
      $this->_main_research_fields_ids[$data["name"]] = $field_id;
      foreach ($xml->research->levels->level as $level)
      {
        $level_attributes = $level->attributes();
        $data = array(
          "field_id" => $field_id,
          "number" => (int)$level_attributes->num,
          "researchers" => (int)$level_attributes->researchers,
          "experience" => (int)$level_attributes->experience
        );
        $this->db->insert(
          $this->_research_levels_table, $data
        );
      }
    }
  }

  public function research_sub_fields() {
    if (count($this->_main_research_fields_ids) > 0)
    {
      foreach ($this->_main_research_fields_ids as $field_name => $field_id)
      {
        $xmls = $this->get_xml_data_by_folder($field_name);
        foreach ($xmls as $xml)
        {
          $data = array(
            "parent_id" => $field_id,
            "name" => (string)$xml->attributes()[0],
            "title" => (string)$xml->research->title,
            "text" => (string)$xml->research->text
          );
          $this->db->insert(
            $this->_research_fields_table, $data
          );
          $sub_field_id = $this->db->insert_id();
          foreach ($xml->research->levels->level as $level)
          {
            $level_attributes = $level->attributes();
            $data = array(
              "field_id" => $sub_field_id,
              "number" => (int)$level_attributes->num,
              "researchers" => (int)$level_attributes->researchers,
              "experience" => (int)$level_attributes->experience
            );
            $this->db->insert(
              $this->_research_levels_table, $data
            );
          }
        }
      }
    }
  }

  // BUILDINGS

  public function buildings()
  {
    $buildings = array();
    $xmls = $this->get_xml_data_by_folder("buildings");
    foreach ($xmls as $xml) {
      $data = array(
        "name" => (string)$xml->attributes()[0],
        "title" => (string)$xml->title,
        "text" => (string)$xml->text
      );
      $this->db->insert(
        $this->_buildings_table, $data
      );
      $building_id = $this->db->insert_id();
      $this->_building_ids[$data["name"]] = $building_id;
      foreach ($xml->levels->level as $level)
      {
        $level_attributes = $level->attributes();
        $data = array(
          "building_id" => $building_id,
          "number" => (int)$level_attributes["num"],
          "rl_number" => (int)$level_attributes["rl-num"],
          "c_wood" => (int)$level_attributes["c-wood"],
          "c_stones" => (int)$level_attributes["c-wood"],
          "c_workers" => (int)$level_attributes["c-workers"],
          "c_rounds" => (int)$level_attributes["c-rounds"],
          "volume" => (int)$level_attributes["volume"]
        );
        $this->db->insert(
          $this->_buildings_levels_table, $data
        );
      }
    }
  }

  // UNITS

  public function units()
  {
    $buildings = array();
    $xmls = $this->get_xml_data_by_folder("units");
    foreach ($xmls as $xml) {
      $data = array(
        "name" => (string)$xml->attributes()[0],
        "title" => (string)$xml->title,
        "text" => (string)$xml->text
      );
      $this->db->insert(
        $this->_units_table, $data
      );
      $unit_id = $this->db->insert_id();
      $this->_unit_ids[$data["name"]] = $unit_id;
      foreach ($xml->levels->level as $level)
      {
        $level_attributes = $level->attributes();
        $data = array(
          "unit_id" => $unit_id,
          "number" => (int)$level_attributes["num"],
          "rl_number" => (int)$level_attributes["rl-num"],
          "t_coins" => (int)$level_attributes["t-coins"],
          "t_rounds" => (int)$level_attributes["t-rounds"],
          "volume" => (int)$level_attributes["volume"]
        );
        $this->db->insert(
          $this->_units_levels_table, $data
        );
      }
    }
  }

  // HANDLE XML

  function get_xml_data_by_folder_file($folder, $file)
  {
    if ($folder == "")
      $full_folder_file = $this->_import_data_path.$file;
    else
      $full_folder_file = $this->_import_data_path.$folder."/".$file;
    $xml_string = file_get_contents($full_folder_file);
    $xml = simplexml_load_string($xml_string)
      or die("Error: Cannot create xml object");
    return $xml;
  }

  function get_xml_data_by_folder($folder)
  {
    if ($folder == "")
      $full_folder = $this->_import_data_path;
    else
      $full_folder = $this->_import_data_path.$folder."/";
    $files = scandir($full_folder);
    $xmls = array();
    foreach ($files as $file)
    {
      if ($file != "." && $file != ".." && is_file($full_folder.$file) == TRUE) {
        $xmls[] = $this->get_xml_data_by_folder_file($folder, $file);
      }
    }
    return $xmls;
  }

}