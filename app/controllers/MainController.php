<?php

class MainController {
    
    // Page 1: Landing
    public function index() {
        require_once __DIR__ . '/../views/landing.php';
    }

    // Page 2: Explore/Listings
    public function explore() {
        require_once __DIR__ . '/../views/explore.php';
    }

    // We will add more methods here as we go!
}