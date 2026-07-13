<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;


class StepController extends Controller
{
    public function update(Step $step)
    {
        $step->update(['completed'=> ! $step->completed]);

        return back();
    }

}
