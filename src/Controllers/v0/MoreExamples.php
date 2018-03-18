<?php
namespace Gvera\Controllers\v0;

use Gvera\Controllers\GvController;

class MoreExamples extends GvController
{
    public function index()
    {
        echo "2nd level controller!";
    }

    public function other()
    {
        echo "another!";
    }
}