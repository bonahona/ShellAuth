<?php
class DefaultController extends Controller
{
    public function Index()
    {
        return $this->Json('Invalid request');
    }
}