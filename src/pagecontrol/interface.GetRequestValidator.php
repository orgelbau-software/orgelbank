<?php

interface GetRequestValidator
{

    public function validateGetRequest();

    public function handleInvalidGet();
}
?>