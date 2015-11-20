cary.site = (function($) {

  var self = {};
  
  self.init = function()
  {
    //Add span for h1 styling
    $('h1').wrapInner('<span />');
  
    //Get blog posts
    self.getBlogPosts();
  };
  
  self.getBlogPosts = function()
  {
    //Call for blog posts
    $.when(cary.helpers.getRSSFeed('http://www.caryaudio.com/feed/', 2)).done(function(data) {
        
      //If we have response data
      if(data.responseData !== null)
      {
        //Save to var
        var feed = data.responseData.feed;

        //If we have entries
        if(feed.entries.length > 0)
        {
          //Get html
          var html = cary.helpers.compileHTML('blog', feed.entries);
          
          //Embed HTML
          $('#blog-feed').append(html);
        }
      }
    });
  };
  
  return self;

})(jQuery);

(function($) { 
  $(function() {
    cary.site.init();
  });
  
})(jQuery);