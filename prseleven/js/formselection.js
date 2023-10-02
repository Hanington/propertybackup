$(document).ready(function(){
  
$('#selectForm').change(function(){
    var formID = $(this).val();
    $('form').css('display','none');
    $('#'+formID).css('display','block');
})
  
  })
