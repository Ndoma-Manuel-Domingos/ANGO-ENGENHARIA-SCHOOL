//ajax form
$("form:not('.ajax_off')").submit(function (e) {
    e.preventDefault();
    var form = $(this);

    form.ajaxSubmit({
        url: form.attr("action"),
        type: "POST",
        dataType: "json",
        // beforeSend: function () {
        //     load.fadeIn(200).css("display", "flex");
        // },
        success: function (response) {
            //redirect
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                load.fadeOut(200);
            }

            //message
            if (response.message == 1) {
                toastr.success("Operação realizada com sucesso", "Correcto", {
                    timeOut: 3000
                });
                // $('#form-anolectivo')[0].reset();
            } else {
                toastr.warning("Não foi possível cadastrar este ano", "ERROR", {
                    timeOut: 3000
                });
            }

        },
        complete: function () {
            if (form.data("reset") === true) {
                form.trigger("reset");
            }
        }
    });
});