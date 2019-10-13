<?php

interface PostRequestValidator
{

    public function validatePostRequest();

    public function handleInvalidPost();
}
?>