<?php

/**
 * Routes parsed as strings.
 * Required keys are controller and action, name, namespace, parameters and methods are optional.
 *
 * Example route:
 *  '<route_as_string>' => [
 *      'name' => '<route_name>' (Used for route generation from View)
 *      'namespace' => f.e '\\Barebones\\Crons\\', (If controller namespace differs from default)
 *      'controller' => '<lowercase_name_of_controller_without_suffix>' (e.g. IndexController as index)
 *      'action' => '<name_of_function_from_controller_class>'
 *      'methods' => [
 *          <request_method>,
 *          ...
 *      ]
 *      'parameters' => [
 *          '<name_of_first_parameter_in_function>' = <parameter_value>,
 *          '<name_of_second_parameter_in_function>' = <parameter_value>,
 *          ...
 *      ]
 * ]
 * @var array $routes
 */

use Barebones\Constants\InputSanitizerConstant;

const ROUTES = [
    '/' => [
        'name' => 'index',
        'controller' => 'index',
        'action' => 'index',
        'methods' => [
            "GET",
            "POST",
        ],
    ],
    
    '/api/json' => [
        'name' => 'json_demo',
        'namespace' => '\\Barebones\\Api\\',
        'controller' => 'api',
        'action' => 'jsonDemo',
    ],

    '/api/xml' => [
        'name' => 'xml_demo',
        'namespace' => '\\Barebones\\Api\\',
        'controller' => 'api',
        'action' => 'xmlDemo',
    ],
];

/**
 * Routes parsed as regular expressions. Please, specify route regex
 * without trailing slash and line position characters (^, $), they are
 * added automatically.
 * Required keys are controller and action, name, namespace, parameters and methods are optional.
 *
 * Example regex route:
 *  '<route_as_regular_expression>' => [
 *      'name' => '<route_name>' (Used for route generation from View)
 *      'namespace' => f.e '\\Barebones\\Crons\\', (If controller namespace differs from default)
 *      'controller' => '<lowercase_name_of_controller_without_suffix>' (e.g. LoginController as login)
 *      'action' => '<name_of_function_from_controller_class>'
 *      'methods' => [
 *          '<request_method>',
 *          ...
 *      ]
 *      'parameters' => [
 *          '<name_of_first_parameter_in_function>' = <parameter_sanitization_type_constant>,
 *          '<name_of_second_parameter_in_function>' = <parameter_sanitization_type_constant>,
 *          ...
 *      ]
 * ]
 * @var array $regexRoutes
 */

const REGEX_ROUTES = [
    '\/exception\/(?<statusCode>[0-9]+)' => [
        'name' => 'exception_demo',
        'controller' => 'index',
        'action' => 'exceptionDemo',
        'methods' => [
            "GET",
            "POST",
        ],
        'parameters' => [
            'statusCode' => InputSanitizerConstant::UNSIGNED_INT,
        ],
        'query' => [
          'message' => InputSanitizerConstant::STRING,
        ],
    ],
];
