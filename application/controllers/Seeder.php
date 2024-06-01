<?php

class Seeder extends CI_Controller
{

        public function index()
        {
            $this->load->model('api_model', 'model');
            $this->model->seeder();
        }

}