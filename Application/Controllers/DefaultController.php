<?php
class DefaultController extends Controller
{
    public function Index()
    {
        return $this->Json('Invalid request');
    }

    public function NotFound()
    {
        return $this->Json('Not found');
    }
}