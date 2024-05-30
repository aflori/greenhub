<?php

function getResponseMiddlewareError()
{
    return response()->json(['error' => 'not authorized content'], 403);
}
