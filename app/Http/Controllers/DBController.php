<?php

namespace App\Http\Controllers;
use Spatie\DbDumper\Databases\MySql;

class DBController extends Controller
{
    public function exportDB(){
       MySql::create()
        ->setDbName(env("DB_DATABASE"))
        ->setUserName(env("DB_USERNAME"))
        ->setPassword(env("DB_PASSWORD"))
        ->dumpToFile('dump.sql');

        return response()->download(public_path('dump.sql'));
    }
}
