<?php

interface IRequestInfo
{
    function isPost();
    function isGet();
    function isPut();
    function isDelete();
}