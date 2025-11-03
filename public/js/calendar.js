$(function () {
  $('.open-modal-btn').on('click', function (e) {

    // e.preventDefault();

    const date = $(this).attr('data-date');
    const part = $(this).attr('data-part');

    console.log('予約日:', date);
    console.log('時間:', part);

    $('.modal_date').text(date);
    $('.modal_part').text(part);

    $('.modal_date_input').val(date);
    $('.modal_id_input').val(part);

    $(' .modal').addClass('open');
  });

  $('.modal_close').on('click', function () {
    $('.modal').removeClass('open');
  });
});
