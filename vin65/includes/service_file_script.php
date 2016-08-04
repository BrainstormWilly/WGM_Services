<script>
  var pollTO, service, file;

  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  function writeLog(log){
    $("#result").html(log);
  }

  function pollLog(){
      $.ajax({
        type: "GET",
        url: "service_log.php?file=" + file,
        async: true,
        cache: false,
        timeout: 10000,

        success: function(data){
          // var log_ary = $.parseJSON(data);
          // var log = "", log_item = "";
          // $.each(log_ary, function(i,v){
          //   log_item = "<p><strong>" + v[0] + ": Record: " + v[1] + "</strong></br>";
          //   log_item += "&nbsp;&nbsp;Customer: " + v[2] + "</br>";
          //   log_item += "&nbsp;&nbsp;Service: " + v[3] + "</br>";
          //   log_item += "&nbsp;&nbsp;Result: " + v[4] + "</p>";
          //   log = log_item + log;
          // });
          // if( log_ary.length > 0 )
          //   writeLog(log);
          console.log( "polling" );
          //writeLog(data);
          pollTO = setTimeout( pollLog, 2000 );
        },

        error: function(XMLHttpRequest, textStatus, errorThrown){
          // var log_item = "<p><strong>ERROR: Unable to load log<strong></br>";
          // log_item += "&nbsp;&nbsp;" + errorThrown + "</br>";
          // log_item += "&nbsp;&nbsp;" + textStatus + "</br>";
          // writeLog(log_item);
          // Keep Trying
          pollTO = setTimeout( pollLog, 2000 );
        }
      });
    }

    function runLoop(){
        $.ajax({
          type: "GET",
          url: service + "_service.php?file=" + file,
          async: true,
          cache: false,

          success: function(data){
            writeLog("<h4>" + data + "</h4>");
            clearTimeout(pollTO);
          },

          error: function(XMLHttpRequest, textStatus, errorThrown){
            var log_item = "<p><strong>ERROR: Unable to load loop<strong></br>";
            log_item += "&nbsp;&nbsp;" + errorThrown + "</br>";
            log_item += "&nbsp;&nbsp;" + textStatus + "</br>";
            writeLog(log_item);
            clearTimeout(pollTO);
          }
        });
      }

    $(document).ready(function(){
      service = getParameterByName("service");
      file = getParameterByName("file");
      runLoop();
      //pollLog();
    });

</script>
