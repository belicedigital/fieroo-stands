<?php

namespace Fieroo\Stands\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Fieroo\Bootstrapper\Models\User;
use Fieroo\Payment\Models\Order;
use Fieroo\Exhibitors\Models\Exhibitor;
use Fieroo\Bootstrapper\Models\Setting;
use \stdClass;
use DB;
use Validator;
use Session;
use Mail;

class StandController extends Controller
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

    public function getSelectList()
    {
        $response = [
            'status' => false
        ];

        try {
            $response['status'] = true;
            $response['data'] = DB::table('stands_types_translations')->where('stands_types_translations.locale', '=', App::getLocale())->get();
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function getFurnishingsList(Request $request)
    {
        $response = [
            'status' => false,
            'message' => ''
        ];

        try {
            $list = DB::table('furnishings_stands_types')
                ->leftJoin('furnishings_translations', 'furnishings_stands_types.furnishing_id', '=', 'furnishings_translations.furnishing_id')
                ->leftJoin('furnishings', 'furnishings_stands_types.furnishing_id', 'furnishings.id')
                ->where([
                    ['furnishings_translations.locale', '=', App::getLocale()],
                    ['furnishings_stands_types.stand_type_id', '=', $request->stand_type_id],
                    ['furnishings.is_variant', '=', 0]
                ])
                ->select('furnishings.*', 'furnishings_translations.description', 'furnishings_stands_types.is_supplied', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
                ->orderBy('furnishings_stands_types.is_supplied', 'DESC')
                ->orderBy('furnishings_stands_types.min', 'ASC')
                ->get();
                
            foreach($list as $l) {
                $l->variants = DB::table('furnishings')->where('variant_id', '=', $l->id)->get();
            }
            $response['status'] = true;
            $response['data'] = $list;
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function index()
    {
        $email = auth()->user()->email;
        $exhibitor_data = DB::table('exhibitors_data')
            ->leftJoin('stands_types_translations', 'exhibitors_data.stand_type_id', 'stands_types_translations.stand_type_id')
            ->where([
                ['exhibitors_data.email_responsible', '=', $email],
                ['stands_types_translations.locale', '=', App::getLocale()],
            ])
            ->select('exhibitors_data.*', 'stands_types_translations.name')
            ->first();
        if(!is_object($exhibitor_data)) {
            abort(404);
        }
        $modules = DB::table('code_modules')->where('exhibitor_id', '=', $exhibitor_data->exhibitor_id)->get();

        $exhibitor_id = $exhibitor_data->exhibitor_id;
        $stand_type_id = $exhibitor_data->stand_type_id;

        $exhibitor = Exhibitor::findOrFail($exhibitor_data->exhibitor_id);

        $list = DB::table('furnishings_stands_types')
            ->leftJoin('furnishings_translations', 'furnishings_stands_types.furnishing_id', '=', 'furnishings_translations.furnishing_id')
            ->leftJoin('furnishings', 'furnishings_stands_types.furnishing_id', 'furnishings.id')
            ->where([
                ['furnishings_translations.locale', '=', App::getLocale()],
                ['furnishings_stands_types.stand_type_id', '=', $stand_type_id],
                ['furnishings.is_variant', '=', 0]
            ])
            ->select('furnishings.*', 'furnishings_translations.description', 'furnishings_stands_types.is_supplied', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
            ->orderBy('furnishings_stands_types.is_supplied', 'DESC')
            ->orderBy('furnishings_stands_types.min', 'ASC')
            ->get();
                
        foreach($list as $l) {
            $l->variants = DB::table('furnishings')->where('variant_id', '=', $l->id)->get();
        }

        $orders = DB::table('orders')
            ->leftJoin('furnishings', 'orders.furnishing_id', '=', 'furnishings.id')
            ->leftJoin('furnishings_translations', function($join) {
                $join->on('furnishings.id', '=', 'furnishings_translations.furnishing_id')
                    ->orOn('furnishings.variant_id', '=', 'furnishings_translations.furnishing_id');
            }) //'orders.furnishing_id', '=', 'furnishings_translations.furnishing_id')
            ->leftJoin('code_modules', function($join) use($exhibitor_id, $stand_type_id) {
                $join->on('code_modules.id', '=', 'orders.code_module_id');
                $join->where([
                    ['code_modules.exhibitor_id', '=', $exhibitor_id],
                    ['code_modules.stand_type_id', '=', $stand_type_id],
                ]);
            })
            ->leftJoin('furnishings_stands_types', function($join) {
                $join->on('furnishings_stands_types.stand_type_id', '=', 'code_modules.stand_type_id')
                    ->on('furnishings_stands_types.furnishing_id', '=', 'orders.furnishing_id');
            })
            ->where([
                ['orders.exhibitor_id', '=', $exhibitor_data->exhibitor_id],
                ['furnishings_translations.locale', '=', $exhibitor->locale],
            ])
            ->select('furnishings.*', 'furnishings_translations.description', 'orders.qty', 'orders.price as subtotal', 'orders.is_supplied', 'orders.code_module_id', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
            ->get();

        $total = 0;
        foreach($orders as $l) {
            /*
            $price = 0;
            if($l->extra_price) {
                $price = $l->price * $l->qty;
            } else {
                if($l->is_supplied) {
                    if($l->qty > $l->max) {
                        $diff = $l->qty - $l->max;
                        $price = $l->price * $diff;
                    }
                } else {
                    $price = $l->price * $l->qty;
                }
            }
            */
            $total += $l->subtotal;
        }

        $list = hasOrder($exhibitor_data->email_responsible) ? $orders : $list;

        //return view('stands::stands.index', ['list' => $list, 'close_furnishings' => $exhibitor_data->close_furnishings]);
        return view('stands::stands.index', ['list' => $list, 'modules' => $modules, 'exhibitor_data' => $exhibitor_data, 'total' => $total, 'stand_type_id' => $stand_type_id]);
    }

    public function indexFurnishings($code_module, $stand_type_id)
    {
        $list = DB::table('furnishings_stands_types')
            ->leftJoin('furnishings_translations', 'furnishings_stands_types.furnishing_id', '=', 'furnishings_translations.furnishing_id')
            ->leftJoin('furnishings', 'furnishings_stands_types.furnishing_id', 'furnishings.id')
            ->where([
                ['furnishings_translations.locale', '=', App::getLocale()],
                ['furnishings_stands_types.stand_type_id', '=', $stand_type_id],
                ['furnishings.is_variant', '=', 0]
            ])
            ->select('furnishings.*', 'furnishings_translations.description', 'furnishings_stands_types.is_supplied', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
            ->orderBy('furnishings_stands_types.is_supplied', 'DESC')
            ->get();
                
        foreach($list as $l) {
            $l->variants = DB::table('furnishings')->where('variant_id', '=', $l->id)->get();
        }

        $code = DB::table('code_modules')->where('id', '=', $code_module)->first();
        
        return view('stands::stands.furnishings', ['list' => $list, 'code_module' => $code->id, 'code' => $code->code, 'stand_type_id' => $stand_type_id]);
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
            //dd($response);

            $response['status'] = true;
            /*
            $response['data'] = DB::table('furnishings')
                ->leftJoin('furnishings_stands_types', 'furnishings.id', '=', 'furnishings_stands_types.furnishing_id')
                ->where([
                    ['furnishings.id', '=', $furnishing_id],
                    ['furnishings_stands_types.stand_type_id', '=', $request->stand_type_id],
                    ['furnishings_stands_types.furnishing_id', '=', $furnishing_id],
                ])
                ->select('furnishings.*', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
                ->first();
                */
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function confirm(Request $request)
    {
        $response = [
            'status' => false,
            'message' => trans('api.error_general')
        ];

        try {
            $validation_data = [
                'data' => ['required', 'json']
            ];
    
            $validator = Validator::make($request->all(), $validation_data);
    
            if ($validator->fails()) {
                $response['message'] = trans('api.error_validation');
                return response()->json($response);
            }

            $rows = json_decode($request->data);
            $exhibitor_data = DB::table('exhibitors_data')->where('email_responsible', '=', auth()->user()->email)->first();
            $furnishing_ids = [];
            $tot = 0;
            foreach($rows as $row) {
                Order::create([
                    'exhibitor_id' => $exhibitor_data->exhibitor_id,
                    'code_module_id' => $row->module_id,
                    'furnishing_id' => $row->id,
                    'qty' => $row->qty,
                    'is_supplied' => $row->is_supplied,
                    'price' => $row->price,
                    'created_at' => DB::raw('NOW()'),
                    'updated_at' => DB::raw('NOW()')
                ]);
                array_push($furnishing_ids, $row->id);
                $tot += $row->price;
            }

            /*
            $furnishings = DB::table('furnishings')
                ->leftJoin('furnishings_translations', function($join) {
                    $join->on('furnishings.id', '=', 'furnishings_translations.furnishing_id')
                        ->orOn('furnishings.variant_id', '=', 'furnishings_translations.furnishing_id');
                })
                ->whereIn('furnishings.id', $furnishing_ids)
                ->where('furnishings_translations.locale', '=', $exhibitor_data->locale)
                ->select('furnishings.*', 'furnishings_translations.description')
                ->get();
            */


            $exhibitor = Exhibitor::findOrFail($exhibitor_data->exhibitor_id);

            $orders = DB::table('orders')
                ->leftJoin('code_modules', 'orders.code_module_id', '=', 'code_modules.id')
                ->leftJoin('furnishings', 'orders.furnishing_id', '=', 'furnishings.id')
                ->leftJoin('furnishings_translations', function($join) {
                    $join->on('furnishings.id', '=', 'furnishings_translations.furnishing_id')
                        ->orOn('furnishings.variant_id', '=', 'furnishings_translations.furnishing_id');
                })
                ->where([
                    ['orders.exhibitor_id', '=', $exhibitor_data->exhibitor_id],
                    ['furnishings_translations.locale', '=', $exhibitor->locale]
                ])
                ->select('orders.*', 'furnishings_translations.description', 'code_modules.code', 'furnishings.color')
                ->get();

            // $data = [
            //     'locale' => $exhibitor->locale,
            //     //'furnishings' => $furnishings,
            //     'orders' => $orders,
            //     'tot' => $tot
            // ];
            $setting = Setting::take(1)->first();

            $body = formatDataForEmail([
                'orders' => $orders,
                'tot' => $tot,
            ], $exhibitor->locale == 'it' ? $setting->email_confirm_order_it : $setting->email_confirm_order_en);

            $data = [
                'body' => $body
            ];

            $subject = trans('emails.confirm_order', [], $exhibitor->locale);
            $email_from = env('MAIL_FROM_ADDRESS');
            $email_to = auth()->user()->email;
            // Mail::send('emails.confirm-order', ['data' => $data], function ($m) use ($email_from, $email_to, $subject) {
            //     $m->from($email_from, env('MAIL_FROM_NAME'));
            //     $m->to($email_to)->subject(env('APP_NAME').' '.$subject);
            // });
            Mail::send('emails.form-data', ['data' => $data], function ($m) use ($email_from, $email_to, $subject) {
                $m->from($email_from, env('MAIL_FROM_NAME'));
                $m->to($email_to)->subject(env('APP_NAME').' '.$subject);
            });

            /*
            $furnishings = DB::table('furnishings')
                ->leftJoin('furnishings_translations', function($join) {
                    $join->on('furnishings.id', '=', 'furnishings_translations.furnishing_id')
                        ->orOn('furnishings.variant_id', '=', 'furnishings_translations.furnishing_id');
                })
                ->whereIn('furnishings.id', $furnishing_ids)
                ->where('furnishings_translations.locale', '=', 'it')
                ->select('furnishings.*', 'furnishings_translations.description')
                ->get();
            */

            $orders = DB::table('orders')
                ->leftJoin('code_modules', 'orders.code_module_id', '=', 'code_modules.id')
                ->leftJoin('furnishings', 'orders.furnishing_id', '=', 'furnishings.id')
                ->leftJoin('furnishings_translations', function($join) {
                    $join->on('furnishings.id', '=', 'furnishings_translations.furnishing_id')
                        ->orOn('furnishings.variant_id', '=', 'furnishings_translations.furnishing_id');
                })
                ->where([
                    ['orders.exhibitor_id', '=', $exhibitor_data->exhibitor_id],
                    ['furnishings_translations.locale', '=', 'it']
                ])
                ->select('orders.*', 'furnishings_translations.description', 'code_modules.code', 'furnishings.color')
                ->get();

            // $data = [
            //     'locale' => 'it',
            //     //'furnishings' => $furnishings,
            //     'orders' => $orders,
            //     'tot' => $tot
            // ];
            $body = formatDataForEmail([
                'orders' => $orders,
                'tot' => $tot,
                'company' => $exhibitor->detail->company,
            ], $setting->email_to_admin_notification_confirm_order);

            $data = [
                'body' => $body
            ];

            $admin_mail_subject = trans('emails.confirm_order', [], 'it');
            $admin_mail_email_from = env('MAIL_FROM_ADDRESS');
            $admin_mail_email_to = env('MAIL_ARREDI');
            // Mail::send('emails.confirm-order', ['data' => $data], function ($m) use ($admin_mail_email_from, $admin_mail_email_to, $admin_mail_subject) {
            //     $m->from($admin_mail_email_from, env('MAIL_FROM_NAME'));
            //     $m->to($admin_mail_email_to)->subject(env('APP_NAME').' '.$admin_mail_subject);
            // });
            Mail::send('emails.form-data', ['data' => $data], function ($m) use ($admin_mail_email_from, $admin_mail_email_to, $admin_mail_subject) {
                $m->from($admin_mail_email_from, env('MAIL_FROM_NAME'));
                $m->to($admin_mail_email_to)->subject(env('APP_NAME').' '.$admin_mail_subject);
            });

            // chiudi arredi
            DB::table('exhibitors_data')->where('email_responsible', '=', auth()->user()->email)->update(['close_furnishings' => 1]);

            $response['message'] = trans('api.confirm_order');
            $response['status'] = true;
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function showFurnishings($code_module, $stand_type_id)
    {
        $exhibitor_data = DB::table('exhibitors_data')->where('email_responsible', '=', auth()->user()->email)->first();
        if(!is_object($exhibitor_data)) {
            abort(404);
        }
        $exhibitor_id = $exhibitor_data->exhibitor_id;

        $exhibitor = Exhibitor::findOrFail($exhibitor_data->exhibitor_id);

        $list = DB::table('orders')
            ->leftJoin('furnishings', 'orders.furnishing_id', '=', 'furnishings.id')
            ->leftJoin('furnishings_translations', function($join) {
                $join->on('furnishings.id', '=', 'furnishings_translations.furnishing_id')
                    ->orOn('furnishings.variant_id', '=', 'furnishings_translations.furnishing_id');
            }) //'orders.furnishing_id', '=', 'furnishings_translations.furnishing_id')
            ->leftJoin('code_modules', function($join) use($exhibitor_id, $stand_type_id) {
                $join->on('code_modules.id', '=', 'orders.code_module_id');
                $join->where([
                    ['code_modules.exhibitor_id', '=', $exhibitor_id],
                    ['code_modules.stand_type_id', '=', $stand_type_id],
                ]);
            })
            ->leftJoin('furnishings_stands_types', function($join) {
                $join->on('furnishings_stands_types.stand_type_id', '=', 'code_modules.stand_type_id')
                    ->on('furnishings_stands_types.furnishing_id', '=', 'orders.furnishing_id');
            })
            ->where([
                ['orders.exhibitor_id', '=', $exhibitor_data->exhibitor_id],
                ['orders.code_module_id', '=', $code_module],
                ['furnishings_translations.locale', '=', $exhibitor->locale],
            ])
            ->select('furnishings.*', 'furnishings_translations.description', 'orders.qty', 'orders.is_supplied', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
            ->get();
        
        $code_module = DB::table('code_modules')->where('id','=',$code_module)->first();
        if(!is_object($code_module)) {
            abort(404);
        }

        $total = 0;
        foreach($list as $l) {
            $price = 0;
            if($l->extra_price) {
                $price = $l->price * $l->qty;
            } else {
                if($l->is_supplied) {
                    if($l->qty > $l->max) {
                        $diff = $l->qty - $l->max;
                        $price = $l->price * $diff;
                    }
                } else {
                    $price = $l->price * $l->qty;
                }
            }
            $total += $price;
        }
        return view('stands::stands.show', ['list' => $list, 'code_module' => $code_module, 'total' => $total]);
    }
}
