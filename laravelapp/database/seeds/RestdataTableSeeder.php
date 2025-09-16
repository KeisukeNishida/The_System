<?php
use Illuminate\Database\Seeder;
use App\Restdata;

class RestdataTableSeeder extends Seeder
{
    public function run()
    {
        $param = [
            'message' => 'Google Japan',
            'url' => 'https://www.google.co.jp',
        ];
        $restdata = new Restdata;
        $restdata->fill($param)->save();
        $param = [
            'message' => 'Yahoo Japan',
            'url' => 'https://www.yahoo.co.jp',
        ];
        $restdata = new Restdata;
        $restdata->fill($param)->save();
        $param = [
            'message' => 'MSN Japan',
            'url' => 'http://www.msn.com/ja-jp',
        ];
        $restdata = new Restdata;
        $restdata->fill($param)->save();
    }
}
