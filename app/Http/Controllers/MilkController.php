<?php

namespace App\Http\Controllers;

use App\Models\Milk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Milk::all();
    }


    public function create()
    {
        //
    }


    /** 
     *  Проверка и запись показателей объема в цистернах
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'volume' => 'required'

        ]);

        $current_volume = MilkController::getresult();
        $max_left = 0;
        foreach ($current_volume as $el) {
            $max_left = max($max_left, (300 - $el->s_volume));
        }

        if (($request->volume) > 300 || (count($current_volume) == 5 && ($request->volume) > $max_left)) {
            return;
        }


        $bottle = 1;
        while ($bottle <= 5) {
            if (count($current_volume) == ($bottle - 1)) {
                $request->request->add(['bottle_num' => $bottle]);
                $request->request->add(['total_left' => (300 - ($request->volume))]);
                break;
            } else {

                if ((300 - ($current_volume[$bottle - 1]->s_volume)) > ($request->volume)) {
                    $request->request->add(['bottle_num' => $bottle]);
                    $request->request->add(['total_left' => ((300 - ($current_volume[$bottle - 1]->s_volume)) - ($request->volume))]);
                    break;
                }
            }

            $bottle++;
        }
        return Milk::create($request->all());
    }


    public function show(Milk $milk)
    {
        //
    }


    public function edit(Milk $milk)
    {
        //
    }


    public function update(Request $request, Milk $milk)
    {
        //
    }

    /**
     * Удаление записи
     */
    public function destroy(Milk $milk)
    {

        $milk->delete();

        return response()->json(['massage' => 'Запись удалена!']);
    }


     /**
     * Выгрузка суммарных значений показателей заполненности пяти цистерн
     */
    public function getresult()
    {
        return DB::table('milks')
            ->select('bottle_num', DB::raw('SUM(volume) as s_volume'))
            ->groupBy('bottle_num')
            ->orderBy('bottle_num')
            ->get();
    }
}
