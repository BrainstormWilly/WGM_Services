<script type="text/javascript">
function longPolling(){
    $.ajaxSetup({ cache: false }); //This line is THE KEY to work under Internet Explorer
    $.ajax({
        type: "GET",
            url: "loader.php",
        dataType: 'json',
        async: true,

        success: function(response){
            if(response!=""){
                sendCommand(response);  //could be any other function
            }
            //longPolling();
            setTimeout(longPolling,1000);
                },
        error: function(){
            //longPolling();
            setTimeout(longPolling,1000);
        }

     });
};

$(document).ready(function(){   /*waits till the whole page loads*/
    longPolling(); /*start the initial request*/
});
</script>
