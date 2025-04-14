<?php

namespace App\Utils\HttpFoundation;

use Illuminate\{Contracts\Foundation\Application,
    Contracts\Pagination\LengthAwarePaginator,
    Contracts\Support\Responsable,
    Http\JsonResponse,
    Http\RedirectResponse,
    Http\Resources\Json\JsonResource,
    Routing\Redirector,
    Validation\ValidationException};
use RuntimeException;

class Response implements Responsable
{
    use ResponseHTTPStatus;

    /**
     * GeneralResponse constructor.
     */
    public function __construct(
        private readonly string|null $message = 'Request Successfully',
        private readonly string|null $userMessage = null,
        private readonly HttpStatus  $status = HttpStatus::HTTP_OK,
        private                      $data = null,
        private readonly array       $option = []
    )
    {
    }

    /**
     * @param string $message
     * @param HttpStatus $status
     * @return static
     */
    public static function error(string $message, HttpStatus $status = HttpStatus::HTTP_UNAUTHORIZED): self
    {
        return new self(
            message: $message,
            status: $status,
            option: [
                'error' => $message,
            ]);
    }

    /**
     * @param string $message
     * @param HttpStatus $code
     * @return void
     */
    public static function exceptionError(string $message, HttpStatus $code = HttpStatus::HTTP_BAD_REQUEST): void
    {
        throw new RuntimeException(message: $message, code: $code);
    }

    /**
     * @param string|array $key
     * @param string $message
     * @throws ValidationException
     */
    public static function exceptionValidationError(string|array $key, string $message)
    {
        if (is_array(value: $key)) {
            throw ValidationException::withMessages(messages: $key);
        }

        throw ValidationException::withMessages(messages: [
            $key => [$message],
        ]);
    }

    /**
     * @param ...$properties
     * @return static
     */
    public static function response(...$properties): self
    {
        return new self(...$properties);
    }

    /**
     * @param string $to
     * @return Application|RedirectResponse|Redirector
     */
    public static function redirect(string $to): Redirector|RedirectResponse|Application
    {
        return redirect(to: $to)->send();
    }

    /**
     * @param $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        return response()
            ->json(data: $this->data(), status: $this->status->value);
    }

    /**
     * merge data and return array
     *
     * @return array
     */
    private function data(): array
    {
        $data = ['status' => $this->status];

        if (!is_null(value: $this->message)) {
            $data['message'] = $this->message;
        }

        if (!is_null(value: $this->userMessage)) {
            $data['user_message'] = $this->userMessage;
        }

        if (!is_null(value: $this->data)) {
            if ($this->data instanceof JsonResource && $this->data->resource instanceof LengthAwarePaginator) {
                $paginator = $this->data->resource;
                $data['data'] = $paginator->items();
                $data['pagination'] = $this->pagination(lengthAwarePaginator: $paginator);
            } elseif ($this->data instanceof LengthAwarePaginator) {
                $data['data'] = $this->data->items();
                $data['pagination'] = $this->pagination(lengthAwarePaginator: $this->data);
            } else {
                $data['data'] = $this->data;
            }
        }

        return array_merge($data, $this->option);
    }

    /**
     * @param $lengthAwarePaginator
     * @return array
     */
    private function pagination($lengthAwarePaginator): array
    {
        return [
            'total' => $lengthAwarePaginator->total(),
            'per_page' => $lengthAwarePaginator->perPage(),
            'current_page' => $lengthAwarePaginator->currentPage(),
            'last_page' => $lengthAwarePaginator->lastPage(),
            'from' => $lengthAwarePaginator->firstItem(),
            'to' => $lengthAwarePaginator->lastItem(),
        ];
    }
}
