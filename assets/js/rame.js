/**
 * Rame JS
 */


function rame(rebindHooks) {

    document.onready    = rebindHooks;

    this.api            = function(request, $ele) {
        $.get(request, function(data) {
            $ele.fadeOut(300, function() {
                $ele.html(data).fadeIn(300);
                rebindHooks();
            });
        });
    };

    this.get            = function(request, $ele) {
        $.get(request, function(data) {
            $ele.fadeOut(300, function() {
                $ele.html(data).fadeIn(300);
                rebindHooks();
            });
        });
    };

    this.post           = function(request, $ele, data) {
        $.post(request, data, function(result) {
            $ele.fadeOut(300, function() {
                $ele.html(result).fadeIn(300);
                rebindHooks();
            });
        });
    };
    
    this.delete         = function(request) {
        $.ajax({
            url: request,
            type: 'DELETE',
            success: function(result) {
                document.location = document.location;
            }
        });
    };
    
}