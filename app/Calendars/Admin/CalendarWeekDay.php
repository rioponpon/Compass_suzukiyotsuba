<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    $ymd = $this->carbon->format("Y-m-d");
    $html=[];
    $html[]='<p class="day">' . $this->carbon->format("j") . '日</p>';
    // $html[] = $this->dayPartCounts($ymd);
    return implode('',$html);
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd){
    $html = [];
    $one_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part','1')->first();
    $two_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    $three_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

    // $parts = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', ['1','2','3'])->get()->keyBy('setting_part');

    $html[] = '<div class="text-left">';
    // foreach([1,2,3] as $part){
    //   if(isset($parts[$part])){
    //   $count = $parts[$part]->users->count();
    //   $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => $part]) . '"> ' .$part.'部' .$count. '</a></p>';
    // }
    // }

    if($one_part){
      $count = $one_part->users->count();
      $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 1]) . '">1部 '.$count. '</p>';
    }
    if($two_part){
      $count = $two_part->users->count();
      $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 2]) . '">2部 '.$count. '</p>';
    }
    if($three_part){
      $count = $three_part->users->count();
      $html[] = '<p class="day_part m-0 pt-1"><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 3]) . '">3部 '.$count. '</p>';
    }
    $html[] = '</div>';

    return implode("", $html);
  }

//   function reserveFrames($ymd)
// {
//     $html = [];

//     // 各部の残り枠を取得
//     $one_part_frame = $this->onePartFrame($ymd);
//     $two_part_frame = $this->twoPartFrame($ymd);
//     $three_part_frame = $this->threePartFrame($ymd);

//     $html[] = '<div class="text-left">';


//     if ($one_part_frame == "0") {
//         $html[] = '<p><a href="#" class="disabled">リモ1部(残り0枠)</a></p>';
//     } else {
//         $html[] = '<p><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 1]) . '">リモ1部(残り' . $one_part_frame . '枠)</a></p>';
//     }

//     if ($two_part_frame == "0") {
//         $html[] = '<p><a href="#" class="disabled">リモ2部(残り0枠)</a></p>';
//     } else {
//         $html[] = '<p><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 2]) . '">リモ2部(残り' . $two_part_frame . '枠)</a></p>';
//     }

//     if ($three_part_frame == "0") {
//         $html[] = '<p><a href="#" class="disabled">リモ3部(残り0枠)</a></p>';
//     } else {
//         $html[] = '<p><a href="' . route('calendar.admin.detail', ['date' => $ymd, 'part' => 3]) . '">リモ3部(残り' . $three_part_frame . '枠)</a></p>';
//     }

//     $html[] = '</div>';

//     return implode('', $html);
// }


  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
