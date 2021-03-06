<html>
<head>
  <title>¡Habla con GiorGioT!</title>
  <link rel="shortcut icon" type="image/png" href="/giorgiot-favicon.jpg"/>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript">
    var accessToken = "3ed15a702c08424bb6a3b91830d0e6b3",
      baseUrl = "https://api.api.ai/v1/",
      $speechInput,
      $recBtn,
      recognition,
      messageRecording = "Grabando...",
      messageCouldntHear = "No podía oírte, ¿podrías decir eso otra vez?",
      messageInternalError = "Oh no, ha habido un error de servidor interno.",
      messageSorry = "Lo siento, mi creador todavía no me ha enseñado que significa eso!";
    $(document).ready(function() {
      $speechInput = $("#speech");
      $recBtn = $("#rec");
      $speechInput.keypress(function(event) {
        if (event.which == 13) {
          event.preventDefault();
          send();
        }
      });
      $recBtn.on("click", function(event) {
        switchRecognition();
      });
    });
    function startRecognition() {
      recognition = new webkitSpeechRecognition();
      recognition.onstart = function(event) {
        updateRec();
      };
      recognition.onresult = function(event) {
        var text = "";
          for (var i = event.resultIndex; i < event.results.length; ++i) {
            text += event.results[i][0].transcript;
          }
          setInput(text);
        stopRecognition();
      };
      recognition.onend = function() {
        stopRecognition();
      };
      recognition.lang = "es-AR";
      recognition.start();
    }
  
    function stopRecognition() {
      if (recognition) {
        recognition.stop();
        recognition = null;
      }
      updateRec();
    }
    function switchRecognition() {
      if (recognition) {
        stopRecognition();
      } else {
        startRecognition();
      }
    }
    function setInput(text) {
      $speechInput.val(text);
      send();
    }
    function updateRec() {
      $recBtn.text(recognition ? "Stop" : "Grabar Mensaje");
    }
    function send() {
      var text = $speechInput.val();
      $.ajax({
        type: "POST",
        url: baseUrl + "query",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + accessToken
        },
        data: JSON.stringify({query: text, lang: "es-ES", sessionId: "yaydevdiner"}),
        success: function(data) {
          prepareResponse(data);
        },
        error: function() {
          respond(messageInternalError);
        }
      });
    }
    function prepareResponse(val) {
      var debugJSON = JSON.stringify(val, undefined, 2),
        spokenResponse = val.result.speech;
      respond(spokenResponse);
      debugRespond(debugJSON);
    }
    function debugRespond(val) {
      $("#response").text(val);
    }
    function respond(val) {
      if (val == "") {
        val = messageSorry;
      }
      if (val !== messageRecording) {
        var msg = new SpeechSynthesisUtterance();
        msg.voiceURI = "Google Español";
        msg.text = val;
        msg.lang = "es-ES";
        window.speechSynthesis.speak(msg);
      }
      $("#spokenResponse").addClass("is-active").find(".spoken-response__text").html(val);
    }
  </script>
  <style type="text/css">
    html {
      box-sizing: border-box;
    }
    *, *:before, *:after {
      box-sizing: inherit;
    }
    body {
      background-color: #192837;
      font-family: "Titillium Web", Arial, sans-serif;
      font-size: 20px;
      margin: 0;
    }
    .container {
      position: absolute;
      margin: -50px auto 0;
      top: 50%;
      left: 50%;
      -webkit-transform: translate(-50%, -50%);
    }
    input {
      background-color: #126077;
      border: 1px solid #3F7F93;
      color: #A6CAE6;
      font-family: "Titillium Web";
      font-size: 20px;
      line-height: 43px;
      padding: 0 0.75em;
      width: 400px;
      -webkit-transition: all 0.35s ease-in;
    }
    textarea {
      background-color: #070F24;
      border: 1px solid #122435;
      color: #606B88;
      padding: 0.5em;
      width: 100%;
      -webkit-transition: all 0.35s ease-in;
    }
    input:active, input:focus, textarea:active, textarea:focus {
      outline: 1px solid #48788B;
    }
    .btn {
      background-color: #126178;
      border: 1px solid #549EAF;
      color: #549EAF;
      cursor: pointer;
      display: inline-block;
      font-family: "Titillium Web";
      font-size: 20px;
      line-height: 43px;
      padding: 0 0.75em;
      text-align: center;
      text-transform: uppercase;
      -webkit-transition: all 0.35s ease-in;
    }
    .btn:hover {
      background-color: #1888A9;
      color: #183035;
    }
    .spoken-response {
      max-height: 0;
      overflow: hidden;
      -webkit-transition: all 0.35s ease-in;
    }
    .spoken-response.is-active {
      max-height: 400px;
      max-width: 593px;
    }
    .spoken-response__text {
      background-color: #040E23;
      color: #7584A2;
      padding: 1em;
    }
  </style>
</head>
<body>
  <div class="container">
    <img style="padding-bottom: 75px; padding-left: 135px" src="giorgiot-logo.png" />
    <input id="speech" type="text">
    <button id="rec" class="btn">Grabar mensaje</button>
    <div id="spokenResponse" class="spoken-response">
      <div class="spoken-response__text"></div>
    </div>
  </div>
  <link href="https://fonts.googleapis.com/css?family=Titillium+Web:200" rel="stylesheet" type="text/css">
</body>
</html>
