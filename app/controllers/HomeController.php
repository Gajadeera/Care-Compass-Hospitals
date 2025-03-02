<?php
class HomeController
{
    public function index()
    {
        require __DIR__ . '/../views/Home/home.php';
    }
    public function about()
    {
        require __DIR__ . '/../views/Home/about.php';
    }
    public function contact()
    {
        require __DIR__ . '/../views/Home/contact.php';
    }
    public function service()
    {
        require __DIR__ . '/../views/Home/service.php';
    }
    public function register()
    {
        require __DIR__ . '/../views/Home/register.php';
    }
}
