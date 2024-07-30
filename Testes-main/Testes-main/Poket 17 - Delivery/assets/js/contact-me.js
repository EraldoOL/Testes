const captcha = new jCaptcha({
  el: '.jCaptcha',
  canvasClass: 'jCaptchaCanvas',
  canvasStyle: {
    width: 100,
    height: 15,
    textBaseline: 'top',
    font: '15px Arial',
    textAlign: 'left',
    fillStyle: '#484848'
  },
  callback: response => {
    if (response == 'success') {
      $.ajax({
        type: "POST",
        url: "./assets/mail/contact_me.php",
        data: {
          name: $("input#nome").val(),
          email: $("input#email").val(),
          phone: $("input#telefone").val(),
          subject: $("input#assunto").val(),
          message: $("textarea#mensagem").val()
        },
        success: function (res) {
          $(".alert > #message").html("Email enviado com sucesso.");
          $("#contact-form").trigger("reset");
        },
        error: function (res) {
          $(".alert > #message").html("Ocorreu um erro no envio.");
        },
        complete: function () {
          $(".alert").removeClass('d-none');
          setTimeout(function () {
            $("#submit-button").prop("disabled", false);
            $(".alert").addClass('d-none');
          }, 2500);
        }
      });
    }
    if (response == 'error') {
      $(".alert").removeClass('d-none');
      $(".alert > #message").html("Captcha incorreto.");
      
      setTimeout(function () {
        $("#submit-button").prop("disabled", false);
        $(".alert").addClass('d-none');
      }, 2500);
    }
  }
});

$("#contact-form").submit(function (e) {
  e.preventDefault();
  $("#submit-button").prop("disabled", true);

  captcha.validate();
});