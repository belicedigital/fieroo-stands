<?php

namespace Fieroo\Stands\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Fieroo\Stands\Models\StandsTypeTranslation;
use DB;
use Validator;

class StandController extends Controller
{

    public function getSelectList()
    {
        $response = [
            'status' => false
        ];

        try {
            $response['status'] = true;
            $response['data'] = StandsTypeTranslation::where('locale','=',auth()->user()->exhibitor->locale)->get();
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function getData(Request $request)
    {
        $response = [
            'status' => false
        ];

        try {
            $validation_data = [
                'id' => ['required', 'exists:furnishings,id'],
                'stand_type_id' => ['required', 'exists:stands_types,id'],
                'is_variant' => ['boolean']
            ];
    
            $validator = Validator::make($request->all(), $validation_data);
    
            if ($validator->fails()) {
                $response['message'] = trans('api.error_validation');
                return response()->json($response);
            }

            $furnishing_id = $request->id;
            $response['data'] = DB::table('furnishings')
                ->leftJoin('furnishings_stands_types', 'furnishings.id', '=', 'furnishings_stands_types.furnishing_id')
                ->where([
                    ['furnishings.id', '=', $furnishing_id],
                    ['furnishings_stands_types.stand_type_id', '=', $request->stand_type_id],
                    ['furnishings_stands_types.furnishing_id', '=', $furnishing_id],
                ])
                ->select('furnishings.*', 'furnishings_stands_types.min', 'furnishings_stands_types.max', 'furnishings_stands_types.is_supplied')
                ->first();

            if($request->is_variant) {
                $furnishing = DB::table('furnishings')->where('id', '=', $request->id)->first();
                $furnishing_id = $furnishing->variant_id;
                $response['data'] = DB::table('furnishings')
                    ->leftJoin('furnishings_stands_types', 'furnishings.variant_id', '=', 'furnishings_stands_types.furnishing_id')
                    ->where([
                        ['furnishings.id', '=', $request->id],
                        ['furnishings_stands_types.stand_type_id', '=', $request->stand_type_id],
                        ['furnishings_stands_types.furnishing_id', '=', $furnishing_id],
                    ])
                    ->select('furnishings.*', 'furnishings_stands_types.min', 'furnishings_stands_types.max', 'furnishings_stands_types.is_supplied')
                    ->first();
            }

            $response['status'] = true;
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
