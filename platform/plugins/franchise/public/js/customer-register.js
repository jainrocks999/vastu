$((function(){if($(document).on("click","input[name=is_vendor]",(function(e){var a=$(e.currentTarget);1==a.val()?$('[data-bb-toggle="vendor-info"]').slideDown():($('[data-bb-toggle="vendor-info"]').slideUp(),a.closest("form").find("button[type=submit]").prop("disabled",!1))})),$('form.js-base-form input[name="shop_url"]').on("change",(function(e){var a=$(e.currentTarget),t=a.closest("form"),i=a.val();if(i){var n=t.find("[data-slug-value]");$.ajax({url:a.data("url"),type:"POST",data:{url:i},beforeSend:function(){a.prop("disabled",!0),t.find("button[type=submit]").prop("disabled",!0)},success:function(e){var i=e.error,s=e.message,d=e.data;i?(a.addClass("is-invalid").removeClass("is-valid"),$(".shop-url-status").removeClass("text-success").addClass("text-danger").text(s)):(a.removeClass("is-invalid").addClass("is-valid"),$(".shop-url-status").removeClass("text-danger").addClass("text-success").text(s),t.find("button[type=submit]").prop("disabled",!1)),null!=d&&d.slug&&n.html("".concat(n.data("base-url"),"/<strong>").concat(d.slug.substring(0,100).toLowerCase(),"</strong>"))},complete:function(){return a.prop("disabled",!1)}})}})),$(".become-vendor-form").length){var e=new Dropzone("#certificate-dropzone",{url:"#",autoProcessQueue:!1,paramName:"certificate_file",maxFiles:1,acceptedFiles:".pdf,.jpg,.jpeg,.png",addRemoveLinks:!0,dictDefaultMessage:$("#certificate-dropzone").data("placeholder"),maxfilesexceeded:function(e){this.removeFile(e)}}),a=new Dropzone("#government-id-dropzone",{url:"#",autoProcessQueue:!1,paramName:"government_id_file",maxFiles:1,acceptedFiles:".pdf,.jpg,.jpeg,.png",addRemoveLinks:!0,dictDefaultMessage:$("#government-id-dropzone").data("placeholder"),maxfilesexceeded:function(e){this.removeFile(e)}});$("form.become-vendor-form").on("submit",(function(t){t.preventDefault();var i=$(t.currentTarget),n=new FormData(i.get(0));e.getQueuedFiles().length>0&&n.append("certificate_file",e.getQueuedFiles()[0]),a.getQueuedFiles().length>0&&n.append("government_id_file",a.getQueuedFiles()[0]),$.ajax({url:i.prop("action"),type:"POST",data:n,processData:!1,contentType:!1,success:function(e){var a=e.data;null!=a&&a.redirect_url&&(window.location.href=a.redirect_url)},error:function(e){var a=e.responseJSON.errors;i.find("input").removeClass("is-invalid").removeClass("is-valid"),i.find(".invalid-feedback").remove(),a&&Object.keys(a).forEach((function(e){var t=i.find('input[name="'.concat(e,'"]')),n=a[e];if(["certificate_file","government_id_file"].includes(e)){var s=i.find('[data-field-name="'.concat(e,'"]'));s.find(".invalid-feedback").remove(),s.append('<div class="invalid-feedback" style="display: block">'.concat(n,"</div>"))}else t.addClass("is-invalid").removeClass("is-valid"),t.is(":checkbox")||t.parent().append('<div class="invalid-feedback">'.concat(n,"</div>"))}))}})}))}}));