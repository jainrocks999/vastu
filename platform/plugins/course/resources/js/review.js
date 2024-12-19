$(() => {
    if ($.rating) {
        $('#rating').rating({ size: 'xs' })
    }

    function handleError(data) {
        let messages = ''
        if (typeof data.errors !== 'undefined' && !Array.isArray(data.errors)) {
            messages = handleValidationError(data.errors)
        } else {
            if (typeof data.responseJSON !== 'undefined') {
                if (typeof data.responseJSON.errors !== 'undefined') {
                    if (data.status === 422) {
                        messages = handleValidationError(data.responseJSON.errors)
                    }
                } else if (typeof data.responseJSON.message !== 'undefined') {
                    messages = data.responseJSON.message
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            messages += item + '<br />'
                        })
                    })
                }
            } else {
                messages = data.statusText
            }
        }

        return messages
    }

    function handleValidationError(errors) {
        let message = ''
        $.each(errors, (index, item) => {
            message += item + '<br />'
        })
        return message
    }

    function submitReviewCourse() {
        let imagesReviewBuffer = []
        let setImagesFormReview = function (input) {
            const dT =
                new ClipboardEvent('').clipboardData || // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
                new DataTransfer() // specs compliant (as of March 2018 only Chrome)
            for (let file of imagesReviewBuffer) {
                dT.items.add(file)
            }
            input.files = dT.files
            loadPreviewImage(input)
        }

        let loadPreviewImage = function (input) {
            let $uploadText = $('.course-image-upload__text')
            const maxFiles = $(input).data('max-files')
            let filesAmount = input.files.length

            if (maxFiles) {
                if (filesAmount >= maxFiles) {
                    $uploadText.closest('.course-image-upload__uploader-container').addClass('d-none')
                } else {
                    $uploadText.closest('.course-image-upload__uploader-container').removeClass('d-none')
                }
                $uploadText.text(filesAmount + '/' + maxFiles)
            } else {
                $uploadText.text(filesAmount)
            }
            const viewerList = $('.course-image-viewer__list')
            const $template = $('#course-review-image-template').html()

            viewerList.addClass('is-loading')
            viewerList.find('.course-image-viewer__item').remove()

            if (filesAmount) {
                for (let i = filesAmount - 1; i >= 0; i--) {
                    viewerList.prepend($template.replace('__id__', i))
                }
                for (let j = filesAmount - 1; j >= 0; j--) {
                    let reader = new FileReader()
                    reader.onload = function (event) {
                        viewerList
                            .find('.course-image-viewer__item[data-id=' + j + ']')
                            .find('img')
                            .attr('src', event.target.result)
                    }
                    reader.readAsDataURL(input.files[j])
                }
            }
            viewerList.removeClass('is-loading')
        }

        $(document).on('change', '.course-form-review-course input[type=file]', function (event) {
            event.preventDefault()
            let input = this
            let $input = $(input)
            let maxSize = $input.data('max-size')
            Object.keys(input.files).map(function (i) {
                if (maxSize && input.files[i].size / 1024 > maxSize) {
                    let message = $input
                        .data('max-size-message')
                        .replace('__attribute__', input.files[i].name)
                        .replace('__max__', maxSize)
                    MartApp.showError(message)
                } else {
                    imagesReviewBuffer.push(input.files[i])
                }
            })

            let filesAmount = imagesReviewBuffer.length
            const maxFiles = $input.data('max-files')
            if (maxFiles && filesAmount > maxFiles) {
                imagesReviewBuffer.splice(filesAmount - maxFiles - 1, filesAmount - maxFiles)
            }

            setImagesFormReview(input)
        })

        $(document).on(
            'click',
            '.course-form-review-course .course-image-viewer__icon-remove',
            function (event) {
                event.preventDefault()
                const $this = $(event.currentTarget)
                let id = $this.closest('.course-image-viewer__item').data('id')
                imagesReviewBuffer.splice(id, 1)

                let input = $('.course-form-review-course input[type=file]')[0]
                setImagesFormReview(input)
            }
        )

        $(document).on('submit', '.course-form-review-course', function (e) {
            e.preventDefault()
            e.stopPropagation()
            const $this = $(e.currentTarget)
            const $button = $this.find('button[type=submit]')

            const courseId = $this.find('input[name=course_id]').val()

            $.ajax({
                type: 'POST',
                cache: false,
                url: $this.prop('action'),
                data: new FormData($this[0]),
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $button.prop('disabled', true).addClass('loading')
                    $this.find('.alert-message').removeClass('alert-success').addClass('d-none alert-warning')
                },
                success: (res) => {
                    if (!res.error) {
                        $this.find('textarea').val('')

                        const $item = $('.course-course-item[data-id=' + courseId + ']')
                        $item
                            .find('.course-course-star')
                            .removeClass()
                            .addClass('text-success mt-2')
                            .html(res.message)
                        if ($('#course-review-modal').length) {
                            $('#course-review-modal').modal('hide')
                        } else {
                            $this
                                .find('.alert-message')
                                .removeClass('alert-warning d-none')
                                .addClass('alert-success')
                                .html(res.message)
                        }
                    } else {
                        $this.find('.alert-message').html(res.message).removeClass('d-none')
                    }
                },
                error: (res) => {
                    let messages = handleError(res)
                    $this.find('.alert-message').html(messages).removeClass('d-none')
                },
                complete: () => {
                    $button.prop('disabled', false).removeClass('loading')
                },
            })
        })

        $(document).on('click', '.course-course-star .course-icon', function (e) {
            const $this = $(e.currentTarget)
            const $course = $this.closest('.course-course-item')

            const $modal = $('#course-review-modal')
            const $form = $modal.find('form')

            $modal.find('.course-course-image').attr('src', $course.find('.course-course-image').attr('src'))
            $modal.find('.course-course-name').text($course.find('.course-course-name').text())
            $form
                .find('input[name=star][value=' + $this.data('star') + ']')
                .prop('checked', true)
                .trigger('change')
            $form.find('input[name=course_id]').val($course.data('id'))

            $modal.modal('show')
        })

        $(document).on('hidden.bs.modal', '#course-review-modal', function (e) {
            const $this = $(e.currentTarget)
            $this.find('.course-produt-image').attr('src', '')
            $this.find('.course-produt-name').text('')
            $this.find('input[name=course_id]').val('')
        })
    }

    submitReviewCourse()
})
