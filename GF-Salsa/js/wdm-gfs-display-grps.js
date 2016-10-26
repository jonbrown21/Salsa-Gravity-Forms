jQuery(document).ready(function($){
  var input_btn;
  $(function() {
   $('body').addClass('wdm-grps');
 });
  $('body').append("<div id='wdm-grp-list' style='display:none;' >"+
    "<table id='wdm-grp-table'><thead><th>Group Name</th><th>Group Key</th></thead>"+
    "<tbody id='wdm-grp-tbody'></tbody></table><div class='pagination-grp'></div></div>");
  $("body").on("focus",".user_groups_setting .field-choice-text, .admin_groups_setting .field-choice-text,.state_groups_setting .field-choice-value", function() {
   
        // input_btn=$(this);
        input_btn=this;
       // console.log(input_btn);
       var $this =$(this);
       $.ajax({
        url:grpobj.ajaxurl,
        type:"POST",
        data:{action:'wdm_get_grp_list'},
        success:function(response){
          $this.attr("disabled", "disabled");
          var result = JSON.parse(response);
          $('#wdm-grp-tbody').html(result.grp_info);
          $('#wdm-grp-list').dialog({
            title: "Group List",
            resizable: false,
            open: function( event, ui ) {
              paginate();
            },
            close: function() {
              $this.removeAttr('disabled');
            }
          });
          function paginate(){
           var list = $("#wdm-grp-tbody tr");
           var numItems = $("#wdm-grp-tbody tr").length;
           var perPage = 15;
           $(".pagination-grp").pagination({
            items: $("#wdm-grp-tbody tr").length,
            itemsOnPage: perPage,
            cssStyle: "light-theme",
            onInit: function() {
              list.hide().slice(1,perPage).show();
            },
            onPageClick: function(pageNumber) {
              var showFrom = perPage * (pageNumber - 1);
              var showTo = showFrom + perPage;
              list.hide().slice(showFrom, showTo).show();
            }
          });
         }

       }
     });
     });
  
  $('body').on('click','.wdm-salsa-grp-list',function(){
    var choice=input_btn;
    var state_grp = choice.closest('.state_groups_setting');
    var $this=$(this);
    $this.css('background-color','#EFEFEF');
    var grp_name = $this.find('.wdm-salsa-grp-name').text();
    var grp_key = $this.find('.wdm-salsa-grp-key').text();
    if (state_grp !=null){
        $(choice).val(grp_key);
        $(choice).siblings('.field-choice-text').prev().trigger('click');
        $(choice).siblings('.field-choice-text').prev().trigger('click');
      
    }else{
        $(choice).val(grp_name);
        $(choice).siblings('.field-choice-value').val(grp_key);
        $(choice).prev().trigger('click');
        $(choice).prev().trigger('click');

    }

    $('#wdm-grp-list').dialog('close');

  });

});



