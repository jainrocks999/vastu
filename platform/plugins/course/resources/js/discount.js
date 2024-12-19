'use strict'

$(() => {
    $(document).on('click', '[data-bb-toggle="select-from-existing"]', (e) => {
        e.preventDefault()

        $('.existing-course-discount-schema-items').show()
    })
})
