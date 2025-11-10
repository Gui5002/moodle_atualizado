$(function (){
   $(".confModal").click(function(){
      var id = $(this).attr('data-userid');
      var html = '<a href=/blocks/eva_permission_barema/users/delete.php?id='+id+ ' class="btn btn-primary">Excluir</a>'
      $("#remove").html(html);

   });
});