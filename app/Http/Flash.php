<?php
namespace App\Http;

class Flash
{
    public function info($title, $message)
    {
        session()->flash('info', ['title' => $title, 'message' => $message]);
    }
}