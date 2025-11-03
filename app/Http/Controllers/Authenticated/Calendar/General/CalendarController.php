<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            // dd($getPart,$getDate);
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request){
        $reserveDate = $request->input('reserve_date');
        $reservePart = $request->input('reserve_part');
// dd($reserveDate,$reservePart);
        // $userId = auth()->id();

        if($reservePart === "リモ1部"){
            $reservePart = 1;
          }else if($reservePart === "リモ2部"){
            $reservePart = 2;
          }else if($reservePart === "リモ3部"){
            $reservePart = 3;
          }

        $setting = ReserveSettings::where('setting_reserve',$reserveDate)
        ->where('setting_part',$reservePart)
        // ->where('user_id', $userId)
        ->first();
        if(!$setting){
            return redirect()->back()->with('error','該当なし');
        }

        $setting->users()->detach(auth()->id());
        $setting->increment('limit_users');
        return redirect()->back()->with('success','予約をキャンセルしました。');
    }
}
