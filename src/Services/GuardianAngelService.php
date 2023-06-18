<?php

namespace Icrewsystems\GuardianAngel\Services;

use App\Jobs\FireWebhookJob;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Exception;

class GuardianAngelService
{
    /**
     * Log an exception and send an API call to Guardian Angel.
     *
     * @param Exception $exception
     * @param string|null $message
     * @param array|null $context
     * @return false|string
     */
    public function logException(\Throwable $exception, ?string $message = null, ?array $context = null)
    {
        try {
            $data = [
                'project_key' => config('app.project_key'),
                'type' => 'exception',
                'message' => $message ?? $exception->getMessage(),
                'context' => $context,
                'key' => 'NOT POPULATED YET',
                'exception' => [
                    'exception' => get_class($exception),
                    'error' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'class' => get_class($exception),
                    'stack_trace' => $exception->getTrace(),
                ],

                'project' => [
                    'name' => config('app.name'),
                    'debug' => config('app.debug'),
                    'environment' => config('app.env'),
                    'project_version' => config('larabug.project_version'),
                ],

                'host' => Request::getHost(),
                'method' => Request::method(),
                'fullUrl' => Request::fullUrl(),

                'additional' => [
                    'SERVER' => $_SERVER,
                    'HEADERS' => Request::header(),
                    'USER' => Request::server('USER'),
                    'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                    'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'] ?? null,
                    'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'] ?? null,
                    'PHP_VERSION' => phpversion(),
                    'OLD' => Request::hasSession() ? Request::old() : [],
                    'COOKIE' => $_COOKIE,
                    'SESSION' => session(),
                ],
                'PARAMETERS' => Request::all(),
                'chat_gpt_question' => $this->generate_gpt_question($message ?? $exception->getMessage()),
            ];

            $data['key'] = $this->generate_key_for_exception($data);
        } catch (Exception $e) {
            dd([
                'something went wrong',
                $e->getMessage(),
            ]);
        }

        FireWebhookJob::dispatch(json_encode($data));

        return true;

    }

    /**
     * generate_gpt_question
     *
     * @param mixed $message
     * @return void
     */
    private function generate_gpt_question($message)
    {
        return Str::replace(':MESSAGE:', $message, "I need you to do roleplay. You are a talented Laravel developer with 10+ years of experience. You are known for your ability to handle issues. I am going to provide you an exception message from a Laravel app. I need you to understand that exception message and give me steps to debug and fix the exception. Keep your response clear, crisp and concise in multiple steps, in a very professional manner in markdown format. The exception message is: :MESSAGE:");
    }

    /**
     * Generate a key for an exception. With this, we'll be able to
     * see if an exception is occuring multiple times.
     *
     *
     * @param array $data
     * @return string
     */
    private function generate_key_for_exception(array $data)
    {
        return 'exception.' . Str::slug($data['host'] . '_' . $data['method'] . '_' . $data['exception']['exception'] . '_' . $data['exception']['line'] . '_' . $data['exception']['file'] . '_' . $data['exception']['class']);
    }
}


