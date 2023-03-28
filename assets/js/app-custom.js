 $(document).on('focus', ':input', function() {
     $(this).attr('autocomplete', 'off');
 });
 $(document).ready(function() {
    
    
     //=============Sticky header==============
     $('#alert').affix({
         offset: {
             top: 10,
             bottom: function() {}
         }
     })
     $('#alert2').affix({
         offset: {
             top: 20,
             bottom: function() {}
         }
     })
     //========================================
     
 });


function toTimestamp(strDate){
   var datum = Date.parse(strDate);
   return datum/1000;
}

function numberFormatId(num) {
    //return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   
    return parts.join(",");
}