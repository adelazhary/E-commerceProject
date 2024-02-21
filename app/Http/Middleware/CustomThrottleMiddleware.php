<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * The function limits the number of requests based on the client's IP address and returns a
     * response if the limit is exceeded.
     *
     * @param request The `` parameter in the `handle` function represents the incoming HTTP
     * request that your application receives. It contains information about the request such as
     * headers, parameters, cookies, and more. You can access various aspects of the request like the
     * request method, URL, input data, and more to process
     * @param Closure next The `` parameter in the `handle` function is a Closure that represents
     * the next middleware or the controller action that should be called after the current middleware
     * has completed its processing. When you call `()`, you are essentially passing the
     * request to the next middleware in the pipeline or the
     * @param maxAttempts The `maxAttempts` parameter in the `handle` function represents the maximum
     * number of attempts allowed within a certain time frame before the request is considered as
     * having too many attempts. In this case, the default value for `maxAttempts` is set to 60,
     * meaning that if the number of attempts
     * @param decayMinutes The `decayMinutes` parameter in the `handle` function represents the time in
     * minutes for which the rate limiter will keep track of the number of attempts made by a user
     * identified by a specific key (in this case, the user's IP address). After this time period
     * elapses, the rate
     *
     * @return The code snippet is a middleware function that handles rate limiting for incoming
     * requests. If the number of attempts from the same IP address exceeds the specified maximum
     * attempts within the decay period, a response with "Too Many Attempts" message and HTTP status
     * code 429 (TOO MANY REQUESTS) is returned. Otherwise, the request is allowed to pass through to
     * the next middleware in the pipeline, and the hit
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $request->ip(); // You can customize the key based on your needs

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response('Too Many Attempts.', Response::HTTP_TOO_MANY_REQUESTS);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $response;
    }
}
