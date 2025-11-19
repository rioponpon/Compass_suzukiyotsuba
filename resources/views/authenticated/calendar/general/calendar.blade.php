<x-sidebar>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>

    </div>
    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>
<!-- モーダル -->
<div class="modal">
  <div class="modal__dg modal_close"></div>
  <div class="modal__content text-algin:left; p-3" style="max-width: 650px; margin: 0 auto;">
    <div class="modal-body">
      <p>予約日:<span class="modal_date"></span></p>
       <p>時間:<span class="modal_part"></span></p>
       <p>上記の予約をキャンセルしてもよろしいですか？</p>
       <div class="d-flex justify-content-between gap-3 mt-4">
        <button type="button" class="btn btn-primary modal_close">閉じる</button>

    <form  method="post" action="{{ route('deleteParts') }}">
      @csrf
          <input type="hidden"  name="reserve_date" id="modal_date_input" class="modal_date_input" value="">
          <input type="hidden"  name="reserve_part" id="modal_id_input" class="modal_id_input" value="">
          <button type="submit" class="btn btn-danger">キャンセル</button>
</form>
        </div>
      </div>
       </div>
    </div>
    </div>
</x-sidebar>

<style>
  .btn btn-primary{
text-align:left;
  }
</style>
