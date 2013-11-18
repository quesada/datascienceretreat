(function($) {
    if ($('table').hasClass('responsive')) {
        var responsive_script = document.createElement("script");
        responsive_script.setAttribute("type", "text/javascript");
        responsive_script.setAttribute("src", "/wp-content/themes/joseq/javascripts/responsive-tables.js");
        document.body.appendChild(responsive_script);
    }
   
    /**
     * So you set a timeout on your alert-box, it will fade out
     */
    $('.alert-box[data-alert-timeout]').each(function() {
        var alert_timeout = parseInt($(this).data('alert-timeout'));
        $(this).delay(alert_timeout).fadeOut(function () {
            $(this).remove();
        });
    });
    /**
     * Add a wrapper to all the videos with an iframe embed
     */
    $('iframe[src*="vimeo.com"]').wrap('<div class="flex-video vimeo widescreen" />');
    $('iframe[src*="youtube.com"]').wrap('<div class="flex-video widescreen" />');
    /**
     * Masonry
     */
    $('.masonry').masonry({
        // options
        itemSelector : 'li',
        gutterWidth : 30
    });

    $.expr[':'].external = function(obj){
        return !obj.href.match(/^mailto\:/)
           && (obj.hostname != location.hostname)
           && !obj.href.match(/^javascript\:/)
           && !obj.href.match(/^$/)
    };

    $('a:external').attr('target', '_blank');

}(jQuery));