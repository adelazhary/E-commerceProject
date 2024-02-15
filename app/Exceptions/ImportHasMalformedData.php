<?php

namespace App\Exceptions;

use Exception;

class ImportHasMalformedData extends Exception
{
    protected $message = 'The import has malformed data.';
}
