$(document).ready(function(){

    $("input[type=submit]").on("click", function(event){

        event.preventDefault();


        //console.log($( "input[name='id']")[0].value);


        if(Form.isEmpty($( "input[name='type']" ))

        )
        {
            console.error("Algo de errado não está certo");
            return false;
        }

        $("form").submit();
        return false;
    });

});