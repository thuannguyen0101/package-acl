<?php

namespace Workable\Budget\Enums;

class ResponseEnum
{
    const CODE_OK                    = 200;
    const CODE_CREATED               = 201;
    const CODE_ACCEPTED              = 202;
    const CODE_NO_CONTENT            = 204;
    const CODE_BAD_REQUEST           = 400;
    const CODE_UNAUTHORIZED          = 401;
    const CODE_FORBIDDEN             = 403;
    const CODE_NOT_FOUND             = 404;
    const CODE_CONFLICT              = 409;
    const CODE_UNPROCESSABLE_ENTITY  = 422;
    const CODE_INTERNAL_SERVER_ERROR = 500;
    const CODE_GATEWAY_TIMEOUT       = 504;
}
