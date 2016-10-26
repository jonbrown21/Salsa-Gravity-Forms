jQuery(document).ready(function($){
  $('#fetch_custom_fields').click(function(){
   var $this=jQuery(this);
   $this.parent().find('.loading_img').html("<img src='"+fetchobj.img_url+"' />");
   var data = {
    dataType: "json",
    action:'wdm_custom_field_fetch',
  };
  $.post(fetchobj.ajaxurl, data, function(response) {
    var res = JSON.parse(response);
    $.each(res,function(key,value){
     if(key=='success'){
      $this.parent().find('.loading_img').addClass('success_msg');
    }else{
      $this.parent().find('.loading_img').addClass('error_msg');
    }
    $this.parent().find('.loading_img').html(value);
  });
  });
  return false;
});

  $('#fetch_grps').click(function(){
   var $this=jQuery(this);
   $this.parent().find('.loading_img').html("<img src='"+fetchobj.img_url+"' />");
   var data = {
     action:'wdm_fetch_grp',
   };
   $.post(fetchobj.ajaxurl, data, function(response) {
    var res = JSON.parse(response);
    $.each(res,function(key,value){
     if(key=='success'){
      $this.parent().find('.loading_img').addClass('success_msg');
    }else{
      $this.parent().find('.loading_img').addClass('error_msg');
    }
    $this.parent().find('.loading_img').html(value);
  });
  });
   return false;
 });

});

