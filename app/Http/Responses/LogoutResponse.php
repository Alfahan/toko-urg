<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as ContractsLogoutResponse;

class LogoutResponse implements ContractsLogoutResponse
{
    /**
     * to Response
     *
     * @param mixed $request
     * @return void
     *
     */
    public function toResponse($request)
    {
        return redirect('/login');
    }
}
