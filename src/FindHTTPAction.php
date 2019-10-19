<?php

namespace Level51\FindHTTPAction;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\Debug;
use SilverStripe\View\ViewableData;

/**
 * Finds actions by HTTP method used on the request.
 *
 * @package Level51\FindHTTPAction
 */
trait FindHTTPAction {

    /**
     * Attempts to lookup the action by checking HTTP method and falls back to default
     * behaviour if there is no specific rule.
     *
     * @param HTTPRequest $request
     *
     * @return array
     */
    protected function findAction($request) {
        $handlerClass = static::class;

        // We stop after RequestHandler; in other words, at ViewableData
        while ($handlerClass && $handlerClass != ViewableData::class) {
            $urlHandlers = Config::inst()->get($handlerClass, 'url_handlers', Config::UNINHERITED);

            if ($urlHandlers) {
                foreach ($urlHandlers as $rule => $action) {
                    if (isset($_REQUEST['debug_request'])) {
                        $class = static::class;
                        $remaining = $request->remaining() ?: '/';
                        Debug::message("Testing '{$rule}' with '{$remaining}' on {$class}");
                    }

                    $method = $request->httpMethod();
                    if ($request->match($rule, true)) {
                        if (isset($_REQUEST['debug_request'])) {
                            $class = static::class;
                            $latestParams = var_export($request->latestParams(), true);
                            $actionInfo = is_array($action) ? $action[$method] . ' (' . $method . ')' : $action;
                            Debug::message(
                                "Rule '{$rule}' matched to action '{$actionInfo}' on {$class}. " . "Latest request params: {$latestParams}"
                            );
                        }

                        // Check if action is provided as array with HTTP method => action mapping
                        if (is_array($action) && array_key_exists($method, $action)) {
                            return [
                                'rule'   => $rule,
                                'action' => $action[$method]
                            ];
                        }

                        return [
                            'rule'   => $rule,
                            'action' => $action,
                        ];
                    }
                }
            }

            $handlerClass = get_parent_class($handlerClass);
        }

        return null;
    }
}
