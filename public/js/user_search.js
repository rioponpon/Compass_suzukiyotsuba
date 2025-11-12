$(function () {
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
    $(this).toggleClass('open', 200);
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
    $(this).toggleClass('open', 200);
  });

  $('.main_categories').click(function () {
    $('.sub-list').slideUp();
    const subList = $(this).next('.sub-list');

    if (!subList.is(':visible')) {
      subList.slideDown();
    }

    $('.main_categories').not(this).removeClass('open');
    $(this).toggleClass('open', 200);
  });
});
