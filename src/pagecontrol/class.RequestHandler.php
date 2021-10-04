<?php

class RequestHandler
{

    public static function handle($c)
    {
        $tpl = null;
        if ($_POST) {
            Log::debug("start handle POST request");
            if ($c instanceof PostRequestValidator) {
                Log::debug("validate POST request");
                if (false == $c->validatePostRequest()) {
                    Log::debug("invalid POST request");
                    $tpl = $c->handleInvalidPost();
                }
            }
            
            if ($tpl == null) {
                Log::debug("TPL is null");
                if ($c instanceof PostRequestHandler) {
                    Log::debug("prepare POST request");
                    $c->preparePost();
                    Log::debug("execute POST request");
                    $tpl = $c->executePost();
                }
            }
        } else {
            Log::debug("start handle GET request");
            if ($c instanceof GetRequestHandler) {
                Log::debug("validate GET request");
                if (false == $c->validateGetRequest()) {
                    Log::debug("GET request is invalid");
                    $tpl = $c->handleInvalidGet();
                }
            }
            
            if ($tpl == null) {
                Log::debug("TPL is null");
                if ($c instanceof GetRequestHandler) {
                    Log::debug("prepare GET request");
                    $c->prepareGet();
                    Log::debug("execute GET request");
                    $tpl = $c->executeGet();
                }
            }
            
        }
        if ($tpl == null) {
            throw new Exception("Die Methode liefert NULL als Template zurück", 1024);
        }
        
        $tpl->anzeigen();
    }
}
?>