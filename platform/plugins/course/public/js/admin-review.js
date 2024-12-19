$(function () {
    var e = function (e, t) {
        Botble.showButtonLoading(t),
          $httpClient
            .make()
            .post(e)
            .then(function (e) {
              var o = e.data;
              o.error
                ? Botble.showError(o.message)
                : (Botble.showSuccess(o.message),
                  $("#review-section-wrapper").load(
                    "".concat(
                      window.location.href,
                      " #review-section-wrapper > *",
                    ),
                  ),
                  t.closest(".modal").modal("hide"));
            })
            .finally(function () {
              return Botble.hideButtonLoading(t);
            });
      },
      t = function () {
        var e = $("#review-section-wrapper form");
        e.find('[data-bb-toggle="edit-reply"]').toggle(),
          e.find('[data-bs-target="#delete-review-reply-modal"]').toggle(),
          e.find(".reply-message").toggle(),
          e.find(".reply-form").toggle(),
          e.find('[data-bb-toggle="update-reply"]').toggle(),
          e.find('[data-bb-toggle="cancel-edit-reply"]').toggle();
      };
    $(document)
      .on("click", '[data-bb-toggle="review-delete"]', function (e) {
        $("#confirm-delete-review-button").data(
          "target",
          $(e.currentTarget).data("target"),
        ),
          $("#delete-review-modal").modal("show");
      })
      .on("click", "#confirm-delete-review-button", function (e) {
        var t = $(e.currentTarget),
          o = t.data("target");
        $httpClient
          .make()
          .withButtonLoading(t)
          .delete(o)
          .then(function (e) {
            var o = e.data;
            Botble.showSuccess(o.message),
              t.closest(".modal").modal("hide"),
              setTimeout(function () {
                var e;
                return (window.location.href =
                  null == o || null === (e = o.data) || void 0 === e
                    ? void 0
                    : e.next_url);
              }, 2e3);
          })
          .finally(function () {
            return Botble.hideButtonLoading(t);
          });
      })
      .on("click", '[data-bb-toggle="review-unpublish"]', function (t) {
        var o = $(t.currentTarget);
        e(route("reviews.unpublish", o.data("id")), o);
      })
      .on("click", '[data-bb-toggle="review-publish"]', function (t) {
        var o = $(t.currentTarget);
        e(route("reviews.publish", o.data("id")), o);
      })
      .on("click", '[data-bb-toggle="review-reply-delete"]', function (e) {
        var t = $(e.currentTarget),
          o = t.closest("form");
        $httpClient
          .make()
          .withButtonLoading(t)
          .delete(o.prop("action"))
          .then(function (e) {
            var t = e.data;
            Botble.showSuccess(t.message),
              o.closest(".modal").modal("hide"),
              setTimeout(function () {
                return window.location.reload();
              }, 2e3);
          });
      })
      .on("click", '[data-bb-toggle="edit-reply"]', function (e) {
        $(e.currentTarget), t();
      })
      .on("click", '[data-bb-toggle="cancel-edit-reply"]', function (e) {
        $(e.currentTarget), t();
      });
  });
  