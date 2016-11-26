<?php

/**
 * Created by PhpStorm.
 * User: josua
 * Date: 25/11/2016
 * Time: 18:30
 */
class Migration extends CI_Controller
{
    public function index(){
        $this->load->library('migration');

        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        }
    }
}