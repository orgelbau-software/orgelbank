<?php

interface GetRequestHandler
{

    public function validateGetRequest();

    public function handleInvalidGet();

    public function prepareGet();

    public function executeGet();
}
?>