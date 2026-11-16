
<?php

class HomeController extends Controller
{
    public function index()
    {
        $this->call->view('landing');
    }
}
