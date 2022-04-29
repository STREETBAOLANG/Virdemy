<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller { 
    public function __construct() {
        parent:: __construct();
		$this->load->model('course_model');
		$this->load->model('file_model');
        $this->load->model('user_model');
    }

    public function index()
	{
        $query = $this->course_model->get_courses();
        $courses = [];
        foreach ($query->result() as $row) {
            // Get course picture file path
            $course_id = $row->course_id;
            $course_pic = $this->file_model->get_file($file_table = 'course_image', $course_id);
            $pic_string = $course_pic->row()->path;
            $pic_path = explode("/",$pic_string);
            // Get course creator name
            $creator_id = $row->creator_id;
            $user_data = $this->user_model->get_user_by_id($creator_id);
            $course = array(
                'course_id' => $course_id,
                'course_pic' => base_url(). "uploads/" .end($pic_path),
                'course_name' => $row->course_name, 
                'description' => $row->course_description, 
                'creator' => $user_data['name'],
                'price' => $row->price, 
            );
            array_push($courses,$course);
            }

            // print "<pre>";
            // print_r($courses);
            // print "</pre>";

            
		$data = array('courses' => $courses, 'query'=>'', 'page'=>'home');
		$this->load->view('template/header', $data);
		$this->load->view('home', $data);
        $this->load->view('template/footer');
	}

    public function search() {
        $query = $this->input->post('query');
        if ($query) {
            # code...
            $result = $this->course_model->fetch_data($query);
            $courses = [];

            foreach ($result->result() as $row) {
                $course_id = $row->course_id;
                $course_pic = $this->file_model->get_file($file_table = 'course_image', $course_id);
                $pic_string = $course_pic->row()->path;
                $pic_path = explode("/",$pic_string);
                // Get course creator name
                $creator_id = $row->creator_id;
                $user_data = $this->user_model->get_user_by_id($creator_id);
                $course = array(
                    'course_id' => $course_id,
                    'course_pic' => base_url(). "uploads/" .end($pic_path),
                    'course_name' => $row->course_name, 
                    'description' => $row->course_description, 
                    'creator' => $user_data['name'],
                    'price' => $row->price, 
                );
                array_push($courses,$course);
            }

            $data = array('courses' => $courses, 'query'=>$query, 'page'=>'');
            $this->load->view('template/header', $data);
            $this->load->view('home', $data);
            $this->load->view('template/footer');
        } else {
            redirect('home');
        }
        
    }
}
