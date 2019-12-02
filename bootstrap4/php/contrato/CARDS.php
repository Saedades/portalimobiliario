$('#anghe').click(function () {

$('#ang').removeClass('animate_not_groww_ang');
$('#ang').parent().removeClass('animate_not_groww_ang');
$('#angt').removeClass('animate_not_headertop_ang');
$('#angt').removeClass('anghover');

$('#angt').css('cursor', 'default');

$('#ang').addClass('animate_groww_ang');
$('#ang').parent().addClass('animate_groww_ang');
$('#angt').addClass('animate_headertop_ang');

$('#angbody').show();
$('#min_ang').show();
});

$('#min_ang').click(function () {

$('#ang').removeClass('animate_groww_ang');
$('#ang').parent().removeClass('animate_groww_ang');
$('#angt').removeClass('animate_headertop_ang');

$('#ang').addClass('animate_not_groww_ang');
$('#ang').parent().addClass('animate_not_groww_ang');
$('#angt').addClass('animate_not_headertop_ang');
$('#angt').addClass('anghover');

$('#angt').css('cursor', 'pointer');

$('#angbody').hide();
$('#min_ang').hide();
});

$('#venhe').click(function () {

$('#leftcard').remove();

$('#ven').removeClass('animate_not_groww_ven');
$('#ven').parent().removeClass('animate_not_groww_ven');
$('#vent').removeClass('animate_not_headertop_ven');
$('#vent').removeClass('venhover');

$('#vent').css('cursor', 'default');

$('#ven').addClass('animate_groww_ven');
$('#ven').parent().addClass('animate_groww_ven');
$('#vent').addClass('animate_headertop_ven');

$('#venbody').show();
$('#min_ven').show();

$('#rightcard').after(left);

if (($('#ang').hasClass('animate_groww_ang') || $('#ang').parent().hasClass('animate_groww_ang'))) {
  $('#ang').removeClass('animate_groww_ang');
  $('#ang').parent().removeClass('animate_groww_ang');
  $('#angt').removeClass('animate_headertop_ang');

  $('#ang').addClass('animate_not_groww_ang');
  $('#ang').parent().addClass('animate_not_groww_ang');
  $('#angt').addClass('animate_not_headertop_ang');
  $('#angt').addClass('anghover');

  $('#angt').css('cursor', 'pointer');

  $('#angbody').hide();
  $('#min_ang').hide();
}

$('#anghe').click(function () {

  $('#ang').removeClass('animate_not_groww_ang');
  $('#ang').parent().removeClass('animate_not_groww_ang');
  $('#angt').removeClass('animate_not_headertop_ang');
  $('#angt').removeClass('anghover');

  $('#angt').css('cursor', 'default');

  $('#ang').addClass('animate_groww_ang');
  $('#ang').parent().addClass('animate_groww_ang');
  $('#angt').addClass('animate_headertop_ang');

  $('#angbody').show();
  $('#min_ang').show();

  if (($('#ven').hasClass('animate_groww_ven') || $('#ven').parent().hasClass('animate_groww_ven'))) {
    $('#leftcard').remove();

    $('#ven').removeClass('animate_groww_ven');
    $('#ven').parent().removeClass('animate_groww_ven');
    $('#vent').removeClass('animate_headertop_ven');

    $('#ven').addClass('animate_not_groww_ven');
    $('#ven').parent().addClass('animate_not_groww_ven');
    $('#vent').addClass('animate_not_headertop_ven');
    $('#vent').addClass('anghover');

    $('#vent').css('cursor', 'pointer');

    $('#venbody').hide();
    $('#min_ven').hide();

    $('#rightcard').before(left);
  }

  $('#min_ang').click(function () {
    $('#ang').removeClass('animate_groww_ang');
    $('#ang').parent().removeClass('animate_groww_ang');
    $('#angt').removeClass('animate_headertop_ang');

    $('#ang').addClass('animate_not_groww_ang');
    $('#ang').parent().addClass('animate_not_groww_ang');
    $('#angt').addClass('animate_not_headertop_ang');
    $('#angt').addClass('anghover');

    $('#angt').css('cursor', 'pointer');

    $('#angbody').hide();
    $('#min_ang').hide();
  });

});
});

$('#min_ven').click(function () {

$('#rightcard').before(left);

$('#ven').removeClass('animate_groww_ven');
$('#ven').parent().removeClass('animate_groww_ven');
$('#vent').removeClass('animate_headertop_ven');

$('#ven').addClass('animate_not_groww_ven');
$('#ven').parent().addClass('animate_not_groww_ven');
$('#vent').addClass('animate_not_headertop_ven');
$('#vent').addClass('anghover');

$('#vent').css('cursor', 'pointer');

$('#venbody').hide();
$('#min_ven').hide();
});

$('#min_ang').click(function () {
$('#rightcard').before(left);

$('#ang').removeClass('animate_groww_ang');
$('#ang').parent().removeClass('animate_groww_ang');
$('#angt').removeClass('animate_headertop_ang');

$('#ang').addClass('animate_not_groww_ang');
$('#ang').parent().addClass('animate_not_groww_ang');
$('#angt').addClass('animate_not_headertop_ang');
$('#angt').addClass('anghover');

$('#angt').css('cursor', 'pointer');

$('#angbody').hide();
$('#min_ang').hide();
});
