;(function ($, window, undefined) {
  'use strict';
  
  $.ajaxSetup ({
      // Disable caching of AJAX responses
      cache: false
  });

  var $doc = $(document),
      Modernizr = window.Modernizr;

  $(document).ready(function() {
    $.fn.foundationAlerts           ? $doc.foundationAlerts() : null;
    $.fn.foundationButtons          ? $doc.foundationButtons() : null;
    $.fn.foundationAccordion        ? $doc.foundationAccordion() : null;
    $.fn.foundationNavigation       ? $doc.foundationNavigation() : null;
    $.fn.foundationTopBar           ? $doc.foundationTopBar() : null;
    $.fn.foundationCustomForms      ? $doc.foundationCustomForms() : null;
    $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
    $.fn.foundationTabs             ? $doc.foundationTabs({callback : $.foundation.customForms.appendCustomMarkup}) : null;
    $.fn.foundationTooltips         ? $doc.foundationTooltips() : null;
    $.fn.foundationMagellan         ? $doc.foundationMagellan() : null;
    $.fn.foundationClearing         ? $doc.foundationClearing() : null;

    $.fn.placeholder                ? $('input, textarea').placeholder() : null;
    
    // Progressbar
    $('#progressbars > div').each(function(){
      
      var p = $(this).data('percentage');
      
      $(this).find('div').css({width: p + '%'});
      
    });
    
    // Subscribe form
    $('#subscribe').submit(function(e){
      
      $('#msg').text(lang.subscribe_loading).show(500);
      
      $.ajax({ 
          type: "POST",
          data: $(this).serialize(),
          dataType : 'json',
          success: function(data){
            
              if(data.success >= 1){
                $('#msg').text(lang.subscribe_success);
              }
              else if(data.duplicated >= 1){
                $('#msg').text(lang.subscribe_duplicated);
              }
              else if(data.failed >= 1){
                $('#msg').text(lang.subscribe_failed);
              }
              
              $('#msg');
              
          },
          error: function(data){
              $('#msg').text(lang.subscribe_error);
          }
      });
    
      e.preventDefault();
      return false;
    
    });
    
  });

  // UNCOMMENT THE LINE YOU WANT BELOW IF YOU WANT IE8 SUPPORT AND ARE USING .block-grids
  // $('.block-grid.two-up>li:nth-child(2n+1)').css({clear: 'both'});
  // $('.block-grid.three-up>li:nth-child(3n+1)').css({clear: 'both'});
  // $('.block-grid.four-up>li:nth-child(4n+1)').css({clear: 'both'});
  // $('.block-grid.five-up>li:nth-child(5n+1)').css({clear: 'both'});

  // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
  if (Modernizr.touch && !window.location.hash) {
    $(window).load(function () {
      setTimeout(function () {
        window.scrollTo(0, 1);
      }, 0);
    });
  }

})(jQuery, this);
