<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\WeeklyRota;
use Faker\Factory as Faker;

class RotaController extends Controller
{
    public function index()
    {
        $rota = new WeeklyRota(332);
        $table = $rota->getTable();
        $faker = Faker::create();
        return view('rota.index', compact('table', 'faker'));
    }
}
