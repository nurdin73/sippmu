<?php

class Example extends Api_Controller
{
    public function index_get()
    {
        $this->returnResponse([
            'data' => $_ENV['EXPIRED_TOKEN']
        ], 'success', 200);
    }
}
