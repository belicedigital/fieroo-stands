<?php

namespace Fieroo\Stands\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Fieroo\Stands\Models\StandsType;
use Fieroo\Exhibitors\Models\Category;
use Fieroo\Exhibitors\Models\StandTypeCategory;
use Fieroo\Stands\Models\StandsTypeTranslation;
use Validator;
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
        return view('stands::stands-types.create', ['categories' => Category::where('is_active', true)->get()]);
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

            if(!env('UNLIMITED')) {
                // check events limit for subscription
                $request_to_api = Http::get('https://manager-fieroo.belicedigital.com/api/stripe/'.env('CUSTOMER_EMAIL').'/check-limit/max_stands');
                if (!$request_to_api->successful()) {
                    throw new \Exception('API Error on get latest subscription '.$request_to_api->body());
                }
                
                $result_api = $request_to_api->json();
                if(isset($result_api['value']) && StandsType::all()->count() >= $result_api['value']) {
                    throw new \Exception('Hai superato il limite di Stands previsti dal tuo piano di abbonamento, per inserire altri Stands dovrai passare ad un altro piano aumentando il limite di stands disponibili.');
                }
            }

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

            if(!is_null($request->category_id)) {
                foreach($request->category_id as $category_id) {
                    $stand_type->categories()->create([
                        'category_id' => $category_id,
                    ]);
                }
            }
            
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
        $selected_categories_ids = StandTypeCategory::where('stand_type_id', $stand->stand_type_id)->pluck('category_id');
        // $categories = Category::where('is_active', true)->get();
        return view('stands::stands-types.edit', [
            'stand' => $stand,
            // 'categories' => $categories,
            'selected_categories_ids' => $selected_categories_ids
        ]);
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

            $old_stand_categories = $update_stand_type->categories()->pluck('category_id')->toArray();

            // insert the new ones that are not in the old array
            foreach($request->category_id as $index => $category_id) {
                if(!in_array($category_id, $old_stand_categories)) {
                    $update_stand_type->categories()->create([
                        'category_id' => $category_id,
                    ]);
                }
            }

            // delete the old ones that are not in the new array
            foreach($old_stand_categories as $index => $category_id) {
                if(!in_array($category_id, $request->category_id)) {
                    $update_stand_type->categories()->where('category_id', $category_id)->delete();
                }
            }

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
