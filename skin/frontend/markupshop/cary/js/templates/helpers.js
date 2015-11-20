Handlebars.registerHelper('date', function(options) {
  
  //Convert into JS date
  var date = new Date(options.fn(this));
  
  //Day
  var day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
      month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  
  //Return formatted date
  return new Handlebars.SafeString(month[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear());
});