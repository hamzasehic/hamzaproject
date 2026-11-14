<?php

class HttpException extends Exception
{
    private $status;

    public function __construct($message, $status = 500)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}

class ValidationException extends HttpException
{
    public function __construct($message)
    {
        parent::__construct($message, 400);
    }
}

class UnauthorizedException extends HttpException
{
    public function __construct($message = 'Authentication required.')
    {
        parent::__construct($message, 401);
    }
}

class ForbiddenException extends HttpException
{
    public function __construct($message = 'You do not have permission to do this.')
    {
        parent::__construct($message, 403);
    }
}

class NotFoundException extends HttpException
{
    public function __construct($message = 'Not found.')
    {
        parent::__construct($message, 404);
    }
}