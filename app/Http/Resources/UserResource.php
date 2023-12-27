<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class UserResource extends JsonResource
{
    /**
     * Response with token
     *
     * @var bool | string
     */
    protected string|bool $token = false;

    /**
     * Token expires timestamp when use $token
     *
     * @var bool | int
     */
    protected int|bool $expires = false;

    /**
     * Token type
     *
     * @var string
     */
    protected string $tokenType = 'bearer';

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $result = parent::toArray($request);

        if ($this->token) {
            $result['jwt'] = [
                'access_token' => $this->token,
                'token_type'   => $this->tokenType,
                'expires_in'   => $this->expires,
            ];
        }

        return $result;
    }

    /**
     * Response with token for API
     *
     * @param $token
     * @param $expires
     *
     * @return $this
     */
    public function withToken($token, $expires): static
    {
        $this->token   = $token;
        $this->expires = $expires;

        return $this;
    }
}
