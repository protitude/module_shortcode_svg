(function ($) {
  $(document).ready(
    function () {
      $(".svg_list li:first").addClass('selected');
      dataCopyUpdate();
      $(".svg-alt").on(
        "change paste keyup", function () {
          dataCopyUpdate();
        }
      );
      $(".svg-width").on(
        "change paste keyup", function () {
          dataCopyUpdate();
        }
      );
      $('.svg-color').on(
        'change', function () {
          dataCopyUpdate();
        }
      );
      $(".svg_list li").click(
        function () {
          $color = $('.svg-color').val();
          $(".svg_list li").removeClass('selected');
          $(".svg_list li").find('use').removeAttr('fill');
          $(this).addClass('selected');
          $(this).find('use').attr('fill', '#' + $color);
          dataCopyUpdate();
        }
      );
      function dataCopyUpdate()
      {
        $name = $(".svg_list li.selected").attr('id');
        $alt = '';
        if ($('.svg-alt').val().length > 0 ) {
          $alt = 'alt="' + $(".svg-alt").val() + '" ';
        }
        $width = $(".svg-width").val();
        $color = $('.svg-color').val();
        $('.copy-icon').attr('data-clipboard', '[svg name=' + $name + ' ' + $alt + 'width=' + $width + ' color=' + $color + '][/svg]');
      }
      $(".copy-icon").click(
        function () {
          copyClipboard($('.copy-icon').attr('data-clipboard'));
        }
      );
    }
  );
}(jQuery));
