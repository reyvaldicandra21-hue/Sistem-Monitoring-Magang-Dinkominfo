<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class DecryptRouteParameters
{
    /**
     * Route parameter names that should be decrypted when present.
     *
     * @var string[]
     */
    protected array $decryptable = [
        'id',
        'user',
        'pembimbing',
        'divisi',
        'pesertapkl',
        'peserta',
        'peserta_id',
        'laporanharian',
        'tugas',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route !== null) {
            foreach ($route->parameters() as $key => $value) {
                if (in_array($key, $this->decryptable, true)
                    && is_string($value)
                    && ! ctype_digit($value)
                ) {
                    try {
                        $decrypted = Crypt::decryptString($value);
                        $route->setParameter($key, $decrypted);
                        $request->route()->setParameter($key, $decrypted);
                    } catch (\Throwable $exception) {
                        abort(404);
                    }
                }
            }
        }

        return $next($request);
    }
}
