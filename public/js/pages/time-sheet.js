jQuery(function($){
    $('#tbl-time-sheets').DataTable({
        "order": [[ 1, "asc" ]]
    });
    var dateFormat = "yy-mm-dd",
      from = $( "#from" )
        .datepicker({
          /* defaultDate: "+2w", */
          changeMonth: true,
          numberOfMonths: 1,
          showOn: "button",
          dateFormat: "yy-mm-dd",
          buttonText: "<i class='fa fa-calendar'></i>"
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+2w",
        changeMonth: true,
        numberOfMonths: 1,
        showOn: "button",
        dateFormat: "yy-mm-dd",
        buttonText: "<i class='fa fa-calendar'></i>"
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
});
