<?php
class Pages extends Controller {
    public function __construct() {
    }

    public function index() {
        $data = [
            'title' => 'Welcome to CMS Sederhana',
            'description' => 'Simple CMS built with custom MVC framework'
        ];
        
        $this->view('pages/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'About our CMS'
        ];
        
        $this->view('pages/about', $data);
    }
} 