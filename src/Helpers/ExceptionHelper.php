<?php

namespace Barebones\Helpers;

use Exception;

class ExceptionHelper
{
    public function traceToHtml($exception)
    {
        $html = '<ol>';

        foreach ($exception->getTrace() as $traceLog) {
            $html.="<li>
                        <i>{$traceLog['file']} ({$traceLog['line']})</i><br>
                        <i>{$traceLog['class']}->{$traceLog['function']}</i><br>
                    </li>";

        }

        return $html . '</ol>';
    }
    public function getErrorIcon($exception)
    {
        /**
         * Returns icon class name based on code of provided error.
         * @param Exception $exception
         * @return string
         */
        if ($exception instanceof Exception || method_exists($exception, 'getCode')) {
            switch ($exception->getCode()) {
                case 403:
                    return 'frankenstein';
                case 404:
                    return 'ghost';
                case 405:
                    return 'cake';
                case 433:
                    return 'toilet-paper';
            }
        }

        return 'skull';
    }
}