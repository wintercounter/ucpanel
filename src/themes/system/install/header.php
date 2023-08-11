<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>UCPanel {LANG="core.installer"}</title>
    <meta charset="UTF-8" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        
            $('form').submit(function(){
            
                $(this).find('input').each(function(){
                    
                    var a = true;
                
                    if($(this).attr('value') == ''){
                        
                        alert('Fields are can\'t be empty!');
                        a = true;
                        return false;
                        
                    }
                    
                    return a;
                
                });
            
            });
            
            $('#go_home').click(function(){
            
                window.location = "{$full_url}";
            
            });
            
            $('#go_admin').click(function(){
            
                window.location = "{$full_url}{$new_name}";
            
            });
        
        });
    </script>
    <style>
        body{background: #eee; text-shadow: 1px 1px #fff; color: #333; padding: 100px; font-family: Arial, Verdana, sans-serif;}
        li{margin-top: 5px; font-size: 12px; color: #666;}
        button{display: inline-block; clear: both; float: left;border: none; color: #fff; background: #369; cursor: pointer; padding: 10px; font-size: 16px; margin-top: 10px; font-weight: bold;opacity: 0.8; position: relative;}
        button:hover{opacity: 1}
        button:active{top: 2px;}
        label{display: block; height: 50px; line-height: 50px; width: 400px; float: left; clear: both; color: #888; font-size: 13px; font-weight: bold;}
        select,input{float: right; display: inline-block; color: #333; padding: 5px; margin-top: 10px;}
        label:last-of-type{border: none;}
        label > div {line-height: 14px !important; font-size: smaller; width: 100%; overflow: hidden;}
    </style>
    <body>