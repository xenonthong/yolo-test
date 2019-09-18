<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class GrantTokenMiddleware
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @return string
     */
    protected function getBearerToken(): string
    {
        $split = explode(' ', $this->request->header('Authorization'));

        if (sizeof($split) !== 2) return response()->json('Unauthorized', 401);

        return $split[1];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection | null
     */
    protected function getRequesterGrantTokens()
    {
        return $this->user->tokens()
                          ->where('revoked', 0)// not revoked.
                          ->where('client_id', env('GRANT_CLIENT_ID'))// and its grant token.
                          ->get();
    }

    protected function requesterHasValidGrantToken(): bool
    {
        $grantTokens = $this->getRequesterGrantTokens();

        return (bool)$grantTokens->firstWhere('id', $this->getBearerToken());
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $this->user    = User::findOrFail($request->id);

        if ($this->requesterHasValidGrantToken()) {
            return $next($request);
        }

        return response()->json('Unauthorized', 401);
    }
}
