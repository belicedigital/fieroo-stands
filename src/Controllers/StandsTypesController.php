<?php

namespace Fieroo\Stands\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fieroo\Stands\Models\StandsType;
use Fieroo\Stands\Models\StandsTypeTranslation;
use Validator;
use DB;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class StandsTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $it = StandsTypeTranslation::where('locale','it')->get();
        $en = StandsTypeTranslation::where('locale','en')->get();
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
            'name_en' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'description_en' => ['required'],
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

            // check events limit for subscription
            $url = 'https://manager-fieroo.belicedigital.com/api/stripe/'.env('CUSTOMER_EMAIL').'/check-limit/max_stands';
            $request_to_api = Http::get($url);
            if (!$request_to_api->successful()) {
                throw new \Exception('API Error on get latest subscription for '.$url.' '.$request_to_api->body());
            }
            $result_api = $request_to_api->json();
            if(isset($result_api['value']) && StandsType::all()->count() >= $result_api['value']) {
                throw new \Exception('Hai superato il limite di Stands previsti dal tuo piano di abbonamento, per inserire altri Stands dovrai passare ad un altro piano aumentando il limite di stands disponibili.');
            }
            
            // $value = $result_api['value'];
            // if(StandsType::all()->count() >= $result_api->value) {
            //     throw new \Exception('Hai superato il limite di Stands previsti dal tuo piano di abbonamento, per inserire altri Stands dovrai passare ad un altro piano aumentando il limite di stands disponibili.');
            // }

            $stand_type = StandsType::create();

            StandsTypeTranslation::insert([
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
                    'price' => $request->price,
                    'size' => $request->size,
                    'max_number_modules' => $request->max_number_modules,
                ],
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
        $stand = StandsTypeTranslation::findOrFail($id);
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
            $update_stand_type = StandsType::findOrFail($request->stand_type_id);
            $update_stand_type->updated_at = Carbon::now();
            $update_stand_type->save();

            $stand_translation = StandsTypeTranslation::findOrFail($id);
            $stand_translation->name = $request->name;
            $stand_translation->description = $request->description;
            $stand_translation->price = $request->price;
            $stand_translation->size = $request->size;
            $stand_translation->max_number_modules = $request->max_number_modules;
            $stand_translation->save();

            $stand_other_lang = StandsTypeTranslation::where([
                ['stand_type_id', '=', $request->stand_type_id],
                ['locale', '!=', $stand_translation->locale],
            ])->update([
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
