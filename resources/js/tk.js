;(function($) {
    var path = '/locationautocomplete'

    $('#location').typeahead({
        source: function(query, process) {
            objects = []
            map = {}
            // clear hidden field
            $('#location_id').val()
            return $.get(path, { query: query }, function(data) {
                $.each(data, function(i, object) {
                    map[object.label] = object
                    objects.push(object.label)
                })
                process(objects)
            })
        },
        updater: function(item) {
            $('#location_id').val(map[item].id)
            return item
        },
    })
})(jQuery)

// initialize the bootstrap datatables
;(function($) {
    $('#tournament-datatable').DataTable({
        order: [[3, 'asc']],
        paging: true,
        searching: true,
        info: false,
    })
})(jQuery)

// initialize the datetimepicker
$(function() {
    $('#datetimepicker1').datetimepicker()
})

// on tournament edit form, show and hide the password field if type is private/public
$(function() {
    if ($('#tournament_type').length) {
        $('select[name="tournament_type"]').on('change', function() {
            switch ($(this).val()) {
                case 'private':
                    $('.tk-private-password').show()
                    break
                default:
                    $('.tk-private-password').hide()
                    break
            }
        })
    }
})
;(function($) {
    $(document).ready(function() {
        $('.main-menu').on('show.bs.collapse', function() {
            $('html').addClass('main-menu-visible')
        })

        $('.main-menu').on('hide.bs.collapse', function() {
            $('html').removeClass('main-menu-visible')
        })
    })
})(jQuery)

// ;(function($) {
//     $(document).ready(function() {
//         $('.navbar-desktop .nav > li').on('click', function() {
//             $('.navbar-desktop .nav > li.active').removeClass('active')
//             $(this).addClass('active')
//             // event.preventDefault()
//         })
//     })
// })(jQuery)
