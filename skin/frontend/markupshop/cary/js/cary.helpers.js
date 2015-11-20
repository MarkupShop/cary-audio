cary.helpers = (function($) {

  var self = {};
  
  /*
   * Compiles a JavaScript object into HTML
   *
   */
  self.compileHTML = function(template, data) {
    
    //Get template
    var tp = Handlebars.templates[template];

    //Return compiled HTML
    return tp(data);
  
  };
  
  /*
   * Returns RSS feed as JavaScript object
   *
   */
  self.getRSSFeed = function(url, count, output) {
    
    //Set params if not passed in
    count = count || 10;
    output = output || 'json';
    
    //Return the result
    return $.ajax({
      url: 'http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&output='+output+'&num='+count+'&callback=?&q=' + encodeURIComponent(url),
      dataType: 'jsonp'
    });
    
  };
  
  return self;

})(jQuery);