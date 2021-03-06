<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Research extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->view->layout = "research";
    $this->view->title = "Forschung - ";
  }

  public function index()
  {
    $this->fields();
  }

  public function fields(
                    $selected_field_id = 0,
                    $action = NULL, $action_field_id = 0, $action_level_id = 0
                  )
  {
    $this->load->model("research_model");

    $this->load->model("update_model");
    $config = $this->update_model->load_config();
    $this->research_model->set_update_config($config);

    $data["action"] = $action;
    if ($action == "start" && $action_field_id > 0 && $action_level_id > 0)
    {
      $data["action_status"] = $this->research_model->start_research(
        $action_field_id, $action_level_id
      );
    }
    else if ($action == "cancel" && $action_field_id > 0 && $action_level_id > 0)
    {
      $data["action_status"] = $this->research_model->cancel_research(
        $action_field_id, $action_level_id
      );
    }

    $data["selected_field_id"] = $selected_field_id;
    $data["main_research_fields"] = $this->research_model->get_fields_list(
      0, $selected_field_id == 0
    );
    if ($selected_field_id == 0)
    {
      $data["research_fields"] = $data["main_research_fields"];
      $this->view->title .= "Allgemein";
    }
    else
    {
      $data["research_fields"] = $this->research_model->get_fields_list(
        $selected_field_id, TRUE
      );
      $this->view->title .=
        $data["main_research_fields"][$selected_field_id]["title"];
    }


    $data["round_number"] = $config["round_number"];
    $data["update_interval"] = $config["update_interval"];

    $this->view->data = $data;
    $this->view->page = "research/fields";
    $this->view->load();
  }

}