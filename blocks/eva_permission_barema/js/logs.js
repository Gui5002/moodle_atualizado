$(function (){
   $(".modallogs").click(function (){
      var id = $(this).attr('data-id');
      
      $.ajax({
         url: '/blocks/eva_permission_barema/users/ajax_user.php',
         data: 'acao=historicos&id='+id,
         success: function (resp) {
            var resposta = JSON.parse(resp);

            console.log(resposta);
            var lista = '';
            if (resposta) {
               for (var i=0; i < resposta.length; i++){
                  lista += '<li>'+resposta[i].log+'</li>'; 
               }
            }
            $(".lista").html(lista);
         }

      });

   });
});