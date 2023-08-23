<?php

namespace Fieroo\Stands\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fieroo\Stands\Models\StandsType;
use Validator;
use DB;
use \Carbon\Carbon;

class StandsTypesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = DB::table('stands_types_translations')
            ->leftJoin('stands_types', 'stands_types_translations.stand_type_id', 'stands_types.id')
            ->select('stands_types_translations.*')
            ->get();
        $it = [];
        $en = [];
        foreach($all as $a) {
            if($a->locale === 'it') {
                array_push($it, $a);
            } else {
                array_push($en, $a);
            }
        }
        return view('stands::stands-types.index', ['it' => $it, 'en' => $en]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stands::stands-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation_data = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'size' => ['required', 'integer'],
            'max_number_modules' => ['required', 'integer']
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $stand_type = StandsType::create();

            $stand_type_translations = DB::table('stands_types_translations')->insert([
                [
                    'stand_type_id' => $stand_type->id,
                    'locale' => 'it',
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'size' => $request->size,
                    'max_number_modules' => $request->max_number_modules,
                ],
                [
                    'stand_type_id' => $stand_type->id,
                    'locale' => 'en',
                    'name' => $request->name_en,
                    'description' => $request->description_en,
                    'price' => $request->price_en,
                    'size' => $request->size_en,
                    'max_number_modules' => $request->max_number_modules_en,
                ]
            ]);

            $entity_name = trans('entities.stands_types');
            return redirect('admin/stands-types')->with('success', trans('forms.created_success',['obj' => $entity_name]));
        } catch(\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stand = DB::table('stands_types_translations')
            ->leftJoin('stands_types', 'stands_types_translations.stand_type_id', 'stands_types.id')
            ->where('stands_types_translations.id', '=', $id)
            ->select('stands_types_translations.*')
            ->first();
        if(is_null($stand) || !is_object($stand)) {
            abort(404);
        }
        return view('stands::stands-types.edit', ['stand' => $stand]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validation_data = [
            'stand_type_id' => ['required', 'integer', 'exists:stands_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'size' => ['required', 'integer'],
            'max_number_modules' => ['required', 'integer'],
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $update_stand_type = StandsType::find($request->stand_type_id);
            $update_stand_type->updated_at = Carbon::now();
            $update_stand_type->save();

            $stand_translations = DB::table('stands_types_translations')->where('id', '=', $id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'size' => $request->size,
                'max_number_modules' => $request->max_number_modules,
            ]);

            $entity_name = trans('entities.stands_types');
            return redirect('admin/stands-types')->with('success', trans('forms.updated_success',['obj' => $entity_name]));
        } catch(\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StandsType::findOrFail($id)->delete();
        $entity_name = trans('entities.stands_types');
        return redirect('admin/stands-types')->with('success', trans('forms.deleted_success',['obj' => $entity_name]));
    }
}
